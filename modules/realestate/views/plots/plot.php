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
                        
                        <?php echo form_open_multipart($this->uri->uri_string()); ?>
                        
                        <!-- Basic Information -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_basic_info'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <?php 
                                $project_options = [];
                                foreach ($projects as $project) {
                                    $project_options[] = ['value' => $project['id'], 'label' => $project['name']];
                                }
                                echo render_select('project_id', $project_options, ['value', 'label'], 'realestate_project_name', isset($plot) ? $plot->project_id : '', ['required' => true]); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('plot_number', 'realestate_plot_number', isset($plot) ? $plot->plot_number : '', 'text', ['required' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <?php 
                                $categories = [
                                    ['value' => 'premium', 'label' => _l('realestate_category_premium')],
                                    ['value' => 'standard', 'label' => _l('realestate_category_standard')],
                                    ['value' => 'economy', 'label' => _l('realestate_category_economy')],
                                ];
                                echo render_select('plot_category', $categories, ['value', 'label'], 'realestate_plot_category', isset($plot) ? $plot->plot_category : 'standard'); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('plot_size', 'realestate_plot_size', isset($plot) ? $plot->plot_size : '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('plot_type', 'realestate_plot_type', isset($plot) ? $plot->plot_type : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php 
                                $statuses = [
                                    ['value' => 'available', 'label' => _l('realestate_status_available')],
                                    ['value' => 'booked', 'label' => _l('realestate_status_booked')],
                                    ['value' => 'sold', 'label' => _l('realestate_status_sold')],
                                    ['value' => 'reserved', 'label' => _l('realestate_status_reserved')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], 'realestate_plot_status', isset($plot) ? $plot->status : 'available'); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('dimension', 'realestate_plot_dimension', isset($plot) ? $plot->dimension : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('facing', 'realestate_plot_facing', isset($plot) ? $plot->facing : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('road_width', 'realestate_road_width', isset($plot) ? $plot->road_width : ''); ?>
                            </div>
                        </div>
                        
                        <!-- Location Features -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_location_features'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="corner_plot" value="1" <?php echo (isset($plot) && $plot->corner_plot) ? 'checked' : ''; ?>>
                                        <?php echo _l('realestate_corner_plot'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="main_road_facing" value="1" <?php echo (isset($plot) && $plot->main_road_facing) ? 'checked' : ''; ?>>
                                        <?php echo _l('realestate_main_road_facing'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $road_access_options = [
                                    ['value' => 'main', 'label' => _l('realestate_road_main')],
                                    ['value' => 'internal', 'label' => _l('realestate_road_internal')],
                                    ['value' => 'corner', 'label' => _l('realestate_road_corner')],
                                ];
                                echo render_select('road_access', $road_access_options, ['value', 'label'], 'realestate_road_access', isset($plot) ? $plot->road_access : ''); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('latitude', 'realestate_latitude', isset($plot) ? $plot->latitude : ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('longitude', 'realestate_longitude', isset($plot) ? $plot->longitude : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('nearby_amenities', 'realestate_nearby_amenities', isset($plot) ? $plot->nearby_amenities : '', ['rows' => 2]); ?>
                            </div>
                        </div>
                        
                        <!-- Plot Specifications -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_plot_specifications'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('soil_type', 'realestate_soil_type', isset($plot) ? $plot->soil_type : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('elevation', 'realestate_elevation', isset($plot) ? $plot->elevation : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('drainage', 'realestate_drainage', isset($plot) ? $plot->drainage : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('corner_coordinates', 'realestate_corner_coordinates', isset($plot) ? $plot->corner_coordinates : '', ['rows' => 2, 'placeholder' => 'e.g., NE: 13.0827,80.2707; SE: 13.0826,80.2707; SW: 13.0826,80.2706; NW: 13.0827,80.2706']); ?>
                            </div>
                        </div>
                        
                        <!-- Utility Connections -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_utility_connections'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="water_connection" value="1" <?php echo (isset($plot) && $plot->water_connection) ? 'checked' : ''; ?>>
                                        <?php echo _l('realestate_water_connection'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="electricity_connection" value="1" <?php echo (isset($plot) && $plot->electricity_connection) ? 'checked' : ''; ?>>
                                        <?php echo _l('realestate_electricity_connection'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="sewage_connection" value="1" <?php echo (isset($plot) && $plot->sewage_connection) ? 'checked' : ''; ?>>
                                        <?php echo _l('realestate_sewage_connection'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing & Discount -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_pricing_discount'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo render_input('price', 'realestate_plot_price', isset($plot) ? $plot->price : '', 'number', ['step' => '0.01', 'id' => 'base_price']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('price_per_sqft', 'realestate_price_per_sqft', isset($plot) ? $plot->price_per_sqft : '', 'number', ['step' => '0.01', 'readonly' => true]); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('discount_percentage', 'realestate_discount_percentage', isset($plot) ? $plot->discount_percentage : '', 'number', ['step' => '0.01', 'max' => '100', 'id' => 'discount_pct']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('discount_amount', 'realestate_discount_amount', isset($plot) ? $plot->discount_amount : '', 'number', ['step' => '0.01', 'id' => 'discount_amt']); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('final_price', 'realestate_final_price', isset($plot) ? $plot->final_price : '', 'number', ['step' => '0.01', 'readonly' => true, 'id' => 'final_price']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('token_amount', 'realestate_token_amount', isset($plot) ? $plot->token_amount : '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_datetime_input('reservation_expiry', 'realestate_reservation_expiry', isset($plot) ? $plot->reservation_expiry : ''); ?>
                            </div>
                        </div>
                        
                        <!-- Plot Map Upload -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_plot_map'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="plot_map_image"><?php echo _l('realestate_plot_map_image'); ?></label>
                                    <input type="file" name="plot_map_image" class="form-control" accept="image/*">
                                    <?php if (isset($plot) && !empty($plot->plot_map_image)) { ?>
                                        <p class="mtop10"><small>Current: <?php echo $plot->plot_map_image; ?></small></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('description', 'realestate_plot_description', isset($plot) ? $plot->description : ''); ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                            <a href="<?php echo admin_url('realestate/plots'); ?>" class="btn btn-default"><?php echo _l('realestate_cancel'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                        
                        <?php if (!empty($plot_id) && $plot_id != '') { ?>
                        <!-- Price History & Waiting List Tabs -->
                        <hr class="mtop30" />
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#price_history_tab" aria-controls="price_history_tab" role="tab" data-toggle="tab">
                                    <i class="fa fa-line-chart"></i> <?php echo _l('realestate_price_history'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#waiting_list_tab" aria-controls="waiting_list_tab" role="tab" data-toggle="tab">
                                    <i class="fa fa-list"></i> <?php echo _l('realestate_waiting_list'); ?>
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content mtop20">
                            <!-- Price History Tab -->
                            <div role="tabpanel" class="tab-pane active" id="price_history_tab">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_old_price'); ?></th>
                                            <th><?php echo _l('realestate_new_price'); ?></th>
                                            <th><?php echo _l('realestate_changed_by'); ?></th>
                                            <th><?php echo _l('realestate_change_date'); ?></th>
                                            <th><?php echo _l('realestate_notes'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($price_history)) { ?>
                                            <?php foreach ($price_history as $history) { ?>
                                                <tr>
                                                    <td><?php echo app_format_money($history['old_price'], ''); ?></td>
                                                    <td><?php echo app_format_money($history['new_price'], ''); ?></td>
                                                    <td><?php echo $history['changed_by_name']; ?></td>
                                                    <td><?php echo _dt($history['change_date']); ?></td>
                                                    <td><?php echo $history['notes']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="5" class="text-center"><?php echo _l('realestate_no_records'); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Waiting List Tab -->
                            <div role="tabpanel" class="tab-pane" id="waiting_list_tab">
                                <button type="button" class="btn btn-primary btn-sm pull-right mtop10" onclick="$('#waiting_list_modal').modal('show')">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_to_waiting_list'); ?>
                                </button>
                                <div class="clearfix"></div>
                                <table class="table table-bordered mtop15">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_priority'); ?></th>
                                            <th><?php echo _l('realestate_customer'); ?></th>
                                            <th><?php echo _l('realestate_notes'); ?></th>
                                            <th><?php echo _l('realestate_date_created'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($waiting_list)) { ?>
                                            <?php foreach ($waiting_list as $waiting) { ?>
                                                <tr>
                                                    <td><?php echo $waiting['priority']; ?></td>
                                                    <td><?php echo $waiting['customer_name']; ?></td>
                                                    <td><?php echo $waiting['notes']; ?></td>
                                                    <td><?php echo _dt($waiting['added_date']); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="4" class="text-center"><?php echo _l('realestate_no_records'); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Waiting List Modal -->
<div class="modal fade" id="waiting_list_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title"><?php echo _l('realestate_add_to_waiting_list'); ?></h4>
            </div>
            <form id="waiting_list_form">
                <div class="modal-body">
                    <input type="hidden" name="plot_id" value="<?php echo isset($plot_id) ? $plot_id : ''; ?>">
                    <div class="form-group">
                        <label for="customer_id"><?php echo _l('realestate_customer'); ?> *</label>
                        <select name="customer_id" id="customer_id" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Customer</option>
                            <?php 
                            $this->load->model('clients_model');
                            $clients = $this->clients_model->get();
                            foreach ($clients as $client) {
                                echo '<option value="' . $client['userid'] . '">' . $client['company'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="waiting_notes"><?php echo _l('realestate_notes'); ?></label>
                        <textarea name="notes" id="waiting_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('realestate_cancel'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
// Auto-calculate price per sq.ft
$('#plot_size, #base_price').on('input', function() {
    var size = parseFloat($('#plot_size').val()) || 0;
    var price = parseFloat($('#base_price').val()) || 0;
    if (size > 0 && price > 0) {
        $('input[name="price_per_sqft"]').val((price / size).toFixed(2));
    }
});

// Auto-calculate final price with discount
function calculateFinalPrice() {
    var price = parseFloat($('#base_price').val()) || 0;
    var discountPct = parseFloat($('#discount_pct').val()) || 0;
    var discountAmt = parseFloat($('#discount_amt').val()) || 0;
    
    var totalDiscount = discountAmt + (price * discountPct / 100);
    var finalPrice = price - totalDiscount;
    
    $('#final_price').val(finalPrice.toFixed(2));
}

$('#base_price, #discount_pct, #discount_amt').on('input', calculateFinalPrice);

// Waiting list form submission
$('#waiting_list_form').on('submit', function(e) {
    e.preventDefault();
    $.post('<?php echo admin_url('realestate/plots/add_to_waiting_list'); ?>', $(this).serialize(), function(response) {
        if (response.success) {
            alert_float('success', response.message);
            $('#waiting_list_modal').modal('hide');
            location.reload();
        } else {
            alert_float('danger', response.message);
        }
    });
});
</script>
</body>
</html>
