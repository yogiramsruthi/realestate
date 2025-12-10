<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Real_estate_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== PROJECTS ====================
    
    public function get_projects($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 're_projects')->row();
        }
        return $this->db->get(db_prefix() . 're_projects')->result_array();
    }

    public function add_project($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert(db_prefix() . 're_projects', $data)) {
            $insert_id = $this->db->insert_id();
            log_activity('New Real Estate Project Created [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_project($data, $id)
    {
        $this->db->where('id', $id);
        $update = $this->db->update(db_prefix() . 're_projects', $data);
        
        if ($update) {
            log_activity('Real Estate Project Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete_project($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 're_projects');
        
        if ($this->db->affected_rows() > 0) {
            log_activity('Real Estate Project Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    // ==================== BLOCKS ====================
    
    public function get_blocks($id = '', $project_id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 're_blocks')->row();
        }
        
        if (is_numeric($project_id)) {
            $this->db->where('project_id', $project_id);
        }
        
        return $this->db->get(db_prefix() . 're_blocks')->result_array();
    }

    public function add_block($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert(db_prefix() . 're_blocks', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function update_block($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 're_blocks', $data);
    }

    public function delete_block($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 're_blocks');
    }

    // ==================== PLOTS ====================
    
    public function get_plots($id = '', $project_id = '', $status = '')
    {
        if (is_numeric($id)) {
            $this->db->select('p.*, pr.name as project_name, b.name as block_name');
            $this->db->from(db_prefix() . 're_plots p');
            $this->db->join(db_prefix() . 're_projects pr', 'pr.id = p.project_id');
            $this->db->join(db_prefix() . 're_blocks b', 'b.id = p.block_id', 'left');
            $this->db->where('p.id', $id);
            return $this->db->get()->row();
        }
        
        $this->db->select('p.*, pr.name as project_name, b.name as block_name');
        $this->db->from(db_prefix() . 're_plots p');
        $this->db->join(db_prefix() . 're_projects pr', 'pr.id = p.project_id');
        $this->db->join(db_prefix() . 're_blocks b', 'b.id = p.block_id', 'left');
        
        if (is_numeric($project_id)) {
            $this->db->where('p.project_id', $project_id);
        }
        
        if (!empty($status)) {
            $this->db->where('p.status', $status);
        }
        
        return $this->db->get()->result_array();
    }

    public function add_plot($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // Calculate total price if not provided
        if (!isset($data['total_price']) || empty($data['total_price'])) {
            $data['total_price'] = $data['area'] * $data['rate_per_unit'];
        }
        
        if ($this->db->insert(db_prefix() . 're_plots', $data)) {
            $insert_id = $this->db->insert_id();
            
            // Update project total plots count
            $this->update_project_plot_count($data['project_id']);
            
            log_activity('New Plot Created [ID: ' . $insert_id . ', Number: ' . $data['plot_number'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_plot($data, $id)
    {
        // Recalculate total price if area or rate changed
        if (isset($data['area']) || isset($data['rate_per_unit'])) {
            $plot = $this->get_plots($id);
            $area = isset($data['area']) ? $data['area'] : $plot->area;
            $rate = isset($data['rate_per_unit']) ? $data['rate_per_unit'] : $plot->rate_per_unit;
            $data['total_price'] = $area * $rate;
        }
        
        $this->db->where('id', $id);
        $update = $this->db->update(db_prefix() . 're_plots', $data);
        
        if ($update) {
            log_activity('Plot Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete_plot($id)
    {
        $plot = $this->get_plots($id);
        
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 're_plots');
        
        if ($this->db->affected_rows() > 0) {
            // Update project total plots count
            $this->update_project_plot_count($plot->project_id);
            
            log_activity('Plot Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function update_plot_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 're_plots', ['status' => $status]);
    }

    private function update_project_plot_count($project_id)
    {
        $this->db->where('project_id', $project_id);
        $count = $this->db->count_all_results(db_prefix() . 're_plots');
        
        $this->db->where('id', $project_id);
        $this->db->update(db_prefix() . 're_projects', ['total_plots' => $count]);
    }

    public function bulk_import_plots($plots_data)
    {
        $success = 0;
        $errors = [];
        
        foreach ($plots_data as $data) {
            if ($this->add_plot($data)) {
                $success++;
            } else {
                $errors[] = 'Failed to import plot: ' . $data['plot_number'];
            }
        }
        
        return ['success' => $success, 'errors' => $errors];
    }

    // ==================== BOOKINGS ====================
    
    public function get_bookings($id = '', $customer_id = '', $status = '')
    {
        if (is_numeric($id)) {
            $this->db->select('b.*, p.plot_number, p.area, pr.name as project_name, c.company as customer_name, pp.name as plan_name');
            $this->db->from(db_prefix() . 're_bookings b');
            $this->db->join(db_prefix() . 're_plots p', 'p.id = b.plot_id');
            $this->db->join(db_prefix() . 're_projects pr', 'pr.id = p.project_id');
            $this->db->join(db_prefix() . 'clients c', 'c.userid = b.customer_id');
            $this->db->join(db_prefix() . 're_payment_plans pp', 'pp.id = b.payment_plan_id', 'left');
            $this->db->where('b.id', $id);
            return $this->db->get()->row();
        }
        
        $this->db->select('b.*, p.plot_number, p.area, pr.name as project_name, c.company as customer_name, pp.name as plan_name');
        $this->db->from(db_prefix() . 're_bookings b');
        $this->db->join(db_prefix() . 're_plots p', 'p.id = b.plot_id');
        $this->db->join(db_prefix() . 're_projects pr', 'pr.id = p.project_id');
        $this->db->join(db_prefix() . 'clients c', 'c.userid = b.customer_id');
        $this->db->join(db_prefix() . 're_payment_plans pp', 'pp.id = b.payment_plan_id', 'left');
        
        if (is_numeric($customer_id)) {
            $this->db->where('b.customer_id', $customer_id);
        }
        
        if (!empty($status)) {
            $this->db->where('b.status', $status);
        }
        
        return $this->db->get()->result_array();
    }

    public function add_booking($data)
    {
        // Generate booking code if not provided
        if (!isset($data['booking_code']) || empty($data['booking_code'])) {
            $data['booking_code'] = $this->generate_booking_code();
        }
        
        $data['created_by'] = get_staff_user_id();
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // Calculate final amount
        $data['final_amount'] = $data['total_amount'] - (isset($data['discount']) ? $data['discount'] : 0);
        
        if ($this->db->insert(db_prefix() . 're_bookings', $data)) {
            $insert_id = $this->db->insert_id();
            
            // Update plot status to booked
            $this->update_plot_status($data['plot_id'], 'booked');
            
            // Generate installments if payment plan is selected
            if (isset($data['payment_plan_id']) && !empty($data['payment_plan_id'])) {
                $this->generate_installments($insert_id, $data['payment_plan_id'], $data['final_amount'], $data['booking_date']);
            }
            
            log_activity('New Booking Created [ID: ' . $insert_id . ', Code: ' . $data['booking_code'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_booking($data, $id)
    {
        // Recalculate final amount if total or discount changed
        if (isset($data['total_amount']) || isset($data['discount'])) {
            $booking = $this->get_bookings($id);
            $total = isset($data['total_amount']) ? $data['total_amount'] : $booking->total_amount;
            $discount = isset($data['discount']) ? $data['discount'] : $booking->discount;
            $data['final_amount'] = $total - $discount;
        }
        
        $this->db->where('id', $id);
        $update = $this->db->update(db_prefix() . 're_bookings', $data);
        
        if ($update) {
            log_activity('Booking Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function cancel_booking($id, $reason)
    {
        $booking = $this->get_bookings($id);
        
        $data = [
            'status' => 'cancelled',
            'cancellation_date' => date('Y-m-d'),
            'cancellation_reason' => $reason
        ];
        
        $this->db->where('id', $id);
        $update = $this->db->update(db_prefix() . 're_bookings', $data);
        
        if ($update) {
            // Update plot status back to available
            $this->update_plot_status($booking->plot_id, 'available');
            
            log_activity('Booking Cancelled [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function convert_to_sale($id)
    {
        $booking = $this->get_bookings($id);
        
        $this->db->where('id', $id);
        $update = $this->db->update(db_prefix() . 're_bookings', ['status' => 'converted_to_sale']);
        
        if ($update) {
            // Update plot status to sold
            $this->update_plot_status($booking->plot_id, 'sold');
            
            log_activity('Booking Converted to Sale [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    private function generate_booking_code()
    {
        $prefix = 'BK';
        $year = date('Y');
        
        // Get last booking code
        $this->db->select('booking_code');
        $this->db->from(db_prefix() . 're_bookings');
        $this->db->like('booking_code', $prefix . $year, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_number = (int) substr($result->booking_code, -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . $year . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    // ==================== PAYMENT PLANS ====================
    
    public function get_payment_plans($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 're_payment_plans')->row();
        }
        
        $this->db->where('is_active', 1);
        return $this->db->get(db_prefix() . 're_payment_plans')->result_array();
    }

    public function add_payment_plan($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert(db_prefix() . 're_payment_plans', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function update_payment_plan($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 're_payment_plans', $data);
    }

    // ==================== INSTALLMENTS ====================
    
    public function get_installments($booking_id = '', $id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 're_booking_installments')->row();
        }
        
        if (is_numeric($booking_id)) {
            $this->db->where('booking_id', $booking_id);
        }
        
        $this->db->order_by('installment_number', 'ASC');
        return $this->db->get(db_prefix() . 're_booking_installments')->result_array();
    }

    private function generate_installments($booking_id, $payment_plan_id, $total_amount, $booking_date)
    {
        $plan = $this->get_payment_plans($payment_plan_id);
        
        if (!$plan) {
            return false;
        }
        
        $down_payment = ($total_amount * $plan->down_payment_percentage) / 100;
        $remaining_amount = $total_amount - $down_payment;
        $installment_amount = $remaining_amount / $plan->number_of_installments;
        
        // Add down payment installment
        $installment_data = [
            'booking_id' => $booking_id,
            'installment_number' => 0,
            'due_date' => $booking_date,
            'amount' => $down_payment,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert(db_prefix() . 're_booking_installments', $installment_data);
        
        // Add remaining installments
        $start_date = new DateTime($booking_date);
        
        for ($i = 1; $i <= $plan->number_of_installments; $i++) {
            // Calculate due date based on frequency
            if ($plan->installment_frequency == 'monthly') {
                $start_date->modify('+1 month');
            } elseif ($plan->installment_frequency == 'quarterly') {
                $start_date->modify('+3 months');
            } elseif ($plan->installment_frequency == 'yearly') {
                $start_date->modify('+1 year');
            }
            
            $installment_data = [
                'booking_id' => $booking_id,
                'installment_number' => $i,
                'due_date' => $start_date->format('Y-m-d'),
                'amount' => $installment_amount,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert(db_prefix() . 're_booking_installments', $installment_data);
        }
        
        return true;
    }

    public function mark_installment_paid($id, $paid_amount, $payment_date, $invoice_id = null)
    {
        $data = [
            'paid_amount' => $paid_amount,
            'payment_date' => $payment_date,
            'status' => 'paid'
        ];
        
        if ($invoice_id) {
            $data['invoice_id'] = $invoice_id;
        }
        
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 're_booking_installments', $data);
    }

    public function get_overdue_installments()
    {
        $this->db->where('status', 'pending');
        $this->db->where('due_date <', date('Y-m-d'));
        return $this->db->get(db_prefix() . 're_booking_installments')->result_array();
    }

    // ==================== TEAM ASSIGNMENTS ====================
    
    public function get_team_assignments($project_id = '')
    {
        $this->db->select('ta.*, s.firstname, s.lastname, s.email');
        $this->db->from(db_prefix() . 're_team_assignments ta');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = ta.staff_id');
        
        if (is_numeric($project_id)) {
            $this->db->where('ta.project_id', $project_id);
        }
        
        $this->db->where('ta.is_active', 1);
        return $this->db->get()->result_array();
    }

    public function assign_team_member($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert(db_prefix() . 're_team_assignments', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function remove_team_member($id)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 're_team_assignments', ['is_active' => 0]);
    }

    // ==================== COMMUNICATIONS ====================
    
    public function get_communications($related_to = '', $related_id = '')
    {
        $this->db->select('c.*, s.firstname as staff_firstname, s.lastname as staff_lastname');
        $this->db->from(db_prefix() . 're_communications c');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = c.created_by', 'left');
        
        if (!empty($related_to) && is_numeric($related_id)) {
            $this->db->where('c.related_to', $related_to);
            $this->db->where('c.related_id', $related_id);
        }
        
        $this->db->order_by('c.communication_date', 'DESC');
        return $this->db->get()->result_array();
    }

    public function add_communication($data)
    {
        $data['created_by'] = get_staff_user_id();
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert(db_prefix() . 're_communications', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    // ==================== REPORTS & STATISTICS ====================
    
    public function get_project_statistics($project_id)
    {
        $stats = [];
        
        // Total plots
        $this->db->where('project_id', $project_id);
        $stats['total_plots'] = $this->db->count_all_results(db_prefix() . 're_plots');
        
        // Available plots
        $this->db->where('project_id', $project_id);
        $this->db->where('status', 'available');
        $stats['available_plots'] = $this->db->count_all_results(db_prefix() . 're_plots');
        
        // Booked plots
        $this->db->where('project_id', $project_id);
        $this->db->where('status', 'booked');
        $stats['booked_plots'] = $this->db->count_all_results(db_prefix() . 're_plots');
        
        // Sold plots
        $this->db->where('project_id', $project_id);
        $this->db->where('status', 'sold');
        $stats['sold_plots'] = $this->db->count_all_results(db_prefix() . 're_plots');
        
        // Total revenue
        $this->db->select_sum('b.final_amount');
        $this->db->from(db_prefix() . 're_bookings b');
        $this->db->join(db_prefix() . 're_plots p', 'p.id = b.plot_id');
        $this->db->where('p.project_id', $project_id);
        $this->db->where_in('b.status', ['confirmed', 'converted_to_sale']);
        $result = $this->db->get()->row();
        $stats['total_revenue'] = $result->final_amount ?? 0;
        
        // New specification fields
        $project = $this->get_projects($project_id);
        if ($project) {
            $stats['total_acres'] = $project->total_acres;
            $stats['total_sqft'] = $project->total_sqft;
            $stats['approved_sqft'] = $project->approved_sqft;
            $stats['total_owners'] = $project->total_owners;
            $stats['emi_enabled'] = $project->emi_enabled;
            $stats['commission_type'] = $project->team_commission_type;
        }
        
        return $stats;
    }

    public function get_dashboard_statistics()
    {
        $stats = [];
        
        // Total projects
        $stats['total_projects'] = $this->db->count_all_results(db_prefix() . 're_projects');
        
        // Active projects
        $this->db->where('status', 'active');
        $stats['active_projects'] = $this->db->count_all_results(db_prefix() . 're_projects');
        
        // Total plots
        $stats['total_plots'] = $this->db->count_all_results(db_prefix() . 're_plots');
        
        // Available plots
        $this->db->where('status', 'available');
        $stats['available_plots'] = $this->db->count_all_results(db_prefix() . 're_plots');
        
        // Total bookings
        $stats['total_bookings'] = $this->db->count_all_results(db_prefix() . 're_bookings');
        
        // Confirmed bookings
        $this->db->where('status', 'confirmed');
        $stats['confirmed_bookings'] = $this->db->count_all_results(db_prefix() . 're_bookings');
        
        // Overdue payments
        $this->db->where('status', 'pending');
        $this->db->where('due_date <', date('Y-m-d'));
        $stats['overdue_payments'] = $this->db->count_all_results(db_prefix() . 're_booking_installments');
        
        return $stats;
    }

    // ==================== ACCOUNTING HELPERS ====================

    public function get_project_accounting_ledger($project_id, $ledger_key, $option_key = '', $fallback = '')
    {
        if (empty($ledger_key)) {
            return $fallback ?: '';
        }

        $ledger_table = db_prefix() . 'real_project_ledgers';
        if (!empty($project_id) && $this->db->table_exists($ledger_table)) {
            $row = $this->db
                ->where('project_id', $project_id)
                ->where('ledger_key', $ledger_key)
                ->get($ledger_table)
                ->row();
            if ($row && !empty($row->ledger_name)) {
                return $row->ledger_name;
            }
        }

        if ($option_key) {
            $option = get_option('real_estat_' . $option_key);
            if (!empty($option)) {
                return $option;
            }
        }

        if (!empty($fallback)) {
            return $fallback;
        }

        return ucfirst(str_replace('_', ' ', $ledger_key));
    }

    public function mark_accounting_exported($table, $column, $ids)
    {
        if (empty($table) || empty($column) || empty($ids)) {
            return false;
        }

        if (!$this->db->table_exists($table) || !$this->db->field_exists($column, $table)) {
            return false;
        }

        $this->db->where_in('id', (array) $ids);
        $this->db->update($table, [$column => 1]);
        return $this->db->affected_rows() > 0;
    }

    public function build_tally_envelope($vouchers_xml)
    {
        return '<ENVELOPE>
            <HEADER>
                <TALLYREQUEST>Import Data</TALLYREQUEST>
            </HEADER>
            <BODY>
                <IMPORTDATA>
                    <REQUESTDESC>
                        <REPORTNAME>Vouchers</REPORTNAME>
                    </REQUESTDESC>
                    <REQUESTDATA>' . $vouchers_xml . '</REQUESTDATA>
                </IMPORTDATA>
            </BODY>
        </ENVELOPE>';
    }

    public function get_tally_http_credentials()
    {
        return [
            'endpoint' => get_option('real_estat_tally_http_endpoint'),
            'username' => get_option('real_estat_tally_http_username'),
            'password' => get_option('real_estat_tally_http_password'),
            'company'  => get_option('real_estat_tally_http_company'),
            'auto'     => (bool) get_option('real_estat_tally_http_auto_push'),
        ];
    }

    // ==================== NEW SPECIFICATION METHODS ====================
    
    /**
     * Calculate SqFt from Acres
     * 1 acre = 43,560 SqFt
     */
    public function calculate_sqft_from_acres($acres)
    {
        if (!is_numeric($acres) || $acres <= 0) {
            return 0;
        }
        return $acres * 43560;
    }

    /**
     * Calculate commission based on type and amount
     */
    public function calculate_commission($amount, $commission_type, $commission_value, $slab_json = null)
    {
        if (!is_numeric($amount) || $amount <= 0) {
            return 0;
        }

        if ($commission_type === 'percentage' && is_numeric($commission_value)) {
            return ($amount * $commission_value) / 100;
        }

        if ($commission_type === 'slab' && !empty($slab_json)) {
            $slabs = json_decode($slab_json, true);
            if (is_array($slabs)) {
                foreach ($slabs as $slab) {
                    $from = isset($slab['from']) ? (float)$slab['from'] : 0;
                    $to = isset($slab['to']) ? (float)$slab['to'] : PHP_INT_MAX;
                    $percent = isset($slab['percent']) ? (float)$slab['percent'] : 0;

                    if ($amount >= $from && $amount <= $to) {
                        return ($amount * $percent) / 100;
                    }
                }
            }
        }

        return 0;
    }

    /**
     * Calculate EMI amount
     * Formula for flat interest: EMI = (Principal + Interest) / Number of Months
     * Formula for reducing interest: EMI = (Principal × Rate × (1 + Rate)^n) / ((1 + Rate)^n - 1)
     */
    public function calculate_emi(
        $principal,
        $annual_rate,
        $tenor_months,
        $interest_type = 'flat'
    ) {
        if (!is_numeric($principal) || $principal <= 0 || !is_numeric($tenor_months) || $tenor_months <= 0) {
            return ['emi' => 0, 'total_interest' => 0, 'total_amount' => $principal];
        }

        if ($interest_type === 'none') {
            $monthly_emi = $principal / $tenor_months;
            return [
                'emi' => round($monthly_emi, 2),
                'total_interest' => 0,
                'total_amount' => $principal
            ];
        }

        $monthly_rate = $annual_rate / 12 / 100;

        if ($interest_type === 'flat') {
            $total_interest = ($principal * $annual_rate * $tenor_months) / (100 * 12);
            $total_amount = $principal + $total_interest;
            $monthly_emi = $total_amount / $tenor_months;
            
            return [
                'emi' => round($monthly_emi, 2),
                'total_interest' => round($total_interest, 2),
                'total_amount' => round($total_amount, 2)
            ];
        }

        if ($interest_type === 'reducing') {
            // Reducing balance EMI formula
            $numerator = $principal * $monthly_rate * pow((1 + $monthly_rate), $tenor_months);
            $denominator = pow((1 + $monthly_rate), $tenor_months) - 1;
            $monthly_emi = $numerator / $denominator;
            $total_amount = $monthly_emi * $tenor_months;
            $total_interest = $total_amount - $principal;

            return [
                'emi' => round($monthly_emi, 2),
                'total_interest' => round($total_interest, 2),
                'total_amount' => round($total_amount, 2)
            ];
        }

        return ['emi' => 0, 'total_interest' => 0, 'total_amount' => $principal];
    }

    /**
     * Calculate late payment penalty
     */
    public function calculate_penalty($amount, $penalty_rate, $days_overdue)
    {
        if (!is_numeric($amount) || $amount <= 0 || !is_numeric($penalty_rate) || !is_numeric($days_overdue) || $days_overdue <= 0) {
            return 0;
        }

        // Penalty is daily compound
        $daily_rate = $penalty_rate / 365 / 100;
        $penalty = $amount * $daily_rate * $days_overdue;

        return round($penalty, 2);
    }

    /**
     * Get project pricing summary
     */
    public function get_project_pricing_summary($project_id)
    {
        $project = $this->get_projects($project_id);

        if (!$project) {
            return null;
        }

        $summary = [
            'total_acres' => $project->total_acres,
            'total_sqft' => $project->total_sqft,
            'approved_sqft' => $project->approved_sqft,
            'owners_price_per_sqft' => $project->owners_price_per_sqft,
            'min_selling_price_per_sqft' => $project->min_selling_price_per_sqft,
            'max_selling_price_per_sqft' => $project->max_selling_price_per_sqft,
            'total_owners' => $project->total_owners,
            'has_power_of_attorney' => $project->has_power_of_attorney,
        ];

        // Calculate total values
        if ($project->total_sqft && $project->owners_price_per_sqft) {
            $summary['total_owner_cost'] = $project->total_sqft * $project->owners_price_per_sqft;
        }

        if ($project->total_sqft && $project->min_selling_price_per_sqft) {
            $summary['total_min_selling_price'] = $project->total_sqft * $project->min_selling_price_per_sqft;
        }

        if ($project->total_sqft && $project->max_selling_price_per_sqft) {
            $summary['total_max_selling_price'] = $project->total_sqft * $project->max_selling_price_per_sqft;
        }

        return $summary;
    }

    /**
     * Validate commission slab JSON
     */
    public function validate_commission_slab($slab_json)
    {
        if (empty($slab_json)) {
            return true;
        }

        $slabs = json_decode($slab_json, true);

        if (!is_array($slabs)) {
            return false;
        }

        foreach ($slabs as $slab) {
            if (!isset($slab['from']) || !isset($slab['to']) || !isset($slab['percent'])) {
                return false;
            }
            if (!is_numeric($slab['from']) || !is_numeric($slab['to']) || !is_numeric($slab['percent'])) {
                return false;
            }
            if ($slab['from'] > $slab['to']) {
                return false;
            }
        }

        return true;
    }
}
