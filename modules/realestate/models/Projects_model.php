<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projects_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all projects
     * @param  array $where
     * @return array
     */
    public function get($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'realestate_projects')->row();
        }

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->order_by('date_created', 'desc');
        return $this->db->get(db_prefix() . 'realestate_projects')->result_array();
    }

    /**
     * Add new project
     * @param array $data project data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_projects', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Real Estate Project Created [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update project
     * @param  array $data project data
     * @param  mixed $id   project id
     * @return boolean
     */
    public function update($data, $id)
    {
        $data['last_updated'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_projects', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Project Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete project
     * @param  mixed $id project id
     * @return boolean
     */
    public function delete($id)
    {
        // Check if project has plots
        $this->db->where('project_id', $id);
        $plots = $this->db->get(db_prefix() . 'realestate_plots')->result_array();

        if (count($plots) > 0) {
            return false; // Cannot delete project with plots
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_projects');

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Project Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Update plot counts for a project
     * @param  mixed $project_id
     * @return void
     */
    public function update_plot_counts($project_id)
    {
        $this->db->where('project_id', $project_id);
        $total_plots = $this->db->count_all_results(db_prefix() . 'realestate_plots');

        $this->db->where('project_id', $project_id);
        $this->db->where('status', 'available');
        $available_plots = $this->db->count_all_results(db_prefix() . 'realestate_plots');

        $this->db->where('id', $project_id);
        $this->db->update(db_prefix() . 'realestate_projects', [
            'total_plots' => $total_plots,
            'available_plots' => $available_plots,
        ]);
    }

    /**
     * Get project statistics
     * @return array
     */
    public function get_statistics()
    {
        $stats = [];
        
        $stats['total_projects'] = $this->db->count_all_results(db_prefix() . 'realestate_projects');
        
        $this->db->where('status', 'active');
        $stats['active_projects'] = $this->db->count_all_results(db_prefix() . 'realestate_projects');
        
        return $stats;
    }
}
