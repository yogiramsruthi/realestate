<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Real_estat extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('real_estate_model');
        $this->load->model('clients_model');
        $this->load->model('staff_model');
    }

    // ==================== EMI CALCULATOR ====================
    
    public function calculate_emi_schedule()
    {
        header('Content-Type: application/json');
        
        $principal = $this->input->post('principal');
        $project_id = $this->input->post('project_id');
        $tenor = $this->input->post('tenor') ?: null;
        
        if (!$principal || $principal <= 0 || !$project_id) {
            echo json_encode(['error' => 'Invalid parameters']);
            return;
        }
        
        $project = $this->real_estate_model->get_projects($project_id);
        if (!$project) {
            echo json_encode(['error' => 'Project not found']);
            return;
        }
        
        if (!$project->emi_enabled) {
            echo json_encode(['error' => 'EMI not enabled for this project']);
            return;
        }
        
        $tenor = $tenor ?: ($project->emi_default_tenor_months ?: 12);
        
        // Calculate EMI
        $emiResult = $this->real_estate_model->calculate_emi(
            $principal,
            $project->emi_interest_rate_annual ?: 0,
            $tenor,
            $project->emi_interest_type ?: 'none'
        );
        
        // Generate schedule
        $schedule = $this->generate_emi_schedule(
            $principal,
            $project->emi_interest_rate_annual ?: 0,
            $tenor,
            $project->emi_interest_type ?: 'none',
            $project->emi_grace_days ?: 0
        );
        
        echo json_encode([
            'success' => true,
            'emi' => $emiResult['emi'],
            'total_interest' => $emiResult['total_interest'],
            'total_amount' => $emiResult['total_amount'],
            'tenor_months' => $tenor,
            'grace_days' => $project->emi_grace_days ?: 0,
            'schedule' => $schedule
        ]);
    }
    
    private function generate_emi_schedule($principal, $rate, $tenor, $interest_type, $grace_days = 0)
    {
        $schedule = [];
        $monthlyRate = $rate / 12 / 100;
        $emiAmount = 0;
        $balance = $principal;
        $startDate = date('Y-m-d', strtotime('+' . $grace_days . ' days'));
        
        // Calculate EMI based on interest type
        if ($interest_type === 'flat') {
            $totalInterest = ($principal * $rate * $tenor) / (100 * 12);
            $emiAmount = ($principal + $totalInterest) / $tenor;
        } elseif ($interest_type === 'reducing') {
            $pow = pow(1 + $monthlyRate, $tenor);
            $emiAmount = ($principal * $monthlyRate * $pow) / ($pow - 1);
        } else {
            $emiAmount = $principal / $tenor;
        }
        
        for ($i = 1; $i <= $tenor; $i++) {
            $dueDate = date('Y-m-d', strtotime($startDate . ' +' . ($i - 1) . ' months'));
            $interestPayable = 0;
            $principalPayable = 0;
            
            if ($interest_type === 'reducing' && $balance > 0) {
                $interestPayable = $balance * $monthlyRate;
                $principalPayable = $emiAmount - $interestPayable;
            } elseif ($interest_type === 'flat') {
                $monthlyInterest = ($principal * $rate) / (100 * 12);
                $interestPayable = $monthlyInterest;
                $principalPayable = $emiAmount - $interestPayable;
            } else {
                $principalPayable = $emiAmount;
            }
            
            $balance -= $principalPayable;
            
            $schedule[] = [
                'installment' => $i,
                'due_date' => $dueDate,
                'principal' => round($principalPayable, 2),
                'interest' => round($interestPayable, 2),
                'total_payment' => round($emiAmount, 2),
                'balance' => max(0, round($balance, 2))
            ];
        }
        
        return $schedule;
    }

    // ==================== DASHBOARD ====================
    
    public function index()
    {
        if (!has_permission('real_estate_projects', '', 'view')) {
            access_denied('Real Estate');
        }
        
        $data['title'] = _l('real_estate');
        $data['stats'] = $this->real_estate_model->get_dashboard_statistics();
        $data['recent_bookings'] = $this->real_estate_model->get_bookings();
        $data['overdue_installments'] = $this->real_estate_model->get_overdue_installments();
        
        $this->load->view('dashboard', $data);
    }

    // ==================== PROJECTS ====================
    
    public function projects()
    {
        if (!has_permission('real_estate_projects', '', 'view')) {
            access_denied('Real Estate Projects');
        }
        
        $data['title'] = _l('real_estate_projects');
        $data['projects'] = $this->real_estate_model->get_projects();
        
        $this->load->view('projects/manage', $data);
    }

    public function project($id = '')
    {
        if (!has_permission('real_estate_projects', '', 'view')) {
            access_denied('Real Estate Projects');
        }
        
        if ($this->input->post()) {
            // Validate all form fields
            if (!$this->validate_project_form()) {
                // Return to form with validation errors
                if (is_numeric($id)) {
                    redirect(admin_url('real_estat/project/' . $id));
                } else {
                    redirect(admin_url('real_estat/project'));
                }
            }
            
            if ($id == '') {
                // Add new project
                if (!has_permission('real_estate_projects', '', 'create')) {
                    access_denied('Real Estate Projects');
                }
                
                $payload    = $this->prepare_project_payload();
                $project_id = $this->real_estate_model->add_project($payload);
                if ($project_id) {
                    set_alert('success', _l('project_added_successfully'));
                    redirect(admin_url('real_estat/project/' . $project_id));
                } else {
                    set_alert('danger', _l('project_code_exists'));
                }
            } else {
                // Update existing project
                if (!has_permission('real_estate_projects', '', 'edit')) {
                    access_denied('Real Estate Projects');
                }
                
                $existing = $this->real_estate_model->get_projects($id);
                $payload = $this->prepare_project_payload($existing);
                $success = $this->real_estate_model->update_project($payload, $id);
                if ($success) {
                    set_alert('success', _l('project_updated_successfully'));
                } else {
                    set_alert('danger', _l('something_went_wrong'));
                }
            }
        }
        
        if (is_numeric($id)) {
            $data['project'] = $this->real_estate_model->get_projects($id);
            if (!$data['project']) {
                show_404();
            }
            $data['stats'] = $this->real_estate_model->get_project_statistics($id);
            $data['blocks'] = $this->real_estate_model->get_blocks('', $id);
            $data['plots'] = $this->real_estate_model->get_plots('', $id);
            $data['team'] = $this->real_estate_model->get_team_assignments($id);
        }
        
        $data['title'] = $id ? _l('edit_project') : _l('new_project');
        $data['staff'] = $this->staff_model->get();
        
        $this->load->view('projects/project', $data);
    }

    private function prepare_project_payload($existing = null)
    {
        $post = $this->input->post();
        $data = [
            'name'                             => trim($post['name'] ?? ''),
            'code'                             => trim($post['code'] ?? ''),
            'status'                           => $post['status'] ?: 'planning',
            'project_manager_id'               => $post['project_manager_id'] ?: 1,
            'total_plots'                      => (int)($post['total_plots'] ?: 0),
            'description'                      => trim($post['description'] ?? ''),
            'location'                         => trim($post['location'] ?? ''),
            'start_date'                       => !empty($post['start_date']) ? $post['start_date'] : null,
            'completion_date'                  => !empty($post['completion_date']) ? $post['completion_date'] : null,
            
            // Location Details
            'district'                         => trim($post['district'] ?? ''),
            'area'                             => trim($post['area'] ?? ''),
            'village'                          => trim($post['village'] ?? ''),
            'location_map'                     => trim($post['location_map'] ?? ''),
            'nearby'                           => trim($post['nearby'] ?? ''),
            'total_owners'                     => !empty($post['total_owners']) ? (int)$post['total_owners'] : null,
            
            // Property Size
            'total_acres'                      => !empty($post['total_acres']) ? (float)$post['total_acres'] : null,
            'total_sqft'                       => !empty($post['total_sqft']) ? (float)$post['total_sqft'] : null,
            'approved_sqft'                    => !empty($post['approved_sqft']) ? (float)$post['approved_sqft'] : null,
            
            // Pricing
            'owners_price_per_sqft'            => !empty($post['owners_price_per_sqft']) ? (float)$post['owners_price_per_sqft'] : null,
            'min_selling_price_per_sqft'       => !empty($post['min_selling_price_per_sqft']) ? (float)$post['min_selling_price_per_sqft'] : null,
            'max_selling_price_per_sqft'       => !empty($post['max_selling_price_per_sqft']) ? (float)$post['max_selling_price_per_sqft'] : null,
            
            // Commission
            'team_commission_type'             => $post['team_commission_type'] ?: 'percentage',
            'team_commission_value'            => !empty($post['team_commission_value']) ? (float)$post['team_commission_value'] : null,
            'team_commission_slab_json'        => $this->normalize_json($post['team_commission_slab_json'] ?? ''),
            
            // Approvals
            'approval_types'                   => trim($post['approval_types'] ?? ''),
            'approval_details'                 => trim($post['approval_details'] ?? ''),
            
            // Power of Attorney
            'has_power_of_attorney'            => $this->input->post('has_power_of_attorney') ? 1 : 0,
            'poa_status'                       => $post['poa_status'] ?: 'none',
            'poa_grantor_name'                 => trim($post['poa_grantor_name'] ?? ''),
            'poa_attorney_name'                => trim($post['poa_attorney_name'] ?? ''),
            'poa_attorney_phone'               => trim($post['poa_attorney_phone'] ?? ''),
            'poa_issue_date'                   => !empty($post['poa_issue_date']) ? $post['poa_issue_date'] : null,
            'poa_expiry_date'                  => !empty($post['poa_expiry_date']) ? $post['poa_expiry_date'] : null,
            'poa_sales_authority'              => $this->input->post('poa_sales_authority') ? 1 : 0,
            'poa_financial_authority'          => $this->input->post('poa_financial_authority') ? 1 : 0,
            'poa_legal_authority'              => $this->input->post('poa_legal_authority') ? 1 : 0,
            'poa_document_signing'             => $this->input->post('poa_document_signing') ? 1 : 0,
            'poa_receipt_authority'            => $this->input->post('poa_receipt_authority') ? 1 : 0,
            'poa_full_authority'               => $this->input->post('poa_full_authority') ? 1 : 0,
            'poa_verification_status'          => $post['poa_verification_status'] ?: 'pending',
            'poa_verified_date'                => !empty($post['poa_verified_date']) ? $post['poa_verified_date'] : null,
            'poa_verified_by'                  => trim($post['poa_verified_by'] ?? ''),
            'poa_notes'                        => trim($post['poa_notes'] ?? ''),
            'poa_document_filename'            => isset($existing) ? $existing->poa_document_filename : null,
            
            // EMI Settings
            'emi_enabled'                      => $this->input->post('emi_enabled') ? 1 : 0,
            'emi_interest_type'                => $post['emi_interest_type'] ?: 'none',
            'emi_interest_rate_annual'         => !empty($post['emi_interest_rate_annual']) ? (float)$post['emi_interest_rate_annual'] : null,
            'emi_penalty_rate_annual'          => !empty($post['emi_penalty_rate_annual']) ? (float)$post['emi_penalty_rate_annual'] : null,
            'emi_grace_days'                   => !empty($post['emi_grace_days']) ? (int)$post['emi_grace_days'] : null,
            'emi_default_tenor_months'         => !empty($post['emi_default_tenor_months']) ? (int)$post['emi_default_tenor_months'] : null,
            
            // Survey & Patta
            'survey_info'                      => trim($post['survey_info'] ?? ''),
            'patta_info'                       => trim($post['patta_info'] ?? ''),
            
            // Documents
            'pr_document'                      => isset($existing) ? $existing->pr_document : null,
            'current_document'                 => isset($existing) ? $existing->current_document : null,
            'layout_plan_document'             => isset($existing) ? $existing->layout_plan_document : null,
        ];

        // Handle file uploads
        if (isset($_FILES['poa_document_filename']) && !empty($_FILES['poa_document_filename']['name'])) {
            $uploaded = $this->handle_project_file_upload('poa_document_filename');
            if ($uploaded) {
                $data['poa_document_filename'] = $uploaded;
            }
        }

        foreach (['pr_document', 'current_document', 'layout_plan_document'] as $field) {
            if (isset($_FILES[$field]) && !empty($_FILES[$field]['name'])) {
                $uploaded = $this->handle_project_file_upload($field);
                if ($uploaded) {
                    $data[$field] = $uploaded;
                }
            }
        }

        return $data;
    }

    private function normalize_json($value)
    {
        $value = trim($value);
        if (empty($value)) {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decoded);
        }
        return null;
    }

    private function handle_project_file_upload($field)
    {
        if (!isset($_FILES[$field]) || empty($_FILES[$field]['name'])) {
            return false;
        }
        $upload_path = FCPATH . 'uploads/realestate/projects/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $extension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $filename = uniqid('proj_') . '.' . strtolower($extension);

        if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_path . $filename)) {
            return 'uploads/realestate/projects/' . $filename;
        }

        return false;
    }

    private function validate_project_form()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', _l('project_name'), 'required|trim|max_length[255]');
        $this->form_validation->set_rules('code', _l('project_code'), 'trim|max_length[50]');
        $this->form_validation->set_rules('status', _l('project_status'), 'in_list[planning,active,completed,on_hold]');
        
        if ($this->form_validation->run() === false) {
            set_alert('danger', validation_errors('<div>', '</div>'));
            return false;
        }
        
        return true;
    }

    // ==================== BLOCKS ====================
    
    public function block($project_id, $id = '')
    {
        if (!has_permission('real_estate_projects', '', 'view')) {
            access_denied('Real Estate Projects');
        }
        
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['project_id'] = $project_id;
            
            if ($id == '') {
                $block_id = $this->real_estate_model->add_block($data);
                if ($block_id) {
                    set_alert('success', _l('block_added_successfully'));
                }
            } else {
                if ($this->real_estate_model->update_block($data, $id)) {
                    set_alert('success', _l('block_updated_successfully'));
                }
            }
        }
        
        redirect(admin_url('real_estat/project/' . $project_id));
    }

    public function delete_block($id, $project_id)
    {
        if (!has_permission('real_estate_projects', '', 'delete')) {
            access_denied('Real Estate Projects');
        }
        
        if ($this->real_estate_model->delete_block($id)) {
            set_alert('success', _l('block_deleted_successfully'));
        }
        
        redirect(admin_url('real_estat/project/' . $project_id));
    }

    // ==================== PLOTS ====================
    
    public function plots($project_id = '')
    {
        if (!has_permission('real_estate_plots', '', 'view')) {
            access_denied('Real Estate Plots');
        }
        
        $data['title'] = _l('real_estate_plots');
        $data['plots'] = $this->real_estate_model->get_plots('', $project_id);
        $data['projects'] = $this->real_estate_model->get_projects();
        $data['selected_project'] = $project_id;
        
        $this->load->view('plots/manage', $data);
    }

    public function plot($id = '')
    {
        if (!has_permission('real_estate_plots', '', 'view')) {
            access_denied('Real Estate Plots');
        }
        
        if ($this->input->post()) {
            if ($id == '') {
                // Add new plot
                if (!has_permission('real_estate_plots', '', 'create')) {
                    access_denied('Real Estate Plots');
                }
                
                $plot_id = $this->real_estate_model->add_plot($this->input->post());
                if ($plot_id) {
                    set_alert('success', _l('plot_added_successfully'));
                    redirect(admin_url('real_estat/plots/' . $this->input->post('project_id')));
                } else {
                    set_alert('danger', _l('plot_number_exists'));
                }
            } else {
                // Update existing plot
                if (!has_permission('real_estate_plots', '', 'edit')) {
                    access_denied('Real Estate Plots');
                }
                
                $success = $this->real_estate_model->update_plot($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('plot_updated_successfully'));
                    $plot = $this->real_estate_model->get_plots($id);
                    redirect(admin_url('real_estat/plots/' . $plot->project_id));
                }
            }
        }
        
        if (is_numeric($id)) {
            $data['plot'] = $this->real_estate_model->get_plots($id);
            if (!$data['plot']) {
                show_404();
            }
        }
        
        $data['title'] = $id ? _l('edit_plot') : _l('new_plot');
        $data['projects'] = $this->real_estate_model->get_projects();
        $data['blocks'] = [];
        
        if (isset($data['plot'])) {
            $data['blocks'] = $this->real_estate_model->get_blocks('', $data['plot']->project_id);
        }
        
        $this->load->view('plots/plot', $data);
    }

    public function get_blocks_by_project($project_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->real_estate_model->get_blocks('', $project_id));
    }

    public function delete_plot($id)
    {
        if (!has_permission('real_estate_plots', '', 'delete')) {
            access_denied('Real Estate Plots');
        }
        
        $plot = $this->real_estate_model->get_plots($id);
        
        if ($this->real_estate_model->delete_plot($id)) {
            set_alert('success', _l('plot_deleted_successfully'));
        } else {
            set_alert('danger', _l('something_went_wrong'));
        }
        
        redirect(admin_url('real_estat/plots/' . $plot->project_id));
    }

    public function bulk_import_plots()
    {
        if (!has_permission('real_estate_plots', '', 'create')) {
            access_denied('Real Estate Plots');
        }
        
        if ($this->input->post()) {
            // Handle CSV upload and import
            $project_id = $this->input->post('project_id');
            
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
                $file = $_FILES['csv_file']['tmp_name'];
                $plots_data = [];
                
                if (($handle = fopen($file, 'r')) !== FALSE) {
                    $header = fgetcsv($handle); // Skip header row
                    
                    while (($row = fgetcsv($handle)) !== FALSE) {
                        $plots_data[] = [
                            'project_id' => $project_id,
                            'block_id' => $row[0] ?: null,
                            'plot_number' => $row[1],
                            'plot_type' => $row[2] ?: null,
                            'area' => $row[3],
                            'area_unit' => $row[4] ?: 'sqft',
                            'facing' => $row[5] ?: null,
                            'rate_per_unit' => $row[6],
                            'dimensions' => $row[7] ?: null,
                            'status' => $row[8] ?: 'available'
                        ];
                    }
                    fclose($handle);
                }
                
                $result = $this->real_estate_model->bulk_import_plots($plots_data);
                
                if ($result['success'] > 0) {
                    set_alert('success', $result['success'] . ' plots imported successfully');
                }
                if (count($result['errors']) > 0) {
                    set_alert('warning', implode('<br>', $result['errors']));
                }
            }
            
            redirect(admin_url('real_estat/plots/' . $project_id));
        }
        
        $data['title'] = _l('bulk_import_plots');
        $data['projects'] = $this->real_estate_model->get_projects();
        $this->load->view('plots/bulk_import', $data);
    }

    // ==================== BOOKINGS ====================
    
    public function bookings($status = '')
    {
        if (!has_permission('real_estate_bookings', '', 'view')) {
            access_denied('Real Estate Bookings');
        }
        
        $data['title'] = _l('real_estate_bookings');
        $data['bookings'] = $this->real_estate_model->get_bookings('', '', $status);
        $data['selected_status'] = $status;
        
        $this->load->view('bookings/manage', $data);
    }

    public function booking($id = '')
    {
        if (!has_permission('real_estate_bookings', '', 'view')) {
            access_denied('Real Estate Bookings');
        }
        
        if ($this->input->post()) {
            if ($id == '') {
                // Add new booking
                if (!has_permission('real_estate_bookings', '', 'create')) {
                    access_denied('Real Estate Bookings');
                }
                
                $booking_id = $this->real_estate_model->add_booking($this->input->post());
                if ($booking_id) {
                    set_alert('success', _l('booking_added_successfully'));
                    redirect(admin_url('real_estat/booking/' . $booking_id));
                } else {
                    set_alert('danger', _l('plot_not_available'));
                }
            } else {
                // Update existing booking
                if (!has_permission('real_estate_bookings', '', 'edit')) {
                    access_denied('Real Estate Bookings');
                }
                
                $success = $this->real_estate_model->update_booking($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('booking_updated_successfully'));
                }
            }
        }
        
        if (is_numeric($id)) {
            $data['booking'] = $this->real_estate_model->get_bookings($id);
            if (!$data['booking']) {
                show_404();
            }
            $data['installments'] = $this->real_estate_model->get_installments($id);
            $data['communications'] = $this->real_estate_model->get_communications('booking', $id);
        }
        
        $data['title'] = $id ? _l('edit_booking') : _l('new_booking');
        $data['projects'] = $this->real_estate_model->get_projects();
        $data['customers'] = $this->clients_model->get();
        $data['payment_plans'] = $this->real_estate_model->get_payment_plans();
        
        $this->load->view('bookings/booking', $data);
    }

    public function get_available_plots($project_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->real_estate_model->get_plots('', $project_id, 'available'));
    }

    public function cancel_booking()
    {
        if (!has_permission('real_estate_bookings', '', 'edit')) {
            access_denied('Real Estate Bookings');
        }
        
        $id = $this->input->post('booking_id');
        $reason = $this->input->post('cancellation_reason');
        
        if ($this->real_estate_model->cancel_booking($id, $reason)) {
            set_alert('success', _l('booking_cancelled_successfully'));
        } else {
            set_alert('danger', _l('something_went_wrong'));
        }
        
        redirect(admin_url('real_estat/booking/' . $id));
    }

    public function convert_to_sale($id)
    {
        if (!has_permission('real_estate_bookings', '', 'edit')) {
            access_denied('Real Estate Bookings');
        }
        
        if ($this->real_estate_model->convert_to_sale($id)) {
            set_alert('success', _l('booking_converted_successfully'));
        } else {
            set_alert('danger', _l('something_went_wrong'));
        }
        
        redirect(admin_url('real_estat/booking/' . $id));
    }

    // ==================== PAYMENTS & INSTALLMENTS ====================
    
    public function payments()
    {
        if (!has_permission('real_estate_payments', '', 'view')) {
            access_denied('Real Estate Payments');
        }
        
        $data['title'] = _l('real_estate_payments');
        $data['bookings'] = $this->real_estate_model->get_bookings();
        $data['overdue_installments'] = $this->real_estate_model->get_overdue_installments();
        
        $this->load->view('payments/manage', $data);
    }

    public function record_payment()
    {
        if (!has_permission('real_estate_payments', '', 'create')) {
            access_denied('Real Estate Payments');
        }
        
        if ($this->input->post()) {
            $installment_id = $this->input->post('installment_id');
            $paid_amount = $this->input->post('paid_amount');
            $payment_date = $this->input->post('payment_date');
            $generate_invoice = $this->input->post('generate_invoice');
            
            $invoice_id = null;
            
            // Generate invoice if requested
            if ($generate_invoice) {
                $installment = $this->real_estate_model->get_installments('', $installment_id);
                $booking = $this->real_estate_model->get_bookings($installment->booking_id);
                
                // Create invoice using Perfex's invoice system
                $this->load->model('invoices_model');
                
                $invoice_data = [
                    'clientid' => $booking->customer_id,
                    'date' => $payment_date,
                    'duedate' => $payment_date,
                    'currency' => get_base_currency()->id,
                    'subtotal' => $paid_amount,
                    'total' => $paid_amount,
                    'status' => 2, // Paid
                ];
                
                $invoice_id = $this->invoices_model->add($invoice_data);
                
                if ($invoice_id) {
                    // Add invoice item
                    $this->db->insert(db_prefix() . 'itemable', [
                        'rel_id' => $invoice_id,
                        'rel_type' => 'invoice',
                        'description' => 'Installment #' . $installment->installment_number . ' - Booking ' . $booking->booking_code,
                        'long_description' => '',
                        'qty' => 1,
                        'rate' => $paid_amount,
                        'unit' => '',
                        'item_order' => 1,
                    ]);
                }
            }
            
            if ($this->real_estate_model->mark_installment_paid($installment_id, $paid_amount, $payment_date, $invoice_id)) {
                set_alert('success', _l('payment_recorded_successfully'));
                if ($invoice_id) {
                    set_alert('success', _l('invoice_generated_successfully') . ' #' . $invoice_id);
                }
            } else {
                set_alert('danger', _l('something_went_wrong'));
            }
        }
        
        redirect(admin_url('real_estat/payments'));
    }

    // ==================== PAYMENT PLANS ====================
    
    public function payment_plans()
    {
        if (!has_permission('real_estate_payments', '', 'view')) {
            access_denied('Real Estate Payments');
        }
        
        if ($this->input->post()) {
            if (!has_permission('real_estate_payments', '', 'create')) {
                access_denied('Real Estate Payments');
            }
            
            $plan_id = $this->real_estate_model->add_payment_plan($this->input->post());
            if ($plan_id) {
                set_alert('success', _l('payment_plan_added_successfully'));
            }
        }
        
        $data['title'] = _l('payment_plans');
        $data['plans'] = $this->real_estate_model->get_payment_plans();
        
        $this->load->view('payments/payment_plans', $data);
    }

    // ==================== COMMUNICATIONS ====================
    
    public function add_communication()
    {
        if ($this->input->post()) {
            $comm_id = $this->real_estate_model->add_communication($this->input->post());
            if ($comm_id) {
                set_alert('success', _l('communication_added_successfully'));
            }
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }

    // ==================== REPORTS ====================
    
    public function reports()
    {
        $data['title'] = _l('real_estate_reports');
        $data['stats'] = $this->real_estate_model->get_dashboard_statistics();
        $data['projects'] = $this->real_estate_model->get_projects();
        
        $this->load->view('reports/dashboard', $data);
    }

    public function project_report($project_id)
    {
        $data['project'] = $this->real_estate_model->get_projects($project_id);
        $data['stats'] = $this->real_estate_model->get_project_statistics($project_id);
        $data['plots'] = $this->real_estate_model->get_plots('', $project_id);
        
        $this->load->view('reports/project_report', $data);
    }

    // ==================== SETTINGS ====================
    
    public function settings()
    {
        if (!is_admin()) {
            access_denied('Real Estate Settings');
        }
        
        if ($this->input->post()) {
            // Save settings
            foreach ($this->input->post() as $key => $value) {
                update_option('real_estat_' . $key, $value);
            }
            set_alert('success', _l('settings_updated'));
        }
        
        $data['title'] = _l('settings');
        $this->load->view('settings', $data);
    }

    // ==================== ACCOUNTING EXPORTS ====================

    public function accounting_exports()
    {
        if (!has_permission('real_estate', '', 'accounts')) {
            access_denied('Real Estate Accounts');
        }

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to')   ?: date('Y-m-t');
        $types = $this->get_accounting_export_types();

        $counts = [];
        foreach ($types as $key => $config) {
            $this->db->from($config['table']);
            $this->db->where($config['flag'], 0);
            if (!empty($config['date_column'])) {
                $this->db->where($config['date_column'] . ' >=', $from . ' 00:00:00');
                $this->db->where($config['date_column'] . ' <=', $to . ' 23:59:59');
            }
            $counts[$key] = $this->db->count_all_results();
        }

        $data = [
            'title' => _l('accounting_exports'),
            'types' => $types,
            'counts' => $counts,
            'from' => $from,
            'to' => $to,
        ];

        $this->load->view('reports/accounting_exports', $data);
    }

    public function export_accounting_csv()
    {
        if (!has_permission('real_estate', '', 'accounts')) {
            access_denied('Real Estate Accounts');
        }

        $type = $this->input->get('type');
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-t');
        $types = $this->get_accounting_export_types();

        if (!isset($types[$type])) {
            set_alert('warning', _l('invalid_request'));
            redirect(admin_url('real_estat/accounting_exports'));
        }

        $rows = $this->get_accounting_export_rows($type, $from, $to);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $type . '_' . $from . '_' . $to . '.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, $types[$type]['csv_header']);

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row->id;
            fputcsv($out, $this->build_csv_row($type, $row));
        }

        fclose($out);

        if (!empty($ids)) {
            $this->real_estate_model->mark_accounting_exported($types[$type]['table'], $types[$type]['flag'], $ids);
        }
        exit;
    }

    public function accounting_tally_xml()
    {
        if (!has_permission('real_estate', '', 'accounts')) {
            access_denied('Real Estate Accounts');
        }

        $type = $this->input->get('type');
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-t');
        $types = $this->get_accounting_export_types();

        if (!isset($types[$type])) {
            set_alert('warning', _l('invalid_request'));
            redirect(admin_url('real_estat/accounting_exports'));
        }

        $rows = $this->get_accounting_export_rows($type, $from, $to);
        $xml = $this->build_accounting_tally_vouchers($type, $rows);
        $envelope = $this->real_estate_model->build_tally_envelope($xml);

        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $type . '_tally_' . $from . '_' . $to . '.xml');
        echo $envelope;

        if (!empty($rows)) {
            $ids = array_column($rows, 'id');
            $this->real_estate_model->mark_accounting_exported($types[$type]['table'], $types[$type]['flag'], $ids);
        }
        exit;
    }

    public function accounting_tally_push()
    {
        if (!has_permission('real_estate', '', 'accounts')) {
            access_denied('Real Estate Accounts');
        }

        $type = $this->input->post('type');
        $from = $this->input->post('from') ?: date('Y-m-01');
        $to   = $this->input->post('to') ?: date('Y-m-t');
        $types = $this->get_accounting_export_types();

        if (!isset($types[$type])) {
            set_alert('warning', _l('invalid_request'));
            redirect(admin_url('real_estat/accounting_exports'));
        }

        $rows = $this->get_accounting_export_rows($type, $from, $to);
        if (empty($rows)) {
            set_alert('warning', _l('no_records_found'));
            redirect(admin_url('real_estat/accounting_exports?from=' . $from . '&to=' . $to));
        }

        $xml = $this->build_accounting_tally_vouchers($type, $rows);
        $envelope = $this->real_estate_model->build_tally_envelope($xml);
        $this->load->library('tally_http');
        $result = $this->tally_http->send($envelope);

        if ($result['success']) {
            $ids = array_column($rows, 'id');
            $this->real_estate_model->mark_accounting_exported($types[$type]['table'], $types[$type]['flag'], $ids);
            set_alert('success', _l('tally_push_success'));
        } else {
            set_alert('warning', _l('tally_push_failed') . ' ' . $result['error']);
        }

        redirect(admin_url('real_estat/accounting_exports?from=' . $from . '&to=' . $to));
    }

    private function get_accounting_export_types()
    {
        return [
            'receipts' => [
                'label' => _l('payments_received'),
                'table' => db_prefix() . 'real_booking_payments',
                'flag' => 'exported_to_accounts',
                'date_column' => 'payment_date',
                'csv_header' => ['Date', 'Booking', 'Project', 'Customer', 'Amount', 'Mode', 'Reference', 'Ledger Credit', 'Ledger Debit'],
                'ledger_key' => 'sales',
                'ledger_option' => 'ledger_sales',
            ],
            'agent_commissions' => [
                'label' => _l('agent_commissions'),
                'table' => db_prefix() . 'real_agent_commission_payments',
                'flag' => 'exported_to_accounts',
                'date_column' => 'payment_date',
                'csv_header' => ['Date', 'Agent', 'Project', 'Amount', 'Mode', 'Reference', 'Ledger Expense', 'Ledger Credit'],
                'ledger_key' => 'agent_commission_expense',
                'ledger_option' => 'ledger_agent_commission_expense',
            ],
            'owner_payouts' => [
                'label' => _l('owner_payouts'),
                'table' => db_prefix() . 'real_owner_payouts',
                'flag' => 'exported_to_accounts',
                'date_column' => 'payment_date',
                'csv_header' => ['Date', 'Owner', 'Project', 'Amount', 'Mode', 'Reference', 'Ledger Liability', 'Ledger Credit'],
                'ledger_key' => 'owner_payable',
                'ledger_option' => 'ledger_owner_payable',
            ],
            'travel_claims' => [
                'label' => _l('travel_claims'),
                'table' => db_prefix() . 'real_travel_claims',
                'flag' => 'exported_to_accounts',
                'date_column' => 'date',
                'csv_header' => ['Date', 'Agent', 'Project', 'Distance', 'Amount', 'Status', 'Ledger Expense', 'Ledger Credit'],
                'ledger_key' => 'travel_expense',
                'ledger_option' => 'ledger_travel_expense',
            ],
            'incentives' => [
                'label' => _l('sales_incentives'),
                'table' => db_prefix() . 'real_sales_incentives',
                'flag' => 'exported_to_accounts',
                'date_column' => 'updated_at',
                'csv_header' => ['Month', 'Agent', 'Achievement %', 'Base Commission', 'Amount', 'Status', 'Ledger Expense', 'Ledger Credit'],
                'ledger_key' => 'incentive_expense',
                'ledger_option' => 'ledger_incentive_expense',
            ],
        ];
    }

    private function get_accounting_export_rows($type, $from, $to)
    {
        $rows = [];
        switch ($type) {
            case 'receipts':
                $rows = $this->db->select('p.*, b.project_id, b.booking_code, c.company as customer_name, pr.name as project_name')
                    ->from(db_prefix() . 'real_booking_payments p')
                    ->join(db_prefix() . 're_bookings b', 'b.id = p.booking_id', 'left')
                    ->join(db_prefix() . 'clients c', 'c.userid = b.customer_id', 'left')
                    ->join(db_prefix() . 're_projects pr', 'pr.id = b.project_id', 'left')
                    ->where('p.exported_to_accounts', 0)
                    ->where('p.payment_date >=', $from . ' 00:00:00')
                    ->where('p.payment_date <=', $to . ' 23:59:59')
                    ->order_by('p.payment_date', 'ASC')
                    ->get()
                    ->result();
                break;
            case 'agent_commissions':
                $rows = $this->db->select('p.*, s.firstname, s.lastname, pr.name as project_name')
                    ->from(db_prefix() . 'real_agent_commission_payments p')
                    ->join(db_prefix() . 'staff s', 's.staffid = p.staff_id', 'left')
                    ->join(db_prefix() . 're_projects pr', 'pr.id = p.project_id', 'left')
                    ->where('p.exported_to_accounts', 0)
                    ->where('p.payment_date >=', $from . ' 00:00:00')
                    ->where('p.payment_date <=', $to . ' 23:59:59')
                    ->order_by('p.payment_date', 'ASC')
                    ->get()
                    ->result();
                break;
            case 'owner_payouts':
                $rows = $this->db->select('p.*, o.name as owner_name, pr.name as project_name')
                    ->from(db_prefix() . 'real_owner_payouts p')
                    ->join(db_prefix() . 'real_project_owners po', 'po.project_id = p.project_id', 'left')
                    ->join(db_prefix() . 'real_owners o', 'o.id = po.owner_id', 'left')
                    ->join(db_prefix() . 're_projects pr', 'pr.id = p.project_id', 'left')
                    ->where('p.exported_to_accounts', 0)
                    ->where('p.payment_date >=', $from . ' 00:00:00')
                    ->where('p.payment_date <=', $to . ' 23:59:59')
                    ->order_by('p.payment_date', 'ASC')
                    ->get()
                    ->result();
                break;
            case 'travel_claims':
                $rows = $this->db->select('t.*, s.firstname, s.lastname, pr.name as project_name')
                    ->from(db_prefix() . 'real_travel_claims t')
                    ->join(db_prefix() . 'staff s', 's.staffid = t.staff_id', 'left')
                    ->join(db_prefix() . 're_projects pr', 'pr.id = t.project_id', 'left')
                    ->where('t.exported_to_accounts', 0)
                    ->where('t.date >=', $from)
                    ->where('t.date <=', $to)
                    ->where('t.status', 'approved')
                    ->order_by('t.date', 'ASC')
                    ->get()
                    ->result();
                break;
            case 'incentives':
                $rows = $this->db->select('i.*, s.firstname, s.lastname')
                    ->from(db_prefix() . 'real_sales_incentives i')
                    ->join(db_prefix() . 'staff s', 's.staffid = i.staff_id', 'left')
                    ->where('i.exported_to_accounts', 0)
                    ->where('i.updated_at >=', $from . ' 00:00:00')
                    ->where('i.updated_at <=', $to . ' 23:59:59')
                    ->order_by('i.updated_at', 'ASC')
                    ->get()
                    ->result();
                break;
        }
        return $rows;
    }

    private function build_csv_row($type, $row)
    {
        switch ($type) {
            case 'receipts':
                $creditLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'sales', 'ledger_sales', 'Sales');
                $debitLedger = get_option('real_estat_ledger_bank') ?: get_option('real_estat_ledger_cash') ?: 'Bank';
                return [
                    $row->payment_date,
                    $row->booking_code,
                    $row->project_name,
                    $row->customer_name,
                    $row->amount,
                    $row->mode,
                    $row->txn_ref ?? '',
                    $creditLedger,
                    $debitLedger,
                ];
            case 'agent_commissions':
                $expenseLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'agent_commission_expense', 'ledger_agent_commission_expense', 'Commission Expense');
                $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                return [
                    $row->payment_date,
                    trim($row->firstname . ' ' . $row->lastname),
                    $row->project_name,
                    $row->amount,
                    $row->mode,
                    $row->ref_no,
                    $expenseLedger,
                    $bankLedger,
                ];
            case 'owner_payouts':
                $ownerName = $row->owner_name ?? 'Owner';
                $liabilityLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'owner_payable', 'ledger_owner_payable', 'Owner Payable');
                $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                return [
                    $row->payment_date,
                    $ownerName,
                    $row->project_name,
                    $row->amount,
                    $row->mode,
                    $row->reference ?? '',
                    $liabilityLedger,
                    $bankLedger,
                ];
            case 'travel_claims':
                $agentName = trim($row->firstname . ' ' . $row->lastname);
                $expenseLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'travel_expense', 'ledger_travel_expense', 'Travel Expense');
                $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                return [
                    $row->date,
                    $agentName,
                    $row->project_name,
                    $row->distance_km,
                    $row->amount,
                    $row->status,
                    $expenseLedger,
                    $bankLedger,
                ];
            case 'incentives':
                $agentName = trim($row->firstname . ' ' . $row->lastname);
                $expenseLedger = get_option('real_estat_ledger_incentive_expense') ?: 'Incentives';
                $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                return [
                    $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT),
                    $agentName,
                    $row->achievement_pct,
                    $row->base_commission,
                    $row->incentive_amount,
                    $row->status,
                    $expenseLedger,
                    $bankLedger,
                ];
        }
        return [];
    }

    private function build_accounting_tally_vouchers($type, $rows)
    {
        $xml = '';
        foreach ($rows as $row) {
            switch ($type) {
                case 'receipts':
                    $creditLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'sales', 'ledger_sales', 'Sales');
                    $debitLedger = get_option('real_estat_ledger_bank') ?: get_option('real_estat_ledger_cash') ?: 'Bank';
                    $xml .= $this->build_receipt_voucher_xml($row, $debitLedger, $creditLedger);
                    break;
                case 'agent_commissions':
                    $expenseLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'agent_commission_expense', 'ledger_agent_commission_expense', 'Commission Expense');
                    $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                    $xml .= $this->build_payment_voucher_xml($row->payment_date, $expenseLedger, $bankLedger, 'Agent Commission - ' . trim($row->firstname . ' ' . $row->lastname), $row->amount);
                    break;
                case 'owner_payouts':
                    $liabilityLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'owner_payable', 'ledger_owner_payable', 'Owner Payable');
                    $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                    $ownerName = $row->owner_name ?: 'Owner Payout';
                    $xml .= $this->build_payment_voucher_xml($row->payment_date, $liabilityLedger, $bankLedger, 'Owner Payout - ' . $ownerName, $row->amount);
                    break;
                case 'travel_claims':
                    $expenseLedger = $this->real_estate_model->get_project_accounting_ledger($row->project_id, 'travel_expense', 'ledger_travel_expense', 'Travel Expense');
                    $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                    $agent = trim($row->firstname . ' ' . $row->lastname);
                    $xml .= $this->build_payment_voucher_xml($row->date, $expenseLedger, $bankLedger, 'Travel Claim - ' . $agent, $row->amount);
                    break;
                case 'incentives':
                    $expenseLedger = get_option('real_estat_ledger_incentive_expense') ?: 'Incentive Expenses';
                    $bankLedger = get_option('real_estat_ledger_bank') ?: 'Bank';
                    $agent = trim($row->firstname . ' ' . $row->lastname);
                    $period = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
                    $xml .= $this->build_payment_voucher_xml($row->updated_at, $expenseLedger, $bankLedger, 'Sales Incentive - ' . $agent . ' (' . $period . ')', $row->incentive_amount);
                    break;
            }
        }
        return $xml;
    }

    private function build_receipt_voucher_xml($row, $debitLedger, $creditLedger)
    {
        $date = date('Ymd', strtotime($row->payment_date));
        $amount = number_format($row->amount, 2, '.', '');
        $narration = 'Booking ' . ($row->booking_code ?? '#'.$row->booking_id);
        $voucherNo = $row->id;

        return '
<TALLYMESSAGE xmlns:UDF="TallyUDF">
  <VOUCHER VCHTYPE="Receipt" ACTION="Create">
    <DATE>' . $date . '</DATE>
    <VOUCHERTYPENAME>Receipt</VOUCHERTYPENAME>
    <VOUCHERNUMBER>' . $voucherNo . '</VOUCHERNUMBER>
    <PARTYLEDGERNAME>' . htmlspecialchars($row->customer_name ?? 'Customer') . '</PARTYLEDGERNAME>
    <NARRATION>' . htmlspecialchars($narration) . '</NARRATION>
    <ALLLEDGERENTRIES.LIST>
      <LEDGERNAME>' . htmlspecialchars($debitLedger) . '</LEDGERNAME>
      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
      <AMOUNT>' . $amount . '</AMOUNT>
    </ALLLEDGERENTRIES.LIST>
    <ALLLEDGERENTRIES.LIST>
      <LEDGERNAME>' . htmlspecialchars($creditLedger) . '</LEDGERNAME>
      <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
      <AMOUNT>-' . $amount . '</AMOUNT>
    </ALLLEDGERENTRIES.LIST>
  </VOUCHER>
</TALLYMESSAGE>';
    }

    private function build_payment_voucher_xml($dateRaw, $debitLedger, $creditLedger, $narration, $amount)
    {
        $date = date('Ymd', strtotime($dateRaw));
        $amount = number_format($amount, 2, '.', '');

        return '
<TALLYMESSAGE xmlns:UDF="TallyUDF">
  <VOUCHER VCHTYPE="Payment" ACTION="Create">
    <DATE>' . $date . '</DATE>
    <VOUCHERTYPENAME>Payment</VOUCHERTYPENAME>
    <VOUCHERNUMBER>' . uniqid() . '</VOUCHERNUMBER>
    <NARRATION>' . htmlspecialchars($narration) . '</NARRATION>
    <ALLLEDGERENTRIES.LIST>
      <LEDGERNAME>' . htmlspecialchars($debitLedger) . '</LEDGERNAME>
      <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
      <AMOUNT>-' . $amount . '</AMOUNT>
    </ALLLEDGERENTRIES.LIST>
    <ALLLEDGERENTRIES.LIST>
      <LEDGERNAME>' . htmlspecialchars($creditLedger) . '</LEDGERNAME>
      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
      <AMOUNT>' . $amount . '</AMOUNT>
    </ALLLEDGERENTRIES.LIST>
  </VOUCHER>
</TALLYMESSAGE>';
    }

    // ==================== PROJECT CODE GENERATION ====================
    
    /**
     * Get next sequential project code
     * Returns the next available sequence number for project codes
     */
    public function get_next_project_code()
    {
        header('Content-Type: application/json');
        
        $format = $this->input->get('format') ?: 'default';
        $prefix = $this->input->get('prefix') ?: 'PRJ';
        $year = date('Y');
        $date = date('Ymd');
        
        // Build pattern based on format
        $pattern = '';
        switch($format) {
            case 'format1':
                $pattern = $prefix . '-' . $year . '-%';
                break;
            case 'format2':
                $pattern = 'RE-' . $date . '-%';
                break;
            case 'format4':
                $pattern = $prefix . '-' . $year;
                break;
            case 'custom':
                $pattern = $prefix . '-' . $year . '-%';
                break;
            default:
                $pattern = $prefix . '-' . $date . '-%';
        }
        
        // Query database for the highest sequence number
        $this->db->select('code');
        $this->db->from(db_prefix() . 're_projects');
        $this->db->like('code', $pattern, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        $nextSeq = 1;
        
        if ($query->num_rows() > 0) {
            $lastCode = $query->row()->code;
            // Extract the sequence number from the last code
            if (preg_match('/(\d+)$/', $lastCode, $matches)) {
                $nextSeq = intval($matches[1]) + 1;
            }
        }
        
        // Return 4-digit padded sequence
        echo json_encode([
            'success' => true,
            'sequence' => str_pad($nextSeq, 4, '0', STR_PAD_LEFT),
            'next_number' => $nextSeq
        ]);
    }
    
    /**
     * Validate project code uniqueness
     */
    public function validate_project_code()
    {
        header('Content-Type: application/json');
        
        $code = $this->input->get('code');
        $project_id = $this->input->get('project_id');
        
        if (empty($code)) {
            echo json_encode(['valid' => true]);
            return;
        }
        
        $this->db->select('id');
        $this->db->from(db_prefix() . 're_projects');
        $this->db->where('code', $code);
        
        if ($project_id) {
            $this->db->where('id !=', $project_id);
        }
        
        $query = $this->db->get();
        
        echo json_encode([
            'valid' => $query->num_rows() == 0,
            'exists' => $query->num_rows() > 0
        ]);
    }
}
