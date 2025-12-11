<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="nav navbar-pills navbar-pills-flat nav-stacked">
                                    <li><a href="<?php echo admin_url('realestate/settings'); ?>"><i class="fa fa-cog"></i> <?php echo _l('realestate_general_settings'); ?></a></li>
                                    <li><a href="<?php echo admin_url('realestate/settings/theme'); ?>"><i class="fa fa-paint-brush"></i> <?php echo _l('realestate_theme_settings'); ?></a></li>
                                    <li><a href="<?php echo admin_url('realestate/settings/notifications'); ?>"><i class="fa fa-bell"></i> <?php echo _l('realestate_notification_settings'); ?></a></li>
                                    <li class="active"><a href="<?php echo admin_url('realestate/settings/reports'); ?>"><i class="fa fa-file-text"></i> <?php echo _l('realestate_report_settings'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <?php echo form_open(admin_url('realestate/settings/reports')); ?>
                                <h4>Scheduled Reports</h4>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="enable_scheduled_reports" value="1" <?php echo (isset($reports['enable_scheduled_reports']) && $reports['enable_scheduled_reports']) ? 'checked' : ''; ?>><label>Enable Scheduled Reports</label></div>
                                <hr>
                                <div class="form-group"><label>Report Frequency</label><select class="form-control selectpicker" name="report_frequency"><option value="daily">Daily</option><option value="weekly">Weekly</option><option value="monthly">Monthly</option></select></div>
                                <div class="form-group"><label>Report Time</label><input type="time" class="form-control" name="report_time" value="<?php echo isset($reports['report_time']) ? $reports['report_time'] : '09:00'; ?>"></div>
                                <div class="form-group"><label>Report Recipients (comma-separated emails)</label><input type="text" class="form-control" name="report_recipients" value="<?php echo isset($reports['report_recipients']) ? $reports['report_recipients'] : ''; ?>"></div>
                                <hr>
                                <h5>Report Content</h5>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="include_projects_summary" value="1" <?php echo (isset($reports['include_projects_summary']) && $reports['include_projects_summary']) ? 'checked' : ''; ?>><label>Include Projects Summary</label></div>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="include_plots_summary" value="1" <?php echo (isset($reports['include_plots_summary']) && $reports['include_plots_summary']) ? 'checked' : ''; ?>><label>Include Plots Summary</label></div>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="include_bookings_summary" value="1" <?php echo (isset($reports['include_bookings_summary']) && $reports['include_bookings_summary']) ? 'checked' : ''; ?>><label>Include Bookings Summary</label></div>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="include_revenue_summary" value="1" <?php echo (isset($reports['include_revenue_summary']) && $reports['include_revenue_summary']) ? 'checked' : ''; ?>><label>Include Revenue Summary</label></div>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="include_analytics" value="1" <?php echo (isset($reports['include_analytics']) && $reports['include_analytics']) ? 'checked' : ''; ?>><label>Include Analytics</label></div>
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                <button type="button" class="btn btn-default" onclick="sendTestReport()">Send Test Report</button>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
function sendTestReport() {
    $.post('<?php echo admin_url('realestate/settings/send_test_report'); ?>', function(response) {
        var result = JSON.parse(response);
        if(result.success) {
            alert_float('success', result.message);
        } else {
            alert_float('danger', result.message);
        }
    });
}
</script>
