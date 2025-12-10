<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plots extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/plots_model');
        $this->load->model('realestate/projects_model');
    }

    /**
     * List all plots
     */
    public function index()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_plots');
        $data['plots'] = $this->plots_model->get();
        $data['projects'] = $this->projects_model->get();
        $this->load->view('plots/manage', $data);
    }

    /**
     * Add/Edit plot
     * @param  mixed $id
     */
    public function plot($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if ($id == '') {
                if (!has_permission('realestate', '', 'create')) {
                    access_denied('realestate');
                }
                $id = $this->plots_model->add($data);
                if ($id) {
                    set_alert('success', _l('realestate_plot_added'));
                    redirect(admin_url('realestate/plots'));
                }
            } else {
                if (!has_permission('realestate', '', 'edit')) {
                    access_denied('realestate');
                }
                $success = $this->plots_model->update($data, $id);
                if ($success) {
                    set_alert('success', _l('realestate_plot_updated'));
                    redirect(admin_url('realestate/plots'));
                }
            }
        }

        $data['projects'] = $this->projects_model->get();
        
        if ($id == '') {
            $title = _l('realestate_add_plot');
        } else {
            $data['plot'] = $this->plots_model->get($id);
            $title = _l('realestate_edit_plot');
        }

        $data['title'] = $title;
        $this->load->view('plots/plot', $data);
    }

    /**
     * Delete plot
     * @param  mixed $id
     */
    public function delete($id)
    {
        if (!has_permission('realestate', '', 'delete')) {
            access_denied('realestate');
        }

        if (!$id) {
            redirect(admin_url('realestate/plots'));
        }

        $response = $this->plots_model->delete($id);
        if ($response) {
            set_alert('success', _l('realestate_plot_deleted'));
        } else {
            set_alert('warning', _l('realestate_plot_delete_error'));
        }
        redirect(admin_url('realestate/plots'));
    }
}
