<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bookings_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Calculate balance amount
     * @param  float $total_amount
     * @param  float $paid_amount
     * @return float
     */
    private function calculate_balance($total_amount, $paid_amount)
    {
        return $total_amount - $paid_amount;
    }

    /**
     * Get bookings
     * @param  mixed $id
     * @param  array $where
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('b.*, p.plot_number, pr.name as project_name, c.company as customer_name, CONCAT(s.firstname, " ", s.lastname) as assigned_staff_name');
        $this->db->from(db_prefix() . 'realestate_bookings b');
        $this->db->join(db_prefix() . 'realestate_plots p', 'p.id = b.plot_id', 'left');
        $this->db->join(db_prefix() . 'realestate_projects pr', 'pr.id = p.project_id', 'left');
        $this->db->join(db_prefix() . 'clients c', 'c.userid = b.customer_id', 'left');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = b.assigned_to', 'left');

        if (is_numeric($id)) {
            $this->db->where('b.id', $id);
            return $this->db->get()->row();
        }

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->order_by('b.date_created', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Add new booking
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        // Calculate balance amount
        $data['balance_amount'] = $this->calculate_balance($data['total_amount'], $data['paid_amount']);
        
        $this->db->insert(db_prefix() . 'realestate_bookings', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Update plot status
            $this->load->model('realestate/plots_model');
            $this->plots_model->update_status($data['plot_id'], 'booked');
            
            log_activity('New Real Estate Booking Created [ID: ' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update booking
     * @param  array $data
     * @param  mixed $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $data['last_updated'] = date('Y-m-d H:i:s');
        
        // Calculate balance amount if amounts are provided
        if (isset($data['total_amount']) && isset($data['paid_amount'])) {
            $data['balance_amount'] = $this->calculate_balance($data['total_amount'], $data['paid_amount']);
        }
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_bookings', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Booking Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete booking
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $booking = $this->get($id);
        $plot_id = $booking->plot_id;

        // Delete related transactions
        $this->db->where('booking_id', $id);
        $this->db->delete(db_prefix() . 'realestate_transactions');

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_bookings');

        if ($this->db->affected_rows() > 0) {
            // Update plot status back to available
            $this->load->model('realestate/plots_model');
            $this->plots_model->update_status($plot_id, 'available');
            
            log_activity('Real Estate Booking Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Get bookings by customer
     * @param  mixed $customer_id
     * @return array
     */
    public function get_by_customer($customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        return $this->get();
    }

    /**
     * Update paid amount
     * @param  mixed $id
     * @param  float $amount
     * @return boolean
     */
    public function update_paid_amount($id, $amount)
    {
        $booking = $this->get($id);
        $new_paid_amount = $booking->paid_amount + $amount;
        $new_balance = $this->calculate_balance($booking->total_amount, $new_paid_amount);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_bookings', [
            'paid_amount' => $new_paid_amount,
            'balance_amount' => $new_balance,
        ]);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Get booking statistics
     * @return array
     */
    public function get_statistics()
    {
        $stats = [];
        
        $stats['total_bookings'] = $this->db->count_all_results(db_prefix() . 'realestate_bookings');
        
        $this->db->select_sum('total_amount');
        $result = $this->db->get(db_prefix() . 'realestate_bookings')->row();
        $stats['total_revenue'] = $result->total_amount ? $result->total_amount : 0;
        
        $this->db->select_sum('paid_amount');
        $result = $this->db->get(db_prefix() . 'realestate_bookings')->row();
        $stats['total_paid'] = $result->paid_amount ? $result->paid_amount : 0;
        
        return $stats;
    }

    /**
     * Get count of bookings
     * @param array $where
     * @return int
     */
    public function get_count($where = [])
    {
        if (isset($where['date_from']) && isset($where['date_to'])) {
            $this->db->where('booking_date >=', $where['date_from']);
            $this->db->where('booking_date <=', $where['date_to']);
            unset($where['date_from'], $where['date_to']);
        }
        
        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }
        return $this->db->count_all_results(db_prefix() . 'realestate_bookings');
    }

    /**
     * Get total pending amount
     * @return float
     */
    public function get_total_pending_amount()
    {
        $this->db->select('SUM(total_amount - paid_amount) as pending');
        $result = $this->db->get(db_prefix() . 'realestate_bookings')->row();
        return $result->pending ? $result->pending : 0;
    }
}
