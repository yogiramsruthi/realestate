<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Team_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get team assignments
     * @param  mixed $id
     * @param  array $where
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('t.*, CONCAT(s.firstname, " ", s.lastname) as staff_name, s.email as staff_email, pr.name as project_name');
        $this->db->from(db_prefix() . 'realestate_team_assignments t');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = t.staff_id', 'left');
        $this->db->join(db_prefix() . 'realestate_projects pr', 'pr.id = t.project_id', 'left');

        if (is_numeric($id)) {
            $this->db->where('t.id', $id);
            return $this->db->get()->row();
        }

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->order_by('t.date_created', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Add new team assignment
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_team_assignments', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Real Estate Team Assignment Created [ID: ' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update team assignment
     * @param  array $data
     * @param  mixed $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_team_assignments', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Team Assignment Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete team assignment
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_team_assignments');

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Team Assignment Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Get assignments by staff
     * @param  mixed $staff_id
     * @return array
     */
    public function get_by_staff($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        return $this->get();
    }

    /**
     * Get assignments by project
     * @param  mixed $project_id
     * @return array
     */
    public function get_by_project($project_id)
    {
        $this->db->where('project_id', $project_id);
        return $this->get();
    }
}
