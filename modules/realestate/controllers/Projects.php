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

        $data['title'] = $title;
        $this->load->view('projects/project', $data);
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
