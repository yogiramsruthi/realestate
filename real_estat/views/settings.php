<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('real_estate') . ' - ' . _l('settings'); ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4><?php echo _l('general_settings'); ?></h4>
                                
                                <?php echo render_input('booking_code_prefix', 'Booking Code Prefix', get_option('real_estat_booking_code_prefix') ?: 'BK'); ?>
                                
                                <?php echo render_input('booking_validity_days', 'Default Booking Validity (Days)', get_option('real_estat_booking_validity_days') ?: '15', 'number'); ?>
                                
                                <?php echo render_input('default_area_unit', 'Default Area Unit', get_option('real_estat_default_area_unit') ?: 'sqft'); ?>
                                
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="auto_generate_invoices" id="auto_generate_invoices" value="1" <?php if (get_option('real_estat_auto_generate_invoices') == '1') echo 'checked'; ?>>
                                        <label for="auto_generate_invoices">Auto-generate invoices for installments</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="send_payment_reminders" id="send_payment_reminders" value="1" <?php if (get_option('real_estat_send_payment_reminders') == '1') echo 'checked'; ?>>
                                        <label for="send_payment_reminders">Send payment reminders before due date</label>
                                    </div>
                                </div>
                                
                                <?php echo render_input('reminder_days_before', 'Send Reminder Days Before Due', get_option('real_estat_reminder_days_before') ?: '3', 'number'); ?>
                            </div>
                            
                            <div class="col-md-6">
                                <h4><?php echo _l('display_settings'); ?></h4>
                                
                                <?php echo render_input('plots_per_page', 'Plots Per Page', get_option('real_estat_plots_per_page') ?: '50', 'number'); ?>
                                
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="show_plot_grid_view" id="show_plot_grid_view" value="1" <?php if (get_option('real_estat_show_plot_grid_view') == '1') echo 'checked'; ?>>
                                        <label for="show_plot_grid_view">Enable Plot Grid View</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="show_project_map" id="show_project_map" value="1" <?php if (get_option('real_estat_show_project_map') == '1') echo 'checked'; ?>>
                                        <label for="show_project_map">Show Project Location Map</label>
                                    </div>
                                </div>
                                
                                <h4 class="mtop25"><?php echo _l('email_settings'); ?></h4>
                                
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="email_on_booking" id="email_on_booking" value="1" <?php if (get_option('real_estat_email_on_booking') == '1') echo 'checked'; ?>>
                                        <label for="email_on_booking">Send email on new booking</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="email_on_payment" id="email_on_payment" value="1" <?php if (get_option('real_estat_email_on_payment') == '1') echo 'checked'; ?>>
                                        <label for="email_on_payment">Send email on payment received</label>
                                    </div>
                                </div>
                                
                                <h4 class="mtop25">Accounting / Tally Settings</h4>
                                
                                <div class="form-group">
                                    <?php echo render_input('ledger_sales', 'Default Sales Ledger', get_option('real_estat_ledger_sales')); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('ledger_owner_payable', 'Default Owner Payable Ledger', get_option('real_estat_ledger_owner_payable')); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('ledger_agent_commission_expense', 'Agent Commission Expense Ledger', get_option('real_estat_ledger_agent_commission_expense')); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('ledger_travel_expense', 'Travel Expense Ledger', get_option('real_estat_ledger_travel_expense')); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('ledger_incentive_expense', 'Incentive Expense Ledger', get_option('real_estat_ledger_incentive_expense')); ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo render_input('ledger_bank', 'Default Bank Ledger', get_option('real_estat_ledger_bank')); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo render_input('ledger_cash', 'Default Cash Ledger', get_option('real_estat_ledger_cash')); ?>
                                    </div>
                                </div>

                                <h4 class="mtop25">Tally HTTP Integration</h4>
                                <div class="form-group">
                                    <?php echo render_input('tally_http_endpoint', 'Tally HTTP API Endpoint', get_option('real_estat_tally_http_endpoint')); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('tally_http_username', 'Tally HTTP Username', get_option('real_estat_tally_http_username')); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('tally_http_password', 'Tally HTTP Password', get_option('real_estat_tally_http_password'), 'password'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo render_input('tally_http_company', 'Tally Company Name', get_option('real_estat_tally_http_company')); ?>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="tally_http_auto_push" id="tally_http_auto_push" value="1" <?php if (get_option('real_estat_tally_http_auto_push') == '1') echo 'checked'; ?>>
                                        <label for="tally_http_auto_push">Auto push accounting XML to Tally</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('settings_save'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
