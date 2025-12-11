<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documents_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get documents for a project
     * @param  mixed $project_id
     * @param  mixed $id
     * @return mixed
     */
    public function get($project_id = '', $id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'realestate_documents')->row();
        }

        if (is_numeric($project_id)) {
            $this->db->where('project_id', $project_id);
        }

        $this->db->order_by('date_uploaded', 'desc');
        return $this->db->get(db_prefix() . 'realestate_documents')->result_array();
    }

    /**
     * Add new document
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        $data['uploaded_by'] = get_staff_user_id();
        $data['date_uploaded'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'realestate_documents', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Real Estate Document Uploaded [ID: ' . $insert_id . ', Type: ' . $data['document_type'] . ']');
            return $insert_id;
        }

        return false;
    }

    /**
     * Delete document
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $document = $this->get('', $id);
        
        if ($document) {
            // Delete physical file
            if (file_exists($document->file_path)) {
                unlink($document->file_path);
            }
            
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'realestate_documents');

            if ($this->db->affected_rows() > 0) {
                log_activity('Real Estate Document Deleted [ID: ' . $id . ']');
                return true;
            }
        }

        return false;
    }

    /**
     * Get documents by type
     * @param  mixed $project_id
     * @param  string $document_type
     * @return array
     */
    public function get_by_type($project_id, $document_type)
    {
        $this->db->where('project_id', $project_id);
        $this->db->where('document_type', $document_type);
        $this->db->order_by('date_uploaded', 'desc');
        return $this->db->get(db_prefix() . 'realestate_documents')->result_array();
    }
}
