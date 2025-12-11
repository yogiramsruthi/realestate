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
                                    <li class="active"><a href="<?php echo admin_url('realestate/settings/notifications'); ?>"><i class="fa fa-bell"></i> <?php echo _l('realestate_notification_settings'); ?></a></li>
                                    <li><a href="<?php echo admin_url('realestate/settings/reports'); ?>"><i class="fa fa-file-text"></i> <?php echo _l('realestate_report_settings'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <?php echo form_open(admin_url('realestate/settings/notifications')); ?>
                                <h4>Notification Preferences</h4>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="new_project_notification" value="1" <?php echo (isset($notifications['new_project_notification']) && $notifications['new_project_notification']) ? 'checked' : ''; ?>><label>Notify on New Project</label></div>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="plot_booking_notification" value="1" <?php echo (isset($notifications['plot_booking_notification']) && $notifications['plot_booking_notification']) ? 'checked' : ''; ?>><label>Notify on Plot Booking</label></div>
                                <div class="checkbox checkbox-primary"><input type="checkbox" name="payment_received_notification" value="1" <?php echo (isset($notifications['payment_received_notification']) && $notifications['payment_received_notification']) ? 'checked' : ''; ?>><label>Notify on Payment Received</label></div>
                                <hr>
                                <div class="form-group"><label>Notification Recipients (comma-separated emails)</label><input type="text" class="form-control" name="notification_recipients" value="<?php echo isset($notifications['notification_recipients']) ? $notifications['notification_recipients'] : ''; ?>"></div>
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
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
