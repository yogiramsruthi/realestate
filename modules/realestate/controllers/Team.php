<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Team extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/team_model');
        $this->load->model('realestate/projects_model');
    }

    /**
     * List all team assignments
     */
    public function index()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_team');
        $data['team_assignments'] = $this->team_model->get();
        $this->load->view('team/manage', $data);
    }

    /**
     * Add/Edit team assignment
     * @param  mixed $id
     */
    public function assignment($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if ($id == '') {
                if (!has_permission('realestate', '', 'create')) {
                    access_denied('realestate');
                }
                $id = $this->team_model->add($data);
                if ($id) {
                    set_alert('success', _l('realestate_team_member_added'));
                    redirect(admin_url('realestate/team'));
                }
            } else {
                if (!has_permission('realestate', '', 'edit')) {
                    access_denied('realestate');
                }
                $success = $this->team_model->update($data, $id);
                if ($success) {
                    set_alert('success', _l('realestate_team_member_updated'));
                    redirect(admin_url('realestate/team'));
                }
            }
        }

        $data['projects'] = $this->projects_model->get();
        $this->load->model('staff_model');
        $data['staff'] = $this->staff_model->get();
        
        if ($id == '') {
            $title = _l('realestate_add_team_member');
        } else {
            $data['assignment'] = $this->team_model->get($id);
            $title = _l('realestate_edit_team_member');
        }

        $data['title'] = $title;
        $this->load->view('team/assignment', $data);
    }

    /**
     * Delete team assignment
     * @param  mixed $id
     */
    public function delete($id)
    {
        if (!has_permission('realestate', '', 'delete')) {
            access_denied('realestate');
        }

        if (!$id) {
            redirect(admin_url('realestate/team'));
        }

        $response = $this->team_model->delete($id);
        if ($response) {
            set_alert('success', _l('realestate_team_member_deleted'));
        }
        redirect(admin_url('realestate/team'));
    }
}
