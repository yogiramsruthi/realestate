<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bookings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/bookings_model');
        $this->load->model('realestate/plots_model');
        $this->load->model('realestate/projects_model');
    }

    /**
     * List all bookings
     */
    public function index()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_bookings');
        $data['bookings'] = $this->bookings_model->get();
        $this->load->view('bookings/manage', $data);
    }

    /**
     * Add/Edit booking
     * @param  mixed $id
     */
    public function booking($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if ($id == '') {
                if (!has_permission('realestate', '', 'create')) {
                    access_denied('realestate');
                }
                $id = $this->bookings_model->add($data);
                if ($id) {
                    set_alert('success', _l('realestate_booking_added'));
                    redirect(admin_url('realestate/bookings'));
                }
            } else {
                if (!has_permission('realestate', '', 'edit')) {
                    access_denied('realestate');
                }
                $success = $this->bookings_model->update($data, $id);
                if ($success) {
                    set_alert('success', _l('realestate_booking_updated'));
                    redirect(admin_url('realestate/bookings'));
                }
            }
        }

        // Get available plots
        $data['plots'] = $this->plots_model->get('', ['status' => 'available']);
        
        // Get all clients
        $this->load->model('clients_model');
        $data['clients'] = $this->clients_model->get();
        
        // Get staff for assignment
        $this->load->model('staff_model');
        $data['staff'] = $this->staff_model->get();
        
        if ($id == '') {
            $title = _l('realestate_add_booking');
        } else {
            $data['booking'] = $this->bookings_model->get($id);
            // Also include the currently booked plot
            if (!empty($data['booking']->plot_id)) {
                $current_plot = $this->plots_model->get($data['booking']->plot_id);
                if ($current_plot) {
                    $data['plots'][] = (array)$current_plot;
                }
            }
            $title = _l('realestate_edit_booking');
        }

        $data['title'] = $title;
        $this->load->view('bookings/booking', $data);
    }

    /**
     * Delete booking
     * @param  mixed $id
     */
    public function delete($id)
    {
        if (!has_permission('realestate', '', 'delete')) {
            access_denied('realestate');
        }

        if (!$id) {
            redirect(admin_url('realestate/bookings'));
        }

        $response = $this->bookings_model->delete($id);
        if ($response) {
            set_alert('success', _l('realestate_booking_deleted'));
        }
        redirect(admin_url('realestate/bookings'));
    }
}
