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
                                $project_options = [];
                                foreach ($projects as $project) {
                                    $project_options[] = ['value' => $project['id'], 'label' => $project['name']];
                                }
                                echo render_select('project_id', $project_options, ['value', 'label'], 'realestate_project_name', '', ['required' => true]); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('prefix', 'realestate_plot_prefix', 'PLOT-', 'text', ['required' => true]); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('start_number', 'realestate_start_number', '1', 'number', ['required' => true, 'min' => '1']); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('count', 'realestate_number_of_plots', '10', 'number', ['required' => true, 'min' => '1', 'max' => '1000']); ?>
                            </div>
                        </div>
                        
                        <h4 class="bold mtop20"><?php echo _l('realestate_default_settings'); ?></h4>
                        <hr />
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('default_plot_size', 'realestate_plot_size', '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('default_plot_type', 'realestate_plot_type', ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('default_price', 'realestate_plot_price', '', 'number', ['step' => '0.01']); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                $statuses = [
                                    ['value' => 'available', 'label' => _l('realestate_status_available')],
                                    ['value' => 'reserved', 'label' => _l('realestate_status_reserved')],
                                ];
                                echo render_select('default_status', $statuses, ['value', 'label'], 'realestate_plot_status', 'available'); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $categories = [
                                    ['value' => 'premium', 'label' => _l('realestate_category_premium')],
                                    ['value' => 'standard', 'label' => _l('realestate_category_standard')],
                                    ['value' => 'economy', 'label' => _l('realestate_category_economy')],
                                ];
                                echo render_select('default_category', $categories, ['value', 'label'], 'realestate_plot_category', 'standard'); 
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
</body>
</html>
