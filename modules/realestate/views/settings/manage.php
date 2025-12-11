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
                                    <li class="active">
                                        <a href="<?php echo admin_url('realestate/settings'); ?>">
                                            <i class="fa fa-cog"></i> <?php echo _l('realestate_general_settings'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo admin_url('realestate/settings/theme'); ?>">
                                            <i class="fa fa-paint-brush"></i> <?php echo _l('realestate_theme_settings'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo admin_url('realestate/settings/notifications'); ?>">
                                            <i class="fa fa-bell"></i> <?php echo _l('realestate_notification_settings'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo admin_url('realestate/settings/reports'); ?>">
                                            <i class="fa fa-file-text"></i> <?php echo _l('realestate_report_settings'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <?php echo form_open(admin_url('realestate/settings/save')); ?>
                                
                                <h4><?php echo _l('realestate_general_settings'); ?></h4>
                                
                                <div class="form-group">
                                    <label for="realestate_company_name"><?php echo _l('realestate_company_name'); ?></label>
                                    <input type="text" class="form-control" name="realestate_company_name" 
                                           value="<?php echo isset($settings['realestate_company_name']) ? $settings['realestate_company_name'] : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="realestate_enable_public_portal"><?php echo _l('realestate_enable_public_portal'); ?></label>
                                    <select class="form-control selectpicker" name="realestate_enable_public_portal">
                                        <option value="1" <?php echo (isset($settings['realestate_enable_public_portal']) && $settings['realestate_enable_public_portal'] == '1') ? 'selected' : ''; ?>><?php echo _l('enabled'); ?></option>
                                        <option value="0" <?php echo (isset($settings['realestate_enable_public_portal']) && $settings['realestate_enable_public_portal'] == '0') ? 'selected' : ''; ?>><?php echo _l('disabled'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="realestate_currency"><?php echo _l('realestate_currency'); ?></label>
                                    <select class="form-control selectpicker" name="realestate_currency">
                                        <?php foreach(get_all_currencies() as $currency) { ?>
                                            <option value="<?php echo $currency->id; ?>" 
                                                <?php echo (isset($settings['realestate_currency']) && $settings['realestate_currency'] == $currency->id) ? 'selected' : ''; ?>>
                                                <?php echo $currency->name . ' (' . $currency->symbol . ')'; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="realestate_date_format"><?php echo _l('realestate_date_format'); ?></label>
                                    <input type="text" class="form-control" name="realestate_date_format" 
                                           value="<?php echo isset($settings['realestate_date_format']) ? $settings['realestate_date_format'] : 'Y-m-d'; ?>"
                                           placeholder="Y-m-d">
                                    <small class="text-muted"><?php echo _l('realestate_date_format_help'); ?></small>
                                </div>

                                <div class="form-group">
                                    <label for="realestate_default_language"><?php echo _l('realestate_default_language'); ?></label>
                                    <select class="form-control selectpicker" name="realestate_default_language">
                                        <option value="english" <?php echo (isset($settings['realestate_default_language']) && $settings['realestate_default_language'] == 'english') ? 'selected' : ''; ?>>English</option>
                                        <option value="spanish" <?php echo (isset($settings['realestate_default_language']) && $settings['realestate_default_language'] == 'spanish') ? 'selected' : ''; ?>>Spanish</option>
                                        <option value="french" <?php echo (isset($settings['realestate_default_language']) && $settings['realestate_default_language'] == 'french') ? 'selected' : ''; ?>>French</option>
                                        <option value="german" <?php echo (isset($settings['realestate_default_language']) && $settings['realestate_default_language'] == 'german') ? 'selected' : ''; ?>>German</option>
                                    </select>
                                </div>

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
