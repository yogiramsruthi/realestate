<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Owners_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get owners for a project
     * @param  mixed $project_id
     * @param  mixed $id
     * @return mixed
     */
    public function get($project_id = '', $id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'realestate_owners')->row();
        }

        if (is_numeric($project_id)) {
            $this->db->where('project_id', $project_id);
        }

        $this->db->order_by('date_created', 'asc');
        return $this->db->get(db_prefix() . 'realestate_owners')->result_array();
    }

    /**
     * Add new owner
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_owners', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Real Estate Owner Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update owner
     * @param  array $data
     * @param  mixed $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $data['last_updated'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_owners', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Owner Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete owner
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_owners');

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Owner Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }
}
