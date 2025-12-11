<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/projects_model');
        $this->load->model('realestate/owners_model');
        $this->load->model('realestate/patta_model');
        $this->load->model('realestate/documents_model');
    }

    /**
     * List all projects
     */
    public function index()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_projects');
        $data['projects'] = $this->projects_model->get();
        $this->load->view('projects/manage', $data);
    }

    /**
     * Add/Edit project
     * @param  mixed $id
     */
    public function project($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            
            // Calculate total_sqft from total_acres if provided
            if (isset($data['total_acres']) && !empty($data['total_acres'])) {
                $data['total_sqft'] = $data['total_acres'] * 43560; // 1 acre = 43,560 sq ft
            }
            
            if ($id == '') {
                if (!has_permission('realestate', '', 'create')) {
                    access_denied('realestate');
                }
                $id = $this->projects_model->add($data);
                if ($id) {
                    set_alert('success', _l('realestate_project_added'));
                    redirect(admin_url('realestate/projects'));
                }
            } else {
                if (!has_permission('realestate', '', 'edit')) {
                    access_denied('realestate');
                }
                $success = $this->projects_model->update($data, $id);
                if ($success) {
                    set_alert('success', _l('realestate_project_updated'));
                    redirect(admin_url('realestate/projects'));
                }
            }
        }

        if ($id == '') {
            $title = _l('realestate_add_project');
        } else {
            $data['project'] = $this->projects_model->get($id);
            $data['owners'] = $this->owners_model->get($id);
            $data['patta_details'] = $this->patta_model->get($id);
            $data['documents'] = $this->documents_model->get($id);
            $title = _l('realestate_edit_project');
        }

        // Get staff for project manager
        $this->load->model('staff_model');
        $data['staff'] = $this->staff_model->get();
        $data['project_id'] = $id;
        
        $data['title'] = $title;
        $this->load->view('projects/project', $data);
    }

    /**
     * Manage owners for a project (AJAX)
     */
    public function manage_owners($project_id)
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['project_id'] = $project_id;
        $data['owners'] = $this->owners_model->get($project_id);
        $this->load->view('projects/owners', $data);
    }

    /**
     * Add/Edit owner via AJAX
     */
    public function save_owner()
    {
        $id = $this->input->post('id');
        $data = $this->input->post();
        unset($data['id']);

        if (empty($id)) {
            $result = $this->owners_model->add($data);
            $message = _l('realestate_owner_added');
        } else {
            $result = $this->owners_model->update($data, $id);
            $message = _l('realestate_owner_updated');
        }

        if ($result) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save owner']);
        }
    }

    /**
     * Delete owner via AJAX
     */
    public function delete_owner($id)
    {
        $result = $this->owners_model->delete($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => _l('realestate_owner_deleted')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete owner']);
        }
    }

    /**
     * Manage patta details for a project (AJAX)
     */
    public function manage_patta($project_id)
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['project_id'] = $project_id;
        $data['patta_details'] = $this->patta_model->get($project_id);
        $this->load->view('projects/patta', $data);
    }

    /**
     * Add/Edit patta via AJAX
     */
    public function save_patta()
    {
        $id = $this->input->post('id');
        $data = $this->input->post();
        unset($data['id']);

        if (empty($id)) {
            $result = $this->patta_model->add($data);
            $message = _l('realestate_patta_added');
        } else {
            $result = $this->patta_model->update($data, $id);
            $message = _l('realestate_patta_updated');
        }

        if ($result) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save patta detail']);
        }
    }

    /**
     * Delete patta via AJAX
     */
    public function delete_patta($id)
    {
        $result = $this->patta_model->delete($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => _l('realestate_patta_deleted')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete patta detail']);
        }
    }

    /**
     * Upload document
     */
    public function upload_document()
    {
        if (!has_permission('realestate', '', 'create')) {
            access_denied('realestate');
        }

        $project_id = $this->input->post('project_id');
        $document_type = $this->input->post('document_type');
        $document_name = $this->input->post('document_name');
        $description = $this->input->post('description');

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $upload_path = FCPATH . 'uploads/realestate/project_' . $project_id . '/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $file_name = time() . '_' . $_FILES['file']['name'];
            $file_path = $upload_path . $file_name;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                $data = [
                    'project_id' => $project_id,
                    'document_type' => $document_type,
                    'document_name' => $document_name,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'file_size' => $_FILES['file']['size'],
                    'description' => $description,
                ];

                $result = $this->documents_model->add($data);
                if ($result) {
                    set_alert('success', _l('realestate_document_uploaded'));
                } else {
                    set_alert('danger', 'Failed to save document');
                }
            } else {
                set_alert('danger', 'Failed to upload file');
            }
        } else {
            set_alert('danger', 'No file uploaded');
        }

        redirect(admin_url('realestate/projects/project/' . $project_id));
    }

    /**
     * Delete document
     */
    public function delete_document($id, $project_id)
    {
        if (!has_permission('realestate', '', 'delete')) {
            access_denied('realestate');
        }

        $result = $this->documents_model->delete($id);
        if ($result) {
            set_alert('success', _l('realestate_document_deleted'));
        } else {
            set_alert('danger', 'Failed to delete document');
        }

        redirect(admin_url('realestate/projects/project/' . $project_id));
    }

    /**
     * Download document
     */
    public function download_document($id)
    {
        $document = $this->documents_model->get('', $id);
        
        if ($document && file_exists($document->file_path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $document->file_name . '"');
            header('Content-Length: ' . filesize($document->file_path));
            readfile($document->file_path);
            exit;
        } else {
            set_alert('danger', 'Document not found');
            redirect(admin_url('realestate/projects'));
        }
    }

    /**
     * Generate project code via AJAX
     */
    public function generate_code()
    {
        try {
            $short_name = $this->input->post('short_name');
            
            if (empty($short_name)) {
                echo json_encode(['success' => false, 'message' => 'Short name is required']);
                return;
            }
            
            $project_code = $this->projects_model->generate_project_code($short_name);
            
            echo json_encode(['success' => true, 'project_code' => $project_code]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to generate project code']);
        }
    }

    /**
     * Get owner details (for edit)
     */
    public function get_owner($id)
    {
        $owner = $this->owners_model->get('', $id);
        header('Content-Type: application/json');
        echo json_encode($owner);
    }

    /**
     * Get patta details (for edit)
     */
    public function get_patta($id)
    {
        $patta = $this->patta_model->get('', $id);
        header('Content-Type: application/json');
        echo json_encode($patta);
    }

    /**
     * Delete project
     * @param  mixed $id
     */
    public function delete($id)
    {
        if (!has_permission('realestate', '', 'delete')) {
            access_denied('realestate');
        }

        if (!$id) {
            redirect(admin_url('realestate/projects'));
        }

        $response = $this->projects_model->delete($id);
        if ($response) {
            set_alert('success', _l('realestate_project_deleted'));
        } else {
            set_alert('warning', _l('realestate_project_delete_error'));
        }
        redirect(admin_url('realestate/projects'));
    }
}
