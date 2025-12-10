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
                                <?php echo render_input('name', 'realestate_project_name', isset($project) ? $project->name : '', 'text', ['required' => true]); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('location', 'realestate_project_location', isset($project) ? $project->location : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('project_type', 'realestate_project_type', isset($project) ? $project->project_type : ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $statuses = [
                                    ['value' => 'active', 'label' => _l('realestate_active')],
                                    ['value' => 'inactive', 'label' => _l('realestate_inactive')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], 'realestate_project_status', isset($project) ? $project->status : 'active'); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_date_input('start_date', 'realestate_start_date', isset($project) ? _d($project->start_date) : ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_date_input('end_date', 'realestate_end_date', isset($project) ? _d($project->end_date) : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('description', 'realestate_project_description', isset($project) ? $project->description : ''); ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                            <a href="<?php echo admin_url('realestate/projects'); ?>" class="btn btn-default"><?php echo _l('realestate_cancel'); ?></a>
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
