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
                        
                        <!-- Filter Form -->
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?php 
                                $project_options = [['value' => '', 'label' => 'All Projects']];
                                foreach ($projects as $project) {
                                    $project_options[] = ['value' => $project['id'], 'label' => $project['name']];
                                }
                                echo render_select('project_id', $project_options, ['value', 'label'], 'realestate_project_name'); 
                                ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo render_input('min_price', 'realestate_min_price', '', 'number', ['step' => '0.01', 'placeholder' => 'Min']); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo render_input('max_price', 'realestate_max_price', '', 'number', ['step' => '0.01', 'placeholder' => 'Max']); ?>
                            </div>
                            <div class="col-md-2">
                                <?php 
                                $statuses = [
                                    ['value' => '', 'label' => 'All Status'],
                                    ['value' => 'available', 'label' => _l('realestate_status_available')],
                                    ['value' => 'reserved', 'label' => _l('realestate_status_reserved')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], 'realestate_plot_status'); 
                                ?>
                            </div>
                            <div class="col-md-2">
                                <?php 
                                $categories = [
                                    ['value' => '', 'label' => 'All Categories'],
                                    ['value' => 'premium', 'label' => _l('realestate_category_premium')],
                                    ['value' => 'standard', 'label' => _l('realestate_category_standard')],
                                    ['value' => 'economy', 'label' => _l('realestate_category_economy')],
                                ];
                                echo render_select('plot_category', $categories, ['value', 'label'], 'realestate_plot_category'); 
                                ?>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-info btn-block"><?php echo _l('realestate_apply_filter'); ?></button>
                            </div>
                        </div>
                        
                        <div class="row mtop10">
                            <div class="col-md-12">
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" name="corner_plot" value="1">
                                        <?php echo _l('realestate_corner_plot'); ?>
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" name="main_road_facing" value="1">
                                        <?php echo _l('realestate_main_road_facing'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        
                        <!-- Results -->
                        <?php if (isset($plots) && !empty($plots)) { ?>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('realestate_project_name'); ?></th>
                                        <th><?php echo _l('realestate_plot_number'); ?></th>
                                        <th><?php echo _l('realestate_plot_size'); ?></th>
                                        <th><?php echo _l('realestate_plot_category'); ?></th>
                                        <th><?php echo _l('realestate_facing'); ?></th>
                                        <th><?php echo _l('realestate_price'); ?></th>
                                        <th><?php echo _l('realestate_price_per_sqft'); ?></th>
                                        <th><?php echo _l('realestate_plot_status'); ?></th>
                                        <th><?php echo _l('realestate_location_features'); ?></th>
                                        <th><?php echo _l('realestate_actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plots as $plot) { ?>
                                        <tr>
                                            <td><?php echo $plot['project_name']; ?></td>
                                            <td><?php echo $plot['plot_number']; ?></td>
                                            <td><?php echo $plot['plot_size']; ?> sq.ft</td>
                                            <td><?php echo ucfirst($plot['plot_category']); ?></td>
                                            <td><?php echo $plot['facing']; ?></td>
                                            <td><?php echo app_format_money($plot['price'], ''); ?></td>
                                            <td><?php echo app_format_money($plot['price_per_sqft'], ''); ?></td>
                                            <td>
                                                <?php 
                                                $status_class = [
                                                    'available' => 'success',
                                                    'reserved' => 'warning',
                                                    'booked' => 'info',
                                                    'sold' => 'danger'
                                                ];
                                                $class = isset($status_class[$plot['status']]) ? $status_class[$plot['status']] : 'default';
                                                ?>
                                                <span class="label label-<?php echo $class; ?>"><?php echo ucfirst($plot['status']); ?></span>
                                            </td>
                                            <td>
                                                <?php if ($plot['corner_plot']) echo '<span class="label label-info">Corner</span> '; ?>
                                                <?php if ($plot['main_road_facing']) echo '<span class="label label-success">Main Road</span>'; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo admin_url('realestate/plots/plot/' . $plot['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
