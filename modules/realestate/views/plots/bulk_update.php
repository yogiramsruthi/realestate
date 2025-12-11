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
                        
                        <!-- Select Plots -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="30"><input type="checkbox" id="select_all"></th>
                                        <th><?php echo _l('realestate_project_name'); ?></th>
                                        <th><?php echo _l('realestate_plot_number'); ?></th>
                                        <th><?php echo _l('realestate_plot_size'); ?></th>
                                        <th><?php echo _l('realestate_plot_status'); ?></th>
                                        <th><?php echo _l('realestate_price'); ?></th>
                                        <th><?php echo _l('realestate_plot_category'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plots as $plot) { ?>
                                        <tr>
                                            <td><input type="checkbox" name="plot_ids[]" value="<?php echo $plot['id']; ?>" class="plot_checkbox"></td>
                                            <td><?php echo $plot['project_name']; ?></td>
                                            <td><?php echo $plot['plot_number']; ?></td>
                                            <td><?php echo $plot['plot_size']; ?></td>
                                            <td><?php echo ucfirst($plot['status']); ?></td>
                                            <td><?php echo app_format_money($plot['price'], ''); ?></td>
                                            <td><?php echo ucfirst($plot['plot_category']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <hr />
                        
                        <!-- Update Options -->
                        <h4><?php echo _l('realestate_bulk_update_plots'); ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="update_status" value="1" id="update_status_check">
                                        <?php echo _l('realestate_plot_status'); ?>
                                    </label>
                                </div>
                                <?php 
                                $statuses = [
                                    ['value' => 'available', 'label' => _l('realestate_status_available')],
                                    ['value' => 'reserved', 'label' => _l('realestate_status_reserved')],
                                    ['value' => 'booked', 'label' => _l('realestate_status_booked')],
                                    ['value' => 'sold', 'label' => _l('realestate_status_sold')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], '', '', ['disabled' => true]); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="update_price" value="1" id="update_price_check">
                                        <?php echo _l('realestate_price'); ?>
                                    </label>
                                </div>
                                <?php echo render_input('price', '', '', 'number', ['step' => '0.01', 'disabled' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="update_category" value="1" id="update_category_check">
                                        <?php echo _l('realestate_plot_category'); ?>
                                    </label>
                                </div>
                                <?php 
                                $categories = [
                                    ['value' => 'premium', 'label' => _l('realestate_category_premium')],
                                    ['value' => 'standard', 'label' => _l('realestate_category_standard')],
                                    ['value' => 'economy', 'label' => _l('realestate_category_economy')],
                                ];
                                echo render_select('plot_category', $categories, ['value', 'label'], '', '', ['disabled' => true]); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                            <a href="<?php echo admin_url('realestate/plots'); ?>" class="btn btn-default"><?php echo _l('realestate_cancel'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
// Select all checkbox
$('#select_all').on('change', function() {
    $('.plot_checkbox').prop('checked', $(this).prop('checked'));
});

// Enable/disable status field
$('#update_status_check').on('change', function() {
    $('select[name="status"]').prop('disabled', !$(this).prop('checked'));
    if ($(this).prop('checked')) {
        $('select[name="status"]').selectpicker('refresh');
    }
});

// Enable/disable price field
$('#update_price_check').on('change', function() {
    $('input[name="price"]').prop('disabled', !$(this).prop('checked'));
});

// Enable/disable category field
$('#update_category_check').on('change', function() {
    $('select[name="plot_category"]').prop('disabled', !$(this).prop('checked'));
    if ($(this).prop('checked')) {
        $('select[name="plot_category"]').selectpicker('refresh');
    }
});
</script>
</body>
</html>
