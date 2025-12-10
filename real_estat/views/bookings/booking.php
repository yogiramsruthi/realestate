<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8">
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id"><?php echo _l('project_name'); ?> *</label>
                                    <select name="project_id" id="project_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required onchange="loadPlots(this.value)">
                                        <option value="">-- <?php echo _l('select'); ?> --</option>
                                        <?php if (isset($projects) && is_array($projects)) {
                                            foreach ($projects as $proj) { ?>
                                                <option value="<?php echo $proj['id']; ?>" <?php if (isset($booking) && $booking->id == $proj['id']) echo 'selected'; ?>>
                                                    <?php echo $proj['name']; ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id"><?php echo _l('customer'); ?> *</label>
                                    <select name="customer_id" id="customer_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required>
                                        <option value="">-- <?php echo _l('select'); ?> --</option>
                                        <?php if (isset($customers) && is_array($customers)) {
                                            foreach ($customers as $customer) { ?>
                                                <option value="<?php echo $customer['userid']; ?>" <?php if (isset($booking) && $booking->customer_id == $customer['userid']) echo 'selected'; ?>>
                                                    <?php echo $customer['company']; ?> (<?php echo $customer['email']; ?>)
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plot_id"><?php echo _l('plot_number'); ?> *</label>
                                    <select name="plot_id" id="plot_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required onchange="updatePlotPrice()">
                                        <option value="">-- <?php echo _l('select'); ?> --</option>
                                        <?php if (isset($booking)) {
                                            echo '<option value="' . $booking->plot_id . '" selected>' . $booking->plot_number . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="booking_date"><?php echo _l('booking_date'); ?> *</label>
                                    <input type="date" id="booking_date" name="booking_date" class="form-control" value="<?php echo isset($booking) ? $booking->booking_date : date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="booking_amount"><?php echo _l('booking_amount'); ?> *</label>
                                    <input type="number" id="booking_amount" name="booking_amount" class="form-control" value="<?php echo isset($booking) ? $booking->booking_amount : ''; ?>" required step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_amount"><?php echo _l('total_price'); ?> *</label>
                                    <input type="number" id="total_amount" name="total_amount" class="form-control" value="<?php echo isset($booking) ? $booking->total_amount : ''; ?>" required step="0.01" onchange="calculateFinalAmount()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount"><?php echo _l('discount'); ?></label>
                                    <input type="number" id="discount" name="discount" class="form-control" value="<?php echo isset($booking) ? $booking->discount : '0'; ?>" step="0.01" onchange="calculateFinalAmount()">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="final_amount"><?php echo _l('final_amount'); ?></label>
                                    <input type="number" id="final_amount" name="final_amount" class="form-control" value="<?php echo isset($booking) ? $booking->final_amount : ''; ?>" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_plan_id"><?php echo _l('payment_plan'); ?> *</label>
                                    <select name="payment_plan_id" id="payment_plan_id" class="form-control selectpicker" data-width="100%" required>
                                        <option value="">-- <?php echo _l('select'); ?> --</option>
                                        <?php if (isset($payment_plans) && is_array($payment_plans)) {
                                            foreach ($payment_plans as $plan) { ?>
                                                <option value="<?php echo $plan['id']; ?>" <?php if (isset($booking) && $booking->payment_plan_id == $plan['id']) echo 'selected'; ?>>
                                                    <?php echo $plan['name']; ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes"><?php echo _l('notes'); ?></label>
                                    <textarea id="notes" name="notes" class="form-control" rows="3"><?php echo isset($booking) ? $booking->notes : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            
            <div class="col-md-4">
                <?php if (isset($booking)) { ?>
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4><?php echo _l('booking_details'); ?></h4>
                            <hr />
                            
                            <p>
                                <strong><?php echo _l('booking_code'); ?>:</strong> 
                                <?php echo $booking->booking_code; ?>
                            </p>
                            
                            <p>
                                <strong><?php echo _l('booking_status'); ?>:</strong> 
                                <span class="label label-<?php 
                                    $status_class = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'converted_to_sale' => 'info'];
                                    echo isset($status_class[$booking->status]) ? $status_class[$booking->status] : 'default';
                                ?>">
                                    <?php echo _l($booking->status); ?>
                                </span>
                            </p>
                            
                            <p>
                                <strong><?php echo _l('booking_date'); ?>:</strong> 
                                <?php echo _d($booking->booking_date); ?>
                            </p>
                            
                            <hr />
                            <h5><?php echo _l('actions'); ?></h5>
                            
                            <?php if ($booking->status == 'pending') { ?>
                                <a href="<?php echo admin_url('real_estat/cancel_booking'); ?>" class="btn btn-warning btn-block" onclick="return confirm('<?php echo _l('are_you_sure'); ?>')">
                                    <i class="fa fa-times"></i> <?php echo _l('cancel_booking'); ?>
                                </a>
                            <?php } ?>
                            
                            <?php if ($booking->status == 'confirmed' || $booking->status == 'pending') { ?>
                                <a href="<?php echo admin_url('real_estat/convert_to_sale/' . $booking->id); ?>" class="btn btn-success btn-block">
                                    <i class="fa fa-check"></i> <?php echo _l('convert_to_sale'); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <?php if (isset($installments) && count($installments) > 0) { ?>
                        <div class="panel_s mtop20">
                            <div class="panel-body">
                                <h4><?php echo _l('installments'); ?></h4>
                                <hr />
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo _l('due_date'); ?></th>
                                            <th><?php echo _l('amount'); ?></th>
                                            <th><?php echo _l('status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($installments as $inst) { ?>
                                            <tr class="<?php echo $inst['status'] == 'paid' ? 'bg-success' : ($inst['status'] == 'overdue' ? 'bg-danger' : ''); ?>">
                                                <td><?php echo $inst['installment_number'] == 0 ? 'DP' : $inst['installment_number']; ?></td>
                                                <td><?php echo _d($inst['due_date']); ?></td>
                                                <td><?php echo app_format_money($inst['amount'], get_base_currency()); ?></td>
                                                <td>
                                                    <span class="label label-<?php echo $inst['status'] == 'paid' ? 'success' : 'warning'; ?>">
                                                        <?php echo _l($inst['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    function loadPlots(project_id) {
        if (!project_id) return;
        $.get('<?php echo admin_url('real_estat/get_available_plots/'); ?>' + project_id, function(plots) {
            var options = '<option value="">-- <?php echo _l('select'); ?> --</option>';
            $.each(plots, function(i, plot) {
                options += '<option value="' + plot.id + '" data-price="' + plot.total_price + '">' + 
                           plot.plot_number + ' - ' + plot.area + ' ' + plot.area_unit + '</option>';
            });
            $('#plot_id').html(options);
            $('#plot_id').selectpicker('refresh');
        });
    }
    
    function updatePlotPrice() {
        var price = $('#plot_id').find('option:selected').data('price');
        if (price) {
            $('#total_amount').val(price);
            calculateFinalAmount();
        }
    }
    
    function calculateFinalAmount() {
        var total = parseFloat($('#total_amount').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        var final = total - discount;
        $('#final_amount').val(final.toFixed(2));
    }
</script>
</body>
</html>
