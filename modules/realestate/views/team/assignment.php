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
                                $staff_options = [];
                                foreach ($staff as $staff_member) {
                                    $staff_options[] = ['value' => $staff_member['staffid'], 'label' => $staff_member['firstname'] . ' ' . $staff_member['lastname']];
                                }
                                echo render_select('staff_id', $staff_options, ['value', 'label'], 'realestate_staff_member', isset($assignment) ? $assignment->staff_id : '', ['required' => true]); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $project_options = [['value' => '', 'label' => _l('realestate_no_project')]];
                                foreach ($projects as $project) {
                                    $project_options[] = ['value' => $project['id'], 'label' => $project['name']];
                                }
                                echo render_select('project_id', $project_options, ['value', 'label'], 'realestate_project_name', isset($assignment) ? $assignment->project_id : ''); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                $role_options = [
                                    ['value' => 'manager', 'label' => _l('realestate_role_manager')],
                                    ['value' => 'sales', 'label' => _l('realestate_role_sales')],
                                    ['value' => 'supervisor', 'label' => _l('realestate_role_supervisor')],
                                ];
                                echo render_select('role', $role_options, ['value', 'label'], 'realestate_role', isset($assignment) ? $assignment->role : ''); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_date_input('assigned_date', 'realestate_assigned_date', isset($assignment) ? _d($assignment->assigned_date) : _d(date('Y-m-d')), ['required' => true]); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                $statuses = [
                                    ['value' => 'active', 'label' => _l('realestate_active')],
                                    ['value' => 'inactive', 'label' => _l('realestate_inactive')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], 'realestate_team_status', isset($assignment) ? $assignment->status : 'active'); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('notes', 'realestate_team_notes', isset($assignment) ? $assignment->notes : ''); ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('realestate_save'); ?></button>
                            <a href="<?php echo admin_url('realestate/team'); ?>" class="btn btn-default"><?php echo _l('realestate_cancel'); ?></a>
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
