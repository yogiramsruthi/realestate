<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plots_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get plots
     * @param  mixed $id
     * @param  array $where
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('p.*, pr.name as project_name');
        $this->db->from(db_prefix() . 'realestate_plots p');
        $this->db->join(db_prefix() . 'realestate_projects pr', 'pr.id = p.project_id', 'left');

        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            return $this->db->get()->row();
        }

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->order_by('p.date_created', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Add new plot
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_plots', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Update project plot counts
            $this->load->model('realestate/projects_model');
            $this->projects_model->update_plot_counts($data['project_id']);
            
            log_activity('New Real Estate Plot Created [ID: ' . $insert_id . ', Plot Number: ' . $data['plot_number'] . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update plot
     * @param  array $data
     * @param  mixed $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $data['last_updated'] = date('Y-m-d H:i:s');
        
        // Get old data to update project counts if needed
        $old_plot = $this->get($id);
        
        if (!$old_plot) {
            return false;
        }
        
        $old_project_id = $old_plot->project_id;
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_plots', $data);

        if ($this->db->affected_rows() > 0) {
            // Update project plot counts
            $this->load->model('realestate/projects_model');
            $this->projects_model->update_plot_counts(isset($data['project_id']) ? $data['project_id'] : $old_project_id);
            
            if (isset($data['project_id']) && $data['project_id'] != $old_project_id) {
                $this->projects_model->update_plot_counts($old_project_id);
            }
            
            log_activity('Real Estate Plot Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete plot
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        // Check if plot has bookings
        $this->db->where('plot_id', $id);
        $bookings = $this->db->get(db_prefix() . 'realestate_bookings')->result_array();

        if (count($bookings) > 0) {
            return false; // Cannot delete plot with bookings
        }

        $plot = $this->get($id);
        $project_id = $plot->project_id;

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_plots');

        if ($this->db->affected_rows() > 0) {
            // Update project plot counts
            $this->load->model('realestate/projects_model');
            $this->projects_model->update_plot_counts($project_id);
            
            log_activity('Real Estate Plot Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Get plots by project
     * @param  mixed $project_id
     * @return array
     */
    public function get_by_project($project_id)
    {
        $this->db->where('project_id', $project_id);
        $this->db->order_by('plot_number', 'asc');
        return $this->db->get(db_prefix() . 'realestate_plots')->result_array();
    }

    /**
     * Update plot status
     * @param  mixed $id
     * @param  string $status
     * @return boolean
     */
    public function update_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_plots', ['status' => $status]);

        if ($this->db->affected_rows() > 0) {
            $plot = $this->get($id);
            $this->load->model('realestate/projects_model');
            $this->projects_model->update_plot_counts($plot->project_id);
            return true;
        }

        return false;
    }

    /**
     * Add price history entry
     * @param  mixed $plot_id
     * @param  float $old_price
     * @param  float $new_price
     * @param  string $notes
     * @return boolean
     */
    public function add_price_history($plot_id, $old_price, $new_price, $notes = '')
    {
        $data = [
            'plot_id' => $plot_id,
            'old_price' => $old_price,
            'new_price' => $new_price,
            'changed_by' => get_staff_user_id(),
            'change_date' => date('Y-m-d H:i:s'),
            'notes' => $notes
        ];

        $this->db->insert(db_prefix() . 'realestate_plot_price_history', $data);
        return $this->db->insert_id();
    }

    /**
     * Get price history for a plot
     * @param  mixed $plot_id
     * @return array
     */
    public function get_price_history($plot_id)
    {
        $this->db->select('ph.*, CONCAT(s.firstname, " ", s.lastname) as changed_by_name');
        $this->db->from(db_prefix() . 'realestate_plot_price_history ph');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = ph.changed_by', 'left');
        $this->db->where('ph.plot_id', $plot_id);
        $this->db->order_by('ph.change_date', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Bulk update plots
     * @param  array $plot_ids
     * @param  array $data
     * @return boolean
     */
    public function bulk_update($plot_ids, $data)
    {
        if (empty($plot_ids) || !is_array($plot_ids)) {
            return false;
        }

        $data['last_updated'] = date('Y-m-d H:i:s');
        
        $this->db->where_in('id', $plot_ids);
        $this->db->update(db_prefix() . 'realestate_plots', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Bulk Plot Update [Count: ' . count($plot_ids) . ']');
            return true;
        }

        return false;
    }

    /**
     * Get plots by criteria for comparison
     * @param  array $criteria
     * @return array
     */
    public function get_for_comparison($criteria = [])
    {
        $this->db->select('p.*, pr.name as project_name');
        $this->db->from(db_prefix() . 'realestate_plots p');
        $this->db->join(db_prefix() . 'realestate_projects pr', 'pr.id = p.project_id', 'left');

        if (!empty($criteria['project_id'])) {
            $this->db->where('p.project_id', $criteria['project_id']);
        }

        if (!empty($criteria['min_price'])) {
            $this->db->where('p.price >=', $criteria['min_price']);
        }

        if (!empty($criteria['max_price'])) {
            $this->db->where('p.price <=', $criteria['max_price']);
        }

        if (!empty($criteria['status'])) {
            $this->db->where('p.status', $criteria['status']);
        }

        if (!empty($criteria['facing'])) {
            $this->db->where('p.facing', $criteria['facing']);
        }

        if (!empty($criteria['plot_category'])) {
            $this->db->where('p.plot_category', $criteria['plot_category']);
        }

        if (!empty($criteria['corner_plot'])) {
            $this->db->where('p.corner_plot', 1);
        }

        if (!empty($criteria['main_road_facing'])) {
            $this->db->where('p.main_road_facing', 1);
        }

        $this->db->order_by('p.price', 'asc');
        return $this->db->get()->result_array();
    }

    /**
     * Add plot to waiting list
     * @param  mixed $plot_id
     * @param  mixed $customer_id
     * @param  string $notes
     * @return mixed
     */
    public function add_to_waiting_list($plot_id, $customer_id, $notes = '')
    {
        $data = [
            'plot_id' => $plot_id,
            'customer_id' => $customer_id,
            'added_date' => date('Y-m-d H:i:s'),
            'status' => 'active',
            'notes' => $notes
        ];

        // Get next priority
        $this->db->select_max('priority');
        $this->db->where('plot_id', $plot_id);
        $result = $this->db->get(db_prefix() . 'realestate_plot_waiting_list')->row();
        $data['priority'] = ($result && $result->priority) ? $result->priority + 1 : 1;

        $this->db->insert(db_prefix() . 'realestate_plot_waiting_list', $data);
        return $this->db->insert_id();
    }

    /**
     * Get waiting list for a plot
     * @param  mixed $plot_id
     * @return array
     */
    public function get_waiting_list($plot_id)
    {
        $this->db->select('wl.*, c.company as customer_name');
        $this->db->from(db_prefix() . 'realestate_plot_waiting_list wl');
        $this->db->join(db_prefix() . 'clients c', 'c.userid = wl.customer_id', 'left');
        $this->db->where('wl.plot_id', $plot_id);
        $this->db->where('wl.status', 'active');
        $this->db->order_by('wl.priority', 'asc');
        return $this->db->get()->result_array();
    }

    /**
     * Calculate final price with discount
     * @param  float $price
     * @param  float $discount_percentage
     * @param  float $discount_amount
     * @return float
     */
    public function calculate_final_price($price, $discount_percentage = 0, $discount_amount = 0)
    {
        $discount = $discount_amount;
        if ($discount_percentage > 0) {
            $discount += ($price * $discount_percentage / 100);
        }
        return $price - $discount;
    }

    /**
     * Get analytics data for plots
     * @param  mixed $project_id
     * @return array
     */
    public function get_analytics($project_id = null)
    {
        $analytics = [];

        // Total plots by status
        $this->db->select('status, COUNT(*) as count');
        if ($project_id) {
            $this->db->where('project_id', $project_id);
        }
        $this->db->group_by('status');
        $analytics['by_status'] = $this->db->get(db_prefix() . 'realestate_plots')->result_array();

        // Total plots by category
        $this->db->select('plot_category, COUNT(*) as count');
        if ($project_id) {
            $this->db->where('project_id', $project_id);
        }
        $this->db->group_by('plot_category');
        $analytics['by_category'] = $this->db->get(db_prefix() . 'realestate_plots')->result_array();

        // Average price
        $this->db->select('AVG(price) as avg_price, MIN(price) as min_price, MAX(price) as max_price');
        if ($project_id) {
            $this->db->where('project_id', $project_id);
        }
        $analytics['price_stats'] = $this->db->get(db_prefix() . 'realestate_plots')->row_array();

        // Sales velocity (last 30 days)
        $this->db->select('COUNT(*) as sold_count');
        $this->db->where('status', 'sold');
        $this->db->where('last_updated >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        if ($project_id) {
            $this->db->where('project_id', $project_id);
        }
        $analytics['sales_velocity'] = $this->db->get(db_prefix() . 'realestate_plots')->row()->sold_count;

        return $analytics;
    }

    /**
     * Bulk create plots
     * @param  mixed $project_id
     * @param  string $prefix
     * @param  int $start_number
     * @param  int $count
     * @param  array $default_data
     * @return boolean
     */
    public function bulk_create($project_id, $prefix, $start_number, $count, $default_data = [])
    {
        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            $plot_number = $prefix . str_pad($start_number + $i, 3, '0', STR_PAD_LEFT);
            
            $data = array_merge($default_data, [
                'project_id' => $project_id,
                'plot_number' => $plot_number,
                'created_by' => get_staff_user_id(),
                'date_created' => date('Y-m-d H:i:s')
            ]);

            $this->db->insert(db_prefix() . 'realestate_plots', $data);
            if ($this->db->insert_id()) {
                $created++;
            }
        }

        if ($created > 0) {
            $this->load->model('realestate/projects_model');
            $this->projects_model->update_plot_counts($project_id);
            log_activity('Bulk Plot Creation [Project: ' . $project_id . ', Count: ' . $created . ']');
            return $created;
        }

        return false;
    }
}
