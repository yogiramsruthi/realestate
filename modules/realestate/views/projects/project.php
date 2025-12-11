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
                        
                        <!-- Basic Information -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_basic_info'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('name', 'realestate_project_name', isset($project) ? $project->name : '', 'text', ['required' => true]); ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_code"><?php echo _l('realestate_project_code'); ?></label>
                                    <div class="input-group">
                                        <?php echo render_input('project_code', '', isset($project) ? $project->project_code : '', 'text', ['readonly' => true], [], 'no-margin'); ?>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info" id="generate_code_btn" onclick="generateProjectCode()">
                                                <i class="fa fa-refresh"></i> <?php echo _l('realestate_generate_code'); ?>
                                            </button>
                                        </span>
                                    </div>
                                    <input type="hidden" name="project_short_name" id="project_short_name" value="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php 
                                $statuses = [
                                    ['value' => 'draft', 'label' => _l('realestate_status_draft')],
                                    ['value' => 'active', 'label' => _l('realestate_status_active')],
                                    ['value' => 'archived', 'label' => _l('realestate_status_archived')],
                                ];
                                echo render_select('status', $statuses, ['value', 'label'], 'realestate_project_status', isset($project) ? $project->status : 'draft'); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php 
                                $manager_options = [];
                                foreach ($staff as $staff_member) {
                                    $manager_options[] = ['value' => $staff_member['staffid'], 'label' => $staff_member['firstname'] . ' ' . $staff_member['lastname']];
                                }
                                echo render_select('project_manager', $manager_options, ['value', 'label'], 'realestate_project_manager', isset($project) ? $project->project_manager : ''); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('project_type', 'realestate_project_type', isset($project) ? $project->project_type : ''); ?>
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
                        
                        <!-- Location Details -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_location_details'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('district', 'realestate_district', isset($project) ? $project->district : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('area_taluk', 'realestate_area_taluk', isset($project) ? $project->area_taluk : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('village', 'realestate_village', isset($project) ? $project->village : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('location_map_url', 'realestate_location_map_url', isset($project) ? $project->location_map_url : '', 'url', ['placeholder' => 'https://maps.google.com/...']); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('location', 'realestate_project_location', isset($project) ? $project->location : ''); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('nearby_landmarks', 'realestate_nearby_landmarks', isset($project) ? $project->nearby_landmarks : ''); ?>
                            </div>
                        </div>
                        
                        <!-- Ownership & Pricing -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_ownership_pricing'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('total_owners', 'realestate_total_owners', isset($project) ? $project->total_owners : '', 'number'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('power_of_attorney', 'realestate_power_of_attorney', isset($project) ? $project->power_of_attorney : ''); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('total_acres', 'realestate_total_acres', isset($project) ? $project->total_acres : '', 'number', ['step' => '0.01', 'id' => 'total_acres', 'onchange' => 'calculateTotalSqft()']); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('total_sqft', 'realestate_total_sqft', isset($project) ? $project->total_sqft : '', 'number', ['step' => '0.01', 'id' => 'total_sqft', 'readonly' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('total_approved_sqft', 'realestate_total_approved_sqft', isset($project) ? $project->total_approved_sqft : '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('owner_price_per_sqft', 'realestate_owner_price_per_sqft', isset($project) ? $project->owner_price_per_sqft : '', 'number', ['step' => '0.01']); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('min_selling_price_per_sqft', 'realestate_min_selling_price_per_sqft', isset($project) ? $project->min_selling_price_per_sqft : '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('max_selling_price_per_sqft', 'realestate_max_selling_price_per_sqft', isset($project) ? $project->max_selling_price_per_sqft : '', 'number', ['step' => '0.01']); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                $commission_types = [
                                    ['value' => 'fixed', 'label' => _l('realestate_commission_fixed')],
                                    ['value' => 'percentage', 'label' => _l('realestate_commission_percentage_type')],
                                    ['value' => 'tiered', 'label' => _l('realestate_commission_tiered')],
                                ];
                                echo render_select('commission_type', $commission_types, ['value', 'label'], 'realestate_commission_type', isset($project) ? $project->commission_type : ''); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('commission_percentage', 'realestate_commission_percentage', isset($project) ? $project->commission_percentage : '', 'number', ['step' => '0.01', 'min' => '0', 'max' => '100']); ?>
                            </div>
                        </div>
                        
                        <!-- Approvals & Compliance -->
                        <h4 class="bold mtop20"><?php echo _l('realestate_approvals_compliance'); ?></h4>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" id="dtcp_approval" name="dtcp_approval" value="1" <?php echo (isset($project) && $project->dtcp_approval) ? 'checked' : ''; ?>>
                                        <label for="dtcp_approval"><?php echo _l('realestate_dtcp_approval'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" id="rera_approval" name="rera_approval" value="1" <?php echo (isset($project) && $project->rera_approval) ? 'checked' : ''; ?>>
                                        <label for="rera_approval"><?php echo _l('realestate_rera_approval'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" id="bdo_approval" name="bdo_approval" value="1" <?php echo (isset($project) && $project->bdo_approval) ? 'checked' : ''; ?>>
                                        <label for="bdo_approval"><?php echo _l('realestate_bdo_approval'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" id="panchayath_78_go" name="panchayath_78_go" value="1" <?php echo (isset($project) && $project->panchayath_78_go) ? 'checked' : ''; ?>>
                                        <label for="panchayath_78_go"><?php echo _l('realestate_panchayath_78_go'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" id="farm_land" name="farm_land" value="1" <?php echo (isset($project) && $project->farm_land) ? 'checked' : ''; ?>>
                                        <label for="farm_land"><?php echo _l('realestate_farm_land'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" id="cmda_approval" name="cmda_approval" value="1" <?php echo (isset($project) && $project->cmda_approval) ? 'checked' : ''; ?>>
                                        <label for="cmda_approval"><?php echo _l('realestate_cmda_approval'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('other_approvals', 'realestate_other_approvals', isset($project) ? $project->other_approvals : '', ['rows' => 3]); ?>
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
<script>
// Constants
const SQFT_PER_ACRE = 43560;

function generateProjectCode() {
    var projectName = $('input[name="name"]').val();
    if (!projectName) {
        alert_float('warning', '<?php echo _l('realestate_project_name'); ?> is required');
        return;
    }
    
    // Extract short name (first 3 letters or specified)
    var shortName = prompt('Enter project short name (e.g., VVN):', projectName.substring(0, 3).toUpperCase());
    if (!shortName) return;
    
    $('#project_short_name').val(shortName);
    $('#generate_code_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating...');
    
    $.post('<?php echo admin_url('realestate/projects/generate_code'); ?>', {
        short_name: shortName
    }, function(response) {
        if (response.success) {
            $('input[name="project_code"]').val(response.project_code);
            alert_float('success', 'Project code generated successfully');
        } else {
            alert_float('danger', response.message || 'Failed to generate project code');
        }
    }).fail(function() {
        alert_float('danger', 'Failed to generate project code');
    }).always(function() {
        $('#generate_code_btn').prop('disabled', false).html('<i class="fa fa-refresh"></i> <?php echo _l('realestate_generate_code'); ?>');
    });
}

function calculateTotalSqft() {
    var totalAcres = parseFloat($('#total_acres').val()) || 0;
    var totalSqft = totalAcres * SQFT_PER_ACRE;
    $('#total_sqft').val(totalSqft.toFixed(2));
}
</script>
</body>
</html>
