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
                                    <li class="active"><a href="<?php echo admin_url('realestate/settings/theme'); ?>"><i class="fa fa-paint-brush"></i> <?php echo _l('realestate_theme_settings'); ?></a></li>
                                    <li><a href="<?php echo admin_url('realestate/settings/notifications'); ?>"><i class="fa fa-bell"></i> <?php echo _l('realestate_notification_settings'); ?></a></li>
                                    <li><a href="<?php echo admin_url('realestate/settings/reports'); ?>"><i class="fa fa-file-text"></i> <?php echo _l('realestate_report_settings'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <?php echo form_open(admin_url('realestate/settings/theme')); ?>
                                <h4><?php echo _l('realestate_theme_customization'); ?></h4>
                                <div class="form-group"><label>Primary Color</label><input type="color" class="form-control" name="primary_color" value="<?php echo isset($theme['primary_color']) ? $theme['primary_color'] : '#2196F3'; ?>"></div>
                                <div class="form-group"><label>Secondary Color</label><input type="color" class="form-control" name="secondary_color" value="<?php echo isset($theme['secondary_color']) ? $theme['secondary_color'] : '#757575'; ?>"></div>
                                <div class="form-group"><label>Success Color</label><input type="color" class="form-control" name="success_color" value="<?php echo isset($theme['success_color']) ? $theme['success_color'] : '#4CAF50'; ?>"></div>
                                <div class="form-group"><label>Warning Color</label><input type="color" class="form-control" name="warning_color" value="<?php echo isset($theme['warning_color']) ? $theme['warning_color'] : '#FF9800'; ?>"></div>
                                <div class="form-group"><label>Danger Color</label><input type="color" class="form-control" name="danger_color" value="<?php echo isset($theme['danger_color']) ? $theme['danger_color'] : '#F44336'; ?>"></div>
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
