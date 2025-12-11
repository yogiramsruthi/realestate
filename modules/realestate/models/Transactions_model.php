<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transactions_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get transactions
     * @param  mixed $id
     * @param  array $where
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('t.*, b.id as booking_id, p.plot_number, c.company as customer_name');
        $this->db->from(db_prefix() . 'realestate_transactions t');
        $this->db->join(db_prefix() . 'realestate_bookings b', 'b.id = t.booking_id', 'left');
        $this->db->join(db_prefix() . 'realestate_plots p', 'p.id = b.plot_id', 'left');
        $this->db->join(db_prefix() . 'clients c', 'c.userid = b.customer_id', 'left');

        if (is_numeric($id)) {
            $this->db->where('t.id', $id);
            return $this->db->get()->row();
        }

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->order_by('t.transaction_date', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Add new transaction
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_transactions', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Update booking paid amount
            if ($data['transaction_type'] == 'payment') {
                $this->load->model('realestate/bookings_model');
                $this->bookings_model->update_paid_amount($data['booking_id'], $data['amount']);
            }
            
            log_activity('New Real Estate Transaction Created [ID: ' . $insert_id . ', Amount: ' . $data['amount'] . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Delete transaction
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $transaction = $this->get($id);
        
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_transactions');

        if ($this->db->affected_rows() > 0) {
            // Update booking paid amount
            if ($transaction->transaction_type == 'payment') {
                $this->load->model('realestate/bookings_model');
                $this->bookings_model->update_paid_amount($transaction->booking_id, -$transaction->amount);
            }
            
            log_activity('Real Estate Transaction Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Get transactions by booking
     * @param  mixed $booking_id
     * @return array
     */
    public function get_by_booking($booking_id)
    {
        $this->db->where('booking_id', $booking_id);
        $this->db->order_by('transaction_date', 'desc');
        return $this->db->get(db_prefix() . 'realestate_transactions')->result_array();
    }

    /**
     * Get total revenue
     * @param string $date_from
     * @param string $date_to
     * @return float
     */
    public function get_total_revenue($date_from = null, $date_to = null)
    {
        $this->db->select_sum('amount');
        
        if ($date_from && $date_to) {
            $this->db->where('transaction_date >=', $date_from);
            $this->db->where('transaction_date <=', $date_to);
        }
        
        $result = $this->db->get(db_prefix() . 'realestate_transactions')->row();
        return $result->amount ? $result->amount : 0;
    }
}
