<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('realestate_settings_model');
    }

    public function index()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        $data['title'] = _l('realestate_settings');
        $data['settings'] = $this->realestate_settings_model->get_all_settings();
        $this->load->view('settings/manage', $data);
    }

    public function save()
    {
        if (!has_permission('realestate', '', 'edit')) {
            access_denied('realestate');
        }

        if ($this->input->post()) {
            $settings = $this->input->post();
            
            foreach ($settings as $key => $value) {
                if (strpos($key, 'realestate_') === 0) {
                    $this->realestate_settings_model->update_setting($key, $value);
                }
            }

            set_alert('success', _l('realestate_settings_updated'));
        }

        redirect(admin_url('realestate/settings'));
    }

    public function theme()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        if ($this->input->post()) {
            $theme_settings = [
                'primary_color' => $this->input->post('primary_color'),
                'secondary_color' => $this->input->post('secondary_color'),
                'success_color' => $this->input->post('success_color'),
                'warning_color' => $this->input->post('warning_color'),
                'danger_color' => $this->input->post('danger_color'),
                'font_family' => $this->input->post('font_family'),
                'font_size' => $this->input->post('font_size')
            ];

            $this->realestate_settings_model->update_setting('realestate_theme', json_encode($theme_settings));
            set_alert('success', _l('realestate_theme_updated'));
            redirect(admin_url('realestate/settings/theme'));
        }

        $data['title'] = _l('realestate_theme_settings');
        $data['theme'] = json_decode($this->realestate_settings_model->get_setting('realestate_theme'), true);
        $this->load->view('settings/theme', $data);
    }

    public function notifications()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        if ($this->input->post()) {
            $notification_settings = [
                'new_project_notification' => $this->input->post('new_project_notification'),
                'plot_booking_notification' => $this->input->post('plot_booking_notification'),
                'payment_received_notification' => $this->input->post('payment_received_notification'),
                'reservation_expiry_notification' => $this->input->post('reservation_expiry_notification'),
                'notification_recipients' => $this->input->post('notification_recipients'),
                'notification_method' => $this->input->post('notification_method')
            ];

            $this->realestate_settings_model->update_setting('realestate_notifications', json_encode($notification_settings));
            set_alert('success', _l('realestate_notifications_updated'));
            redirect(admin_url('realestate/settings/notifications'));
        }

        $data['title'] = _l('realestate_notification_settings');
        $data['notifications'] = json_decode($this->realestate_settings_model->get_setting('realestate_notifications'), true);
        $data['staff'] = $this->staff_model->get();
        $this->load->view('settings/notifications', $data);
    }

    public function reports()
    {
        if (!has_permission('realestate', '', 'view')) {
            access_denied('realestate');
        }

        if ($this->input->post()) {
            $report_settings = [
                'enable_scheduled_reports' => $this->input->post('enable_scheduled_reports'),
                'report_frequency' => $this->input->post('report_frequency'),
                'report_day' => $this->input->post('report_day'),
                'report_time' => $this->input->post('report_time'),
                'report_recipients' => $this->input->post('report_recipients'),
                'include_projects_summary' => $this->input->post('include_projects_summary'),
                'include_plots_summary' => $this->input->post('include_plots_summary'),
                'include_bookings_summary' => $this->input->post('include_bookings_summary'),
                'include_revenue_summary' => $this->input->post('include_revenue_summary'),
                'include_analytics' => $this->input->post('include_analytics')
            ];

            $this->realestate_settings_model->update_setting('realestate_reports', json_encode($report_settings));
            set_alert('success', _l('realestate_report_settings_updated'));
            redirect(admin_url('realestate/settings/reports'));
        }

        $data['title'] = _l('realestate_report_settings');
        $data['reports'] = json_decode($this->realestate_settings_model->get_setting('realestate_reports'), true);
        $data['staff'] = $this->staff_model->get();
        $this->load->view('settings/reports', $data);
    }

    public function send_test_report()
    {
        if (!has_permission('realestate', '', 'view')) {
            ajax_access_denied();
        }

        $this->load->model('reports_model');
        $result = $this->reports_model->generate_and_send_report(true);

        echo json_encode(['success' => $result, 'message' => $result ? _l('realestate_test_report_sent') : _l('realestate_test_report_failed')]);
    }
}
