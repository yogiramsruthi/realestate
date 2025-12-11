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
            
            // Handle file upload for plot map
            if (isset($_FILES['plot_map_image']) && $_FILES['plot_map_image']['error'] == 0) {
                $upload_path = FCPATH . 'uploads/realestate/plot_maps/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }
                $file_name = time() . '_' . $_FILES['plot_map_image']['name'];
                if (move_uploaded_file($_FILES['plot_map_image']['tmp_name'], $upload_path . $file_name)) {
                    $data['plot_map_image'] = $file_name;
                }
            }
            
            // Calculate price per sqft if plot_size provided
            if (!empty($data['plot_size']) && !empty($data['price'])) {
                $data['price_per_sqft'] = $data['price'] / floatval($data['plot_size']);
            }
            
            // Calculate final price with discount
            if (!empty($data['price'])) {
                $discount_percentage = isset($data['discount_percentage']) ? floatval($data['discount_percentage']) : 0;
                $discount_amount = isset($data['discount_amount']) ? floatval($data['discount_amount']) : 0;
                $data['final_price'] = $this->plots_model->calculate_final_price($data['price'], $discount_percentage, $discount_amount);
            }
            
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
                
                // Track price changes
                $old_plot = $this->plots_model->get($id);
                if ($old_plot && isset($data['price']) && $old_plot->price != $data['price']) {
                    $this->plots_model->add_price_history($id, $old_plot->price, $data['price'], 'Price updated');
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
            $data['price_history'] = $this->plots_model->get_price_history($id);
            $data['waiting_list'] = $this->plots_model->get_waiting_list($id);
            $title = _l('realestate_edit_plot');
        }

        $data['plot_id'] = $id;
        $data['title'] = $title;
        $this->load->view('plots/plot', $data);
    }

    /**
     * Compare plots
     */
    public function compare()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_compare_plots');
        $data['projects'] = $this->projects_model->get();
        
        if ($this->input->post()) {
            $criteria = $this->input->post();
            $data['plots'] = $this->plots_model->get_for_comparison($criteria);
        }
        
        $this->load->view('plots/compare', $data);
    }

    /**
     * Plot analytics
     */
    public function analytics()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $project_id = $this->input->get('project_id');
        
        $data['title'] = _l('realestate_analytics');
        $data['projects'] = $this->projects_model->get();
        $data['analytics'] = $this->plots_model->get_analytics($project_id);
        
        $this->load->view('plots/analytics', $data);
    }

    /**
     * Bulk create plots
     */
    public function bulk_create()
    {
        if (!has_permission('realestate', '', 'create')) {
            access_denied('realestate');
        }

        if ($this->input->post()) {
            $project_id = $this->input->post('project_id');
            $prefix = $this->input->post('prefix');
            $start_number = $this->input->post('start_number');
            $count = $this->input->post('count');
            
            $default_data = [
                'plot_size' => $this->input->post('default_plot_size'),
                'plot_type' => $this->input->post('default_plot_type'),
                'price' => $this->input->post('default_price'),
                'status' => $this->input->post('default_status'),
                'plot_category' => $this->input->post('default_category')
            ];

            $created = $this->plots_model->bulk_create($project_id, $prefix, $start_number, $count, $default_data);
            
            if ($created) {
                set_alert('success', $created . ' ' . _l('realestate_plots_created'));
            } else {
                set_alert('danger', 'Failed to create plots');
            }
            redirect(admin_url('realestate/plots'));
        }

        $data['title'] = _l('realestate_bulk_create_plots');
        $data['projects'] = $this->projects_model->get();
        $this->load->view('plots/bulk_create', $data);
    }

    /**
     * Bulk update plots
     */
    public function bulk_update()
    {
        if (!has_permission('realestate', '', 'edit')) {
            access_denied('realestate');
        }

        if ($this->input->post()) {
            $plot_ids = $this->input->post('plot_ids');
            $update_data = [];
            
            if ($this->input->post('update_status')) {
                $update_data['status'] = $this->input->post('status');
            }
            if ($this->input->post('update_price')) {
                $update_data['price'] = $this->input->post('price');
            }
            if ($this->input->post('update_category')) {
                $update_data['plot_category'] = $this->input->post('plot_category');
            }

            if (!empty($plot_ids) && !empty($update_data)) {
                $success = $this->plots_model->bulk_update($plot_ids, $update_data);
                if ($success) {
                    set_alert('success', _l('realestate_plots_updated'));
                } else {
                    set_alert('danger', 'Failed to update plots');
                }
            }
            redirect(admin_url('realestate/plots'));
        }

        $data['title'] = _l('realestate_bulk_update_plots');
        $data['plots'] = $this->plots_model->get();
        $this->load->view('plots/bulk_update', $data);
    }

    /**
     * Add to waiting list
     */
    public function add_to_waiting_list()
    {
        $plot_id = $this->input->post('plot_id');
        $customer_id = $this->input->post('customer_id');
        $notes = $this->input->post('notes');

        $result = $this->plots_model->add_to_waiting_list($plot_id, $customer_id, $notes);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Added to waiting list successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add to waiting list']);
        }
    }

    /**
     * Get plot map view
     */
    public function plot_map($project_id)
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_plot_map');
        $data['project'] = $this->projects_model->get($project_id);
        $data['plots'] = $this->plots_model->get_by_project($project_id);
        
        $this->load->view('plots/plot_map', $data);
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
