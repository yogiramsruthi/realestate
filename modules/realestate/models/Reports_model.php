<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'projects_model',
            'plots_model',
            'bookings_model',
            'transactions_model',
            'realestate_settings_model'
        ]);
    }

    public function generate_and_send_report($is_test = false)
    {
        $report_settings = json_decode($this->realestate_settings_model->get_setting('realestate_reports'), true);

        if (!$report_settings || !$report_settings['enable_scheduled_reports']) {
            return false;
        }

        $report_data = $this->compile_report_data($report_settings);
        $html = $this->generate_report_html($report_data, $report_settings);

        $recipients = $is_test ? [get_staff_email()] : explode(',', $report_settings['report_recipients']);

        foreach ($recipients as $email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->send_report_email($email, $html, $is_test);
            }
        }

        if (!$is_test) {
            $this->log_report_sent();
        }

        return true;
    }

    private function compile_report_data($settings)
    {
        $data = [];
        $date_from = date('Y-m-01'); // First day of current month
        $date_to = date('Y-m-d');

        if ($settings['include_projects_summary']) {
            $data['projects'] = [
                'total' => $this->projects_model->get_count(),
                'active' => $this->projects_model->get_count(['status' => 'active']),
                'draft' => $this->projects_model->get_count(['status' => 'draft']),
                'archived' => $this->projects_model->get_count(['status' => 'archived'])
            ];
        }

        if ($settings['include_plots_summary']) {
            $data['plots'] = [
                'total' => $this->plots_model->get_count(),
                'available' => $this->plots_model->get_count(['status' => 'available']),
                'booked' => $this->plots_model->get_count(['status' => 'booked']),
                'sold' => $this->plots_model->get_count(['status' => 'sold']),
                'reserved' => $this->plots_model->get_count(['status' => 'reserved'])
            ];
        }

        if ($settings['include_bookings_summary']) {
            $data['bookings'] = [
                'total' => $this->bookings_model->get_count(),
                'this_month' => $this->bookings_model->get_count(['date_from' => $date_from, 'date_to' => $date_to]),
                'pending' => $this->bookings_model->get_count(['status' => 'pending']),
                'confirmed' => $this->bookings_model->get_count(['status' => 'confirmed'])
            ];
        }

        if ($settings['include_revenue_summary']) {
            $data['revenue'] = [
                'total' => $this->transactions_model->get_total_revenue(),
                'this_month' => $this->transactions_model->get_total_revenue($date_from, $date_to),
                'pending' => $this->bookings_model->get_total_pending_amount()
            ];
        }

        if ($settings['include_analytics']) {
            $data['analytics'] = $this->plots_model->get_analytics();
        }

        return $data;
    }

    private function generate_report_html($data, $settings)
    {
        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; }
            h2 { color: #666; margin-top: 30px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f5f5f5; font-weight: bold; }
            .summary-box { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 5px; }
            .metric { display: inline-block; margin: 10px 20px 10px 0; }
            .metric-value { font-size: 24px; font-weight: bold; color: #2196F3; }
            .metric-label { font-size: 14px; color: #666; }
        </style></head><body>';

        $html .= '<h1>Real Estate Management Report</h1>';
        $html .= '<p>Report generated on: ' . date('F d, Y H:i:s') . '</p>';

        if (isset($data['projects'])) {
            $html .= '<h2>Projects Summary</h2><div class="summary-box">';
            $html .= '<div class="metric"><div class="metric-value">' . $data['projects']['total'] . '</div><div class="metric-label">Total Projects</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['projects']['active'] . '</div><div class="metric-label">Active</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['projects']['draft'] . '</div><div class="metric-label">Draft</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['projects']['archived'] . '</div><div class="metric-label">Archived</div></div>';
            $html .= '</div>';
        }

        if (isset($data['plots'])) {
            $html .= '<h2>Plots Summary</h2><div class="summary-box">';
            $html .= '<div class="metric"><div class="metric-value">' . $data['plots']['total'] . '</div><div class="metric-label">Total Plots</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['plots']['available'] . '</div><div class="metric-label">Available</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['plots']['booked'] . '</div><div class="metric-label">Booked</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['plots']['sold'] . '</div><div class="metric-label">Sold</div></div>';
            $html .= '</div>';
        }

        if (isset($data['bookings'])) {
            $html .= '<h2>Bookings Summary</h2><div class="summary-box">';
            $html .= '<div class="metric"><div class="metric-value">' . $data['bookings']['total'] . '</div><div class="metric-label">Total Bookings</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['bookings']['this_month'] . '</div><div class="metric-label">This Month</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . $data['bookings']['confirmed'] . '</div><div class="metric-label">Confirmed</div></div>';
            $html .= '</div>';
        }

        if (isset($data['revenue'])) {
            $html .= '<h2>Revenue Summary</h2><div class="summary-box">';
            $html .= '<div class="metric"><div class="metric-value">' . app_format_money($data['revenue']['total'], get_base_currency()) . '</div><div class="metric-label">Total Revenue</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . app_format_money($data['revenue']['this_month'], get_base_currency()) . '</div><div class="metric-label">This Month</div></div>';
            $html .= '<div class="metric"><div class="metric-value">' . app_format_money($data['revenue']['pending'], get_base_currency()) . '</div><div class="metric-label">Pending</div></div>';
            $html .= '</div>';
        }

        $html .= '</body></html>';

        return $html;
    }

    private function send_report_email($to, $html, $is_test = false)
    {
        $subject = ($is_test ? '[Test] ' : '') . 'Real Estate Management Report - ' . date('F d, Y');

        $this->load->library('email');
        $this->email->from(get_option('smtp_email'), get_option('companyname'));
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($html);
        $this->email->set_mailtype('html');

        return $this->email->send();
    }

    private function log_report_sent()
    {
        log_activity('Scheduled Real Estate Report Sent [Date: ' . date('Y-m-d H:i:s') . ']');
    }
}
