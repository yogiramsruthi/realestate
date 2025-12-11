<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Patta_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get patta details for a project
     * @param  mixed $project_id
     * @param  mixed $id
     * @return mixed
     */
    public function get($project_id = '', $id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'realestate_patta_details')->row();
        }

        if (is_numeric($project_id)) {
            $this->db->where('project_id', $project_id);
        }

        $this->db->order_by('date_created', 'asc');
        return $this->db->get(db_prefix() . 'realestate_patta_details')->result_array();
    }

    /**
     * Add new patta detail
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_patta_details', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Real Estate Patta Detail Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Update patta detail
     * @param  array $data
     * @param  mixed $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $data['last_updated'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'realestate_patta_details', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Patta Detail Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete patta detail
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'realestate_patta_details');

        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Patta Detail Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }
}
