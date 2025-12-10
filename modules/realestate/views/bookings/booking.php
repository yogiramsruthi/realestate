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
                        
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                $plot_options = [];
                                foreach ($plots as $plot_item) {
                                    $plot_options[] = ['value' => $plot_item['id'], 'label' => $plot_item['plot_number'] . ' - ' . $plot_item['project_name']];
                                }
                                echo render_select('plot_id', $plot_options, ['value', 'label'], 'realestate_plot_number', isset($booking) ? $booking->plot_id : '', ['required' => true]); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $client_options = [];
                                foreach ($clients as $client) {
                                    $client_options[] = ['value' => $client['userid'], 'label' => $client['company']];
                                }
                                echo render_select('customer_id', $client_options, ['value', 'label'], 'realestate_customer', isset($booking) ? $booking->customer_id : '', ['required' => true]); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_date_input('booking_date', 'realestate_booking_date', isset($booking) ? _d($booking->booking_date) : _d(date('Y-m-d')), ['required' => true]); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $staff_options = [];
                                foreach ($staff as $staff_member) {
                                    $staff_options[] = ['value' => $staff_member['staffid'], 'label' => $staff_member['firstname'] . ' ' . $staff_member['lastname']];
                                }
                                echo render_select('assigned_to', $staff_options, ['value', 'label'], 'realestate_assigned_to', isset($booking) ? $booking->assigned_to : ''); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('booking_amount', 'realestate_booking_amount', isset($booking) ? $booking->booking_amount : '', 'number', ['step' => '0.01', 'required' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('total_amount', 'realestate_total_amount', isset($booking) ? $booking->total_amount : '', 'number', ['step' => '0.01', 'required' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('paid_amount', 'realestate_paid_amount', isset($booking) ? $booking->paid_amount : '', 'number', ['step' => '0.01', 'required' => true]); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('payment_plan', 'realestate_payment_plan', isset($booking) ? $booking->payment_plan : ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $statuses = [
                                    ['value' => 'pending', 'label' => _l('realestate_status_pending')],
                                    ['value' => 'confirmed', 'label' => _l('realestate_status_confirmed')],
                                    ['value' => 'cancelled', 'label' => _l('realestate_status_cancelled')],
                                    ['value' => 'completed', 'label' => _l('realestate_status_completed')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], 'realestate_booking_status', isset($booking) ? $booking->status : 'pending'); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('notes', 'realestate_booking_notes', isset($booking) ? $booking->notes : ''); ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                            <a href="<?php echo admin_url('realestate/bookings'); ?>" class="btn btn-default"><?php echo _l('realestate_cancel'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
