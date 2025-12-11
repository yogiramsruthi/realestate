<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Realestate extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate/projects_model');
        $this->load->model('realestate/plots_model');
        $this->load->model('realestate/bookings_model');
    }

    /**
     * Dashboard/Overview
     */
    public function index()
    {
        $this->dashboard();
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_dashboard');
        
        // Get statistics
        $project_stats = $this->projects_model->get_statistics();
        $booking_stats = $this->bookings_model->get_statistics();
        
        $data['total_projects'] = $project_stats['total_projects'];
        $data['active_projects'] = $project_stats['active_projects'];
        
        // Get total available plots
        $this->db->where('status', 'available');
        $data['available_plots'] = $this->db->count_all_results(db_prefix() . 'realestate_plots');
        
        $data['total_bookings'] = $booking_stats['total_bookings'];
        $data['total_revenue'] = $booking_stats['total_revenue'];
        
        // Get recent bookings
        $data['recent_bookings'] = $this->bookings_model->get('', []);
        if (is_array($data['recent_bookings']) && count($data['recent_bookings']) > 5) {
            $data['recent_bookings'] = array_slice($data['recent_bookings'], 0, 5);
        }
        
        // Get projects overview
        $data['projects'] = $this->projects_model->get();
        
        $this->load->view('dashboard', $data);
    }
}
