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
                                echo render_select('project_id', $project_options, ['value', 'label'], 'realestate_project_name', isset($plot) ? $plot->project_id : '', ['required' => true]); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('plot_number', 'realestate_plot_number', isset($plot) ? $plot->plot_number : '', 'text', ['required' => true]); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('plot_size', 'realestate_plot_size', isset($plot) ? $plot->plot_size : ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('plot_type', 'realestate_plot_type', isset($plot) ? $plot->plot_type : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('price', 'realestate_plot_price', isset($plot) ? $plot->price : '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <?php echo render_input('dimension', 'realestate_plot_dimension', isset($plot) ? $plot->dimension : ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('facing', 'realestate_plot_facing', isset($plot) ? $plot->facing : ''); ?>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
