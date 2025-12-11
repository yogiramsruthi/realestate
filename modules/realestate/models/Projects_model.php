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
            $this->db->select('p.*, CONCAT(s.firstname, " ", s.lastname) as manager_name');
            $this->db->from(db_prefix() . 'realestate_projects p');
            $this->db->join(db_prefix() . 'staff s', 's.staffid = p.project_manager', 'left');
            $this->db->where('p.id', $id);
            return $this->db->get()->row();
        }

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->order_by('date_created', 'desc');
        return $this->db->get(db_prefix() . 'realestate_projects')->result_array();
    }

    /**
     * Generate project code
     * @param string $project_short_name
     * @return string
     */
    public function generate_project_code($project_short_name = '')
    {
        $year = intval(date('Y')); // Ensure year is an integer for security
        
        // Get the last project code for this year with precise matching
        $this->db->select('project_code');
        $this->db->where("project_code REGEXP", $this->db->escape("^[A-Z]+-{$year}-[0-9]+$"));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $last_project = $this->db->get(db_prefix() . 'realestate_projects')->row();
        
        $serial_number = 1;
        if ($last_project && $last_project->project_code) {
            // Extract serial number from last project code
            $parts = explode('-', $last_project->project_code);
            if (count($parts) == 3 && intval($parts[1]) == $year) {
                $serial_number = intval($parts[2]) + 1;
            }
        }
        
        // Default short name if not provided
        if (empty($project_short_name)) {
            $project_short_name = 'PRJ';
        }
        
        // Sanitize short name to allow only alphanumeric characters
        $project_short_name = preg_replace('/[^A-Za-z0-9]/', '', $project_short_name);
        
        // Generate code: SHORTNAME-YEAR-SERIALNUMBER
        $project_code = strtoupper($project_short_name) . '-' . $year . '-' . str_pad($serial_number, 5, '0', STR_PAD_LEFT);
        
        return $project_code;
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
        $this->db->select('COUNT(*) as total_plots, SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available_plots');
        $this->db->where('project_id', $project_id);
        $result = $this->db->get(db_prefix() . 'realestate_plots')->row();

        $this->db->where('id', $project_id);
        $this->db->update(db_prefix() . 'realestate_projects', [
            'total_plots' => $result->total_plots,
            'available_plots' => $result->available_plots,
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

    /**
     * Get count of projects
     * @param array $where
     * @return int
     */
    public function get_count($where = [])
    {
        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }
        return $this->db->count_all_results(db_prefix() . 'realestate_projects');
    }
}
