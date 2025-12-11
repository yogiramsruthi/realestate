<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/projects_model');
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
            $title = _l('realestate_edit_project');
        }

        // Get staff for project manager
        $this->load->model('staff_model');
        $data['staff'] = $this->staff_model->get();
        
        $data['title'] = $title;
        $this->load->view('projects/project', $data);
    }

    /**
     * Generate project code via AJAX
     */
    public function generate_code()
    {
        $short_name = $this->input->post('short_name');
        $project_code = $this->projects_model->generate_project_code($short_name);
        
        header('Content-Type: application/json');
        echo json_encode(['project_code' => $project_code]);
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
