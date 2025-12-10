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
                                $booking_options = [];
                                foreach ($bookings as $booking_item) {
                                    $booking_options[] = ['value' => $booking_item['id'], 'label' => $booking_item['customer_name'] . ' - ' . $booking_item['plot_number']];
                                }
                                echo render_select('booking_id', $booking_options, ['value', 'label'], 'realestate_booking', isset($selected_booking) ? $selected_booking : '', ['required' => true]); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_date_input('transaction_date', 'realestate_transaction_date', _d(date('Y-m-d')), ['required' => true]); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('amount', 'realestate_transaction_amount', '', 'number', ['step' => '0.01', 'required' => true]); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $payment_modes = [
                                    ['value' => 'cash', 'label' => _l('realestate_payment_cash')],
                                    ['value' => 'cheque', 'label' => _l('realestate_payment_cheque')],
                                    ['value' => 'bank_transfer', 'label' => _l('realestate_payment_bank_transfer')],
                                    ['value' => 'online', 'label' => _l('realestate_payment_online')],
                                ];
                                echo render_select('payment_mode', $payment_modes, ['value', 'label'], 'realestate_payment_mode', '', ['required' => true]); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('reference_number', 'realestate_reference_number', ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $transaction_types = [
                                    ['value' => 'payment', 'label' => _l('realestate_payment')],
                                    ['value' => 'refund', 'label' => _l('realestate_refund')],
                                ];
                                echo render_select('transaction_type', $transaction_types, ['value', 'label'], 'realestate_transaction_type', 'payment'); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('notes', 'realestate_transaction_notes', ''); ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                            <a href="<?php echo admin_url('realestate/accounts'); ?>" class="btn btn-default"><?php echo _l('realestate_cancel'); ?></a>
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
