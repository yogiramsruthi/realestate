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
}
