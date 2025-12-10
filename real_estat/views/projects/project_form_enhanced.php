<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo $id ? _l('edit_project') : _l('new_project'); ?>
                        </h4>
                        <hr class="hr-panel-heading" />

                        <!-- Progress Steps -->
                        <div class="progress-steps-container" style="margin-bottom: 30px;">
                            <ul class="list-unstyled" style="display: flex; justify-content: space-between;">
                                <li data-step="1" class="step-item active" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #007bff; color: white; line-height: 30px;">1</span>
                                    <div style="font-size: 12px; margin-top: 5px;">Basic Info</div>
                                </li>
                                <li data-step="2" class="step-item" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #e0e0e0; color: #666; line-height: 30px;">2</span>
                                    <div style="font-size: 12px; margin-top: 5px;">Location</div>
                                </li>
                                <li data-step="3" class="step-item" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #e0e0e0; color: #666; line-height: 30px;">3</span>
                                    <div style="font-size: 12px; margin-top: 5px;">Ownership</div>
                                </li>
                                <li data-step="4" class="step-item" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #e0e0e0; color: #666; line-height: 30px;">4</span>
                                    <div style="font-size: 12px; margin-top: 5px;">Approvals</div>
                                </li>
                                <li data-step="5" class="step-item" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #e0e0e0; color: #666; line-height: 30px;">5</span>
                                    <div style="font-size: 12px; margin-top: 5px;">EMI</div>
                                </li>
                                <li data-step="6" class="step-item" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #e0e0e0; color: #666; line-height: 30px;">6</span>
                                    <div style="font-size: 12px; margin-top: 5px;">Docs</div>
                                </li>
                                <li data-step="7" class="step-item" style="flex: 1; text-align: center;">
                                    <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: #e0e0e0; color: #666; line-height: 30px;">7</span>
                                    <div style="font-size: 12px; margin-top: 5px;">Survey</div>
                                </li>
                            </ul>
                        </div>

                        <form method="post" enctype="multipart/form-data" id="project-form">
                            <!-- Step 1: Basic Info -->
                            <div class="form-step" data-step="1" style="display: block;">
                                <h5><?php echo _l('basic_info'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label for="name"><?php echo _l('project_name'); ?> *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="<?php echo isset($project) ? $project->name : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="code"><?php echo _l('project_code'); ?></label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        value="<?php echo isset($project) ? $project->code : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="status"><?php echo _l('project_status'); ?></label>
                                    <select class="form-control selectpicker" id="status" name="status" data-live-search="true">
                                        <option value="draft" <?php echo (isset($project) && $project->status == 'draft') ? 'selected' : ''; ?>>
                                            <?php echo _l('status_draft'); ?>
                                        </option>
                                        <option value="active" <?php echo (isset($project) && $project->status == 'active') ? 'selected' : ''; ?>>
                                            <?php echo _l('status_active'); ?>
                                        </option>
                                        <option value="archived" <?php echo (isset($project) && $project->status == 'archived') ? 'selected' : ''; ?>>
                                            <?php echo _l('status_archived'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Step 2: Location -->
                            <div class="form-step" data-step="2" style="display: none;">
                                <h5><?php echo _l('location'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label for="district"><?php echo _l('district'); ?> *</label>
                                    <input type="text" class="form-control" id="district" name="district"
                                        value="<?php echo isset($project) ? $project->district : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="area"><?php echo _l('area'); ?></label>
                                    <input type="text" class="form-control" id="area" name="area"
                                        value="<?php echo isset($project) ? $project->area : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="village"><?php echo _l('village'); ?></label>
                                    <input type="text" class="form-control" id="village" name="village"
                                        value="<?php echo isset($project) ? $project->village : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="location_map"><?php echo _l('location_map'); ?></label>
                                    <input type="text" class="form-control" id="location_map" name="location_map"
                                        placeholder="https://maps.google.com/maps?q=..."
                                        value="<?php echo isset($project) ? $project->location_map : ''; ?>">
                                    <small class="form-text text-muted">Google Maps link or coordinates</small>
                                </div>

                                <div class="form-group">
                                    <label for="nearby"><?php echo _l('nearby_landmarks'); ?></label>
                                    <textarea class="form-control" id="nearby" name="nearby" rows="3"><?php echo isset($project) ? $project->nearby : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Step 3: Ownership & Pricing -->
                            <div class="form-step" data-step="3" style="display: none;">
                                <h5><?php echo _l('ownership'); ?></h5>
                                <hr />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_owners"><?php echo _l('total_owners'); ?></label>
                                            <input type="number" class="form-control" id="total_owners" name="total_owners"
                                                value="<?php echo isset($project) ? $project->total_owners : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" id="has_power_of_attorney" name="has_power_of_attorney"
                                                    <?php echo (isset($project) && $project->has_power_of_attorney) ? 'checked' : ''; ?>>
                                                <?php echo _l('power_of_attorney'); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <h5 style="margin-top: 20px;"><?php echo _l('land_details'); ?></h5>
                                <hr />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_acres"><?php echo _l('total_acres'); ?> *</label>
                                            <input type="number" step="0.01" class="form-control" id="total_acres" name="total_acres"
                                                value="<?php echo isset($project) ? $project->total_acres : ''; ?>" required>
                                            <small class="form-text text-muted"><?php echo _l('sqft_from_acres'); ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_sqft"><?php echo _l('total_sqft'); ?></label>
                                            <input type="number" step="0.01" class="form-control" id="total_sqft" name="total_sqft"
                                                value="<?php echo isset($project) ? $project->total_sqft : ''; ?>" readonly>
                                            <small class="form-text text-muted">Auto-calculated</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="approved_sqft"><?php echo _l('approved_sqft'); ?></label>
                                    <input type="number" step="0.01" class="form-control" id="approved_sqft" name="approved_sqft"
                                        value="<?php echo isset($project) ? $project->approved_sqft : ''; ?>">
                                </div>

                                <h5 style="margin-top: 20px;"><?php echo _l('pricing'); ?></h5>
                                <hr />

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="owners_price_per_sqft"><?php echo _l('owners_price_per_sqft'); ?></label>
                                            <input type="number" step="0.01" class="form-control" id="owners_price_per_sqft" 
                                                name="owners_price_per_sqft"
                                                value="<?php echo isset($project) ? $project->owners_price_per_sqft : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="min_selling_price_per_sqft"><?php echo _l('min_selling_price_per_sqft'); ?> *</label>
                                            <input type="number" step="0.01" class="form-control" id="min_selling_price_per_sqft"
                                                name="min_selling_price_per_sqft"
                                                value="<?php echo isset($project) ? $project->min_selling_price_per_sqft : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="max_selling_price_per_sqft"><?php echo _l('max_selling_price_per_sqft'); ?> *</label>
                                            <input type="number" step="0.01" class="form-control" id="max_selling_price_per_sqft"
                                                name="max_selling_price_per_sqft"
                                                value="<?php echo isset($project) ? $project->max_selling_price_per_sqft : ''; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <h5 style="margin-top: 20px;"><?php echo _l('commission_settings'); ?></h5>
                                <hr />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="team_commission_type"><?php echo _l('team_commission_type'); ?></label>
                                            <select class="form-control selectpicker" id="team_commission_type" name="team_commission_type" onchange="toggleCommissionInput()">
                                                <option value="percentage" <?php echo (isset($project) && $project->team_commission_type == 'percentage') ? 'selected' : ''; ?>>
                                                    <?php echo _l('commission_percentage'); ?>
                                                </option>
                                                <option value="slab" <?php echo (isset($project) && $project->team_commission_type == 'slab') ? 'selected' : ''; ?>>
                                                    <?php echo _l('commission_slab'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="percentage-input" style="<?php echo (isset($project) && $project->team_commission_type == 'slab') ? 'display:none;' : ''; ?>">
                                            <label for="team_commission_value"><?php echo _l('team_commission_value'); ?> (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="team_commission_value" 
                                                name="team_commission_value"
                                                value="<?php echo isset($project) ? $project->team_commission_value : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Approvals -->
                            <div class="form-step" data-step="4" style="display: none;">
                                <h5><?php echo _l('approvals'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label for="approval_types"><?php echo _l('approval_types'); ?></label>
                                    <input type="text" class="form-control" id="approval_types" name="approval_types"
                                        placeholder="e.g., Municipal, Environmental, Forest Clearance"
                                        value="<?php echo isset($project) ? $project->approval_types : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="approval_details"><?php echo _l('approval_details'); ?></label>
                                    <textarea class="form-control" id="approval_details" name="approval_details" rows="5"><?php echo isset($project) ? $project->approval_details : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Step 5: EMI Settings -->
                            <div class="form-step" data-step="5" style="display: none;">
                                <h5><?php echo _l('emi_settings'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" id="emi_enabled" name="emi_enabled"
                                            onchange="toggleEMIFields()"
                                            <?php echo (isset($project) && $project->emi_enabled) ? 'checked' : ''; ?>>
                                        <?php echo _l('emi_enabled'); ?>
                                    </label>
                                </div>

                                <div id="emi-fields" style="<?php echo (isset($project) && $project->emi_enabled) ? 'display:block;' : 'display:none;'; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emi_interest_type"><?php echo _l('emi_interest_type'); ?></label>
                                                <select class="form-control selectpicker" id="emi_interest_type" name="emi_interest_type">
                                                    <option value="none" <?php echo (isset($project) && $project->emi_interest_type == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo _l('emi_none'); ?>
                                                    </option>
                                                    <option value="flat" <?php echo (isset($project) && $project->emi_interest_type == 'flat') ? 'selected' : ''; ?>>
                                                        <?php echo _l('emi_flat'); ?>
                                                    </option>
                                                    <option value="reducing" <?php echo (isset($project) && $project->emi_interest_type == 'reducing') ? 'selected' : ''; ?>>
                                                        <?php echo _l('emi_reducing'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emi_interest_rate_annual"><?php echo _l('emi_interest_rate_annual'); ?> (%)</label>
                                                <input type="number" step="0.01" class="form-control" id="emi_interest_rate_annual"
                                                    name="emi_interest_rate_annual"
                                                    value="<?php echo isset($project) ? $project->emi_interest_rate_annual : ''; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emi_penalty_rate_annual"><?php echo _l('emi_penalty_rate_annual'); ?> (%)</label>
                                                <input type="number" step="0.01" class="form-control" id="emi_penalty_rate_annual"
                                                    name="emi_penalty_rate_annual"
                                                    value="<?php echo isset($project) ? $project->emi_penalty_rate_annual : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emi_grace_days"><?php echo _l('emi_grace_days'); ?></label>
                                                <input type="number" class="form-control" id="emi_grace_days" name="emi_grace_days"
                                                    value="<?php echo isset($project) ? $project->emi_grace_days : ''; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="emi_default_tenor_months"><?php echo _l('emi_default_tenor_months'); ?></label>
                                        <input type="number" class="form-control" id="emi_default_tenor_months" name="emi_default_tenor_months"
                                            value="<?php echo isset($project) ? $project->emi_default_tenor_months : ''; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 6: Documents -->
                            <div class="form-step" data-step="6" style="display: none;">
                                <h5><?php echo _l('project_documents'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label for="pr_document"><?php echo _l('pr_document'); ?></label>
                                    <input type="file" class="form-control" id="pr_document" name="pr_document">
                                    <?php if (isset($project) && !empty($project->pr_document)): ?>
                                        <small class="text-muted">Current: <?php echo basename($project->pr_document); ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="current_document"><?php echo _l('current_document'); ?></label>
                                    <input type="file" class="form-control" id="current_document" name="current_document">
                                    <?php if (isset($project) && !empty($project->current_document)): ?>
                                        <small class="text-muted">Current: <?php echo basename($project->current_document); ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="layout_plan_document"><?php echo _l('layout_plan_document'); ?></label>
                                    <input type="file" class="form-control" id="layout_plan_document" name="layout_plan_document">
                                    <?php if (isset($project) && !empty($project->layout_plan_document)): ?>
                                        <small class="text-muted">Current: <?php echo basename($project->layout_plan_document); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Step 7: Survey & Patta -->
                            <div class="form-step" data-step="7" style="display: none;">
                                <h5><?php echo _l('survey_details'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label for="survey_info"><?php echo _l('survey_info'); ?></label>
                                    <textarea class="form-control" id="survey_info" name="survey_info" rows="5"><?php echo isset($project) ? $project->survey_info : ''; ?></textarea>
                                </div>

                                <h5 style="margin-top: 20px;"><?php echo _l('patta_details'); ?></h5>
                                <hr />

                                <div class="form-group">
                                    <label for="patta_info"><?php echo _l('patta_info'); ?></label>
                                    <textarea class="form-control" id="patta_info" name="patta_info" rows="5"><?php echo isset($project) ? $project->patta_info : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px;">
                                <button type="button" class="btn btn-secondary" id="prev-btn" onclick="previousStep()" style="display: none;">
                                    <i class="fa fa-chevron-left"></i> <?php echo _l('previous_step'); ?>
                                </button>
                                <button type="button" class="btn btn-primary" id="next-btn" onclick="nextStep()">
                                    <?php echo _l('next_step'); ?> <i class="fa fa-chevron-right"></i>
                                </button>
                                <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                                    <i class="fa fa-save"></i> <?php echo $id ? _l('update') : _l('save'); ?>
                                </button>
                                <a href="<?php echo admin_url('real_estat/projects'); ?>" class="btn btn-default">
                                    <?php echo _l('cancel'); ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
let currentStep = 1;
const totalSteps = 7;

function showStep(step) {
    document.querySelectorAll('.form-step').forEach(el => el.style.display = 'none');
    document.querySelector(`.form-step[data-step="${step}"]`).style.display = 'block';

    // Update step indicators
    document.querySelectorAll('.step-item').forEach((el, i) => {
        const stepNum = i + 1;
        const indicator = el.querySelector('span');
        if (stepNum < step) {
            indicator.style.background = '#28a745';
            indicator.style.color = 'white';
        } else if (stepNum === step) {
            indicator.style.background = '#007bff';
            indicator.style.color = 'white';
        } else {
            indicator.style.background = '#e0e0e0';
            indicator.style.color = '#666';
        }
    });

    // Update buttons
    document.getElementById('prev-btn').style.display = step === 1 ? 'none' : 'inline-block';
    document.getElementById('next-btn').style.display = step === totalSteps ? 'none' : 'inline-block';
    document.getElementById('submit-btn').style.display = step === totalSteps ? 'inline-block' : 'none';
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
        window.scrollTo(0, 0);
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
        window.scrollTo(0, 0);
    }
}

function toggleEMIFields() {
    const enabled = document.getElementById('emi_enabled').checked;
    document.getElementById('emi-fields').style.display = enabled ? 'block' : 'none';
}

function toggleCommissionInput() {
    const type = document.getElementById('team_commission_type').value;
    document.getElementById('percentage-input').style.display = type === 'percentage' ? 'block' : 'none';
}

// Auto-calculate SqFt from Acres
document.getElementById('total_acres').addEventListener('change', function() {
    if (this.value) {
        const acres = parseFloat(this.value);
        const sqft = acres * 43560;
        document.getElementById('total_sqft').value = sqft.toFixed(2);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    showStep(currentStep);
    
    // Initialize selectpicker
    if (typeof $.fn.selectpicker === 'function') {
        $('.selectpicker').selectpicker('refresh');
    }
});
</script>

</body>
</html>
