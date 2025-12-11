<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accounts extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/transactions_model');
        $this->load->model('realestate/bookings_model');
    }

    /**
     * List all transactions
     */
    public function index()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_accounts');
        $data['transactions'] = $this->transactions_model->get();
        $this->load->view('accounts/manage', $data);
    }

    /**
     * Add transaction
     * @param  mixed $booking_id
     */
    public function transaction($booking_id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if (!has_permission('realestate', '', 'create')) {
                access_denied('realestate');
            }
            
            $id = $this->transactions_model->add($data);
            if ($id) {
                set_alert('success', _l('realestate_transaction_added'));
                redirect(admin_url('realestate/accounts'));
            }
        }

        $data['bookings'] = $this->bookings_model->get();
        
        if ($booking_id != '') {
            $data['selected_booking'] = $booking_id;
        }

        $data['title'] = _l('realestate_add_transaction');
        $this->load->view('accounts/transaction', $data);
    }

    /**
     * Delete transaction
     * @param  mixed $id
     */
    public function delete($id)
    {
        if (!has_permission('realestate', '', 'delete')) {
            access_denied('realestate');
        }

        if (!$id) {
            redirect(admin_url('realestate/accounts'));
        }

        $response = $this->transactions_model->delete($id);
        if ($response) {
            set_alert('success', _l('realestate_transaction_deleted'));
        }
        redirect(admin_url('realestate/accounts'));
    }
}
