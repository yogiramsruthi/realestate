<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php echo form_open($this->uri->uri_string(), ['enctype' => 'multipart/form-data']); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <!-- Validation Errors Display -->
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong><?php echo _l('validation_errors'); ?>:</strong>
                                <?php echo validation_errors('<div>', '</div>'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <p class="text-muted"><strong><?php echo _l('real_estate_project_form'); ?></strong></p>
                        
                        <div class="section">
                            <h5 class="bold">Basic Info</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name"><?php echo _l('project_name'); ?> *</label>
                                        <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($project) ? $project->name : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">
                                            <?php echo _l('project_code'); ?>
                                            <span class="label label-success" id="code-auto-badge" style="margin-left: 8px; display:none;">
                                                <i class="fa fa-magic"></i> Auto-Generated
                                            </span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" id="code" name="code" class="form-control" value="<?php echo isset($project) ? $project->code : ''; ?>" placeholder="Auto-generate or enter manually">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success" onclick="generateProjectCode()" title="Auto-Generate Code">
                                                    <i class="fa fa-magic"></i> Generate
                                                </button>
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Generate Options">
                                                    <i class="fa fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="javascript:void(0);" onclick="generateProjectCode('format1')">
                                                        <i class="fa fa-code"></i> PRJ-YYYY-0001
                                                    </a></li>
                                                    <li><a href="javascript:void(0);" onclick="generateProjectCode('format2')">
                                                        <i class="fa fa-hashtag"></i> RE-YYYYMMDD-0001
                                                    </a></li>
                                                    <li><a href="javascript:void(0);" onclick="generateProjectCode('format3')">
                                                        <i class="fa fa-random"></i> RES-RANDOM6
                                                    </a></li>
                                                    <li><a href="javascript:void(0);" onclick="generateProjectCode('format4')">
                                                        <i class="fa fa-text-width"></i> NAME-YYYY-0001
                                                    </a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="javascript:void(0);" onclick="showCodeSettings()">
                                                        <i class="fa fa-wrench"></i> Custom Format...
                                                    </a></li>
                                                </ul>
                                            </span>
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fa fa-info-circle"></i> 
                                            <span id="code-format-hint">Click Generate or enter custom code</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status"><?php echo _l('project_status'); ?></label>
                                        <select name="status" id="status" class="form-control selectpicker" data-width="100%">
                                            <option value="draft" <?php if (isset($project) && $project->status == 'draft') echo 'selected'; ?>>Draft</option>
                                            <option value="active" <?php if (isset($project) && $project->status == 'active') echo 'selected'; ?>>Active</option>
                                            <option value="archived" <?php if (isset($project) && $project->status == 'archived') echo 'selected'; ?>>Archived</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="project_manager_id"><?php echo _l('project_manager'); ?></label>
                                        <select name="project_manager_id" id="project_manager_id" class="form-control selectpicker" data-width="100%" data-live-search="true">
                                            <option value=""><?php echo _l('select'); ?></option>
                                            <?php foreach ($staff as $member) { ?>
                                                <option value="<?php echo $member['staffid']; ?>" <?php if (isset($project) && $project->project_manager_id == $member['staffid']) echo 'selected'; ?>>
                                                    <?php echo $member['firstname'] . ' ' . $member['lastname']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_plots"><?php echo _l('total_plots'); ?></label>
                                        <input type="number" id="total_plots" name="total_plots" class="form-control" value="<?php echo isset($project) ? $project->total_plots : ''; ?>" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="section">
                            <h5 class="bold">Location Details</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <input type="text" id="district" name="district" class="form-control" value="<?php echo isset($project) ? $project->district : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="area">Area / Taluk</label>
                                        <input type="text" id="area" name="area" class="form-control" value="<?php echo isset($project) ? $project->area : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="village">Village</label>
                                        <input type="text" id="village" name="village" class="form-control" value="<?php echo isset($project) ? $project->village : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location_map">Location Map (Google URL)</label>
                                        <input type="text" id="location_map" name="location_map" class="form-control" value="<?php echo isset($project) ? $project->location_map : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nearby">Nearby Landmarks</label>
                                        <textarea id="nearby" name="nearby" class="form-control" rows="2"><?php echo isset($project) ? $project->nearby : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="section">
                            <h5 class="bold">Ownership Pricing</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_owners">Total No. of Owners</label>
                                        <input type="number" id="total_owners" name="total_owners" class="form-control" value="<?php echo isset($project) ? $project->total_owners : ''; ?>" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="poa_status">
                                            Power of Attorney
                                            <span class="label label-info" style="margin-left: 10px;">
                                                <i class="fa fa-info-circle"></i> Legal Auth
                                            </span>
                                        </label>
                                        <select name="poa_status" id="poa_status" class="form-control selectpicker" data-width="100%" onchange="togglePOASection()">
                                            <option value="none" <?php echo isset($project) && $project->poa_status == 'none' ? 'selected' : ''; ?>>No POA</option>
                                            <option value="general" <?php echo isset($project) && $project->poa_status == 'general' ? 'selected' : ''; ?>>General POA</option>
                                            <option value="special" <?php echo isset($project) && $project->poa_status == 'special' ? 'selected' : ''; ?>>Special POA</option>
                                            <option value="enduring" <?php echo isset($project) && $project->poa_status == 'enduring' ? 'selected' : ''; ?>>Enduring POA</option>
                                            <option value="pending" <?php echo isset($project) && $project->poa_status == 'pending' ? 'selected' : ''; ?>>POA Pending</option>
                                            <option value="expired" <?php echo isset($project) && $project->poa_status == 'expired' ? 'selected' : ''; ?>>POA Expired</option>
                                            <option value="revoked" <?php echo isset($project) && $project->poa_status == 'revoked' ? 'selected' : ''; ?>>POA Revoked</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            <i class="fa fa-question-circle"></i> 
                                            POA allows authorized representative to act on behalf of property owner.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_acres">
                                            Total Acres
                                            <i class="fa fa-map text-muted" style="margin-left: 5px;"></i>
                                            <a href="javascript:void(0);" onclick="showConversionTooltip()" style="margin-left: 5px;" title="View Conversion Chart">
                                                <i class="fa fa-question-circle text-info"></i>
                                            </a>
                                        </label>
                                        <input type="number" id="total_acres" name="total_acres" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->total_acres : ''; ?>" oninput="calculateSqftFromAcres()" placeholder="Enter acres">
                                        <small class="form-text text-muted">
                                            <i class="fa fa-info-circle"></i> 1 acre = 43,560 sq.ft
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_sqft">
                                            Total Sq.ft (Auto)
                                            <span class="label label-success" id="sqft-auto-badge" style="margin-left: 8px; display:none;">
                                                <i class="fa fa-check"></i> Calculated
                                            </span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="total_sqft" name="total_sqft" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->total_sqft : ''; ?>" readonly style="background-color: #f0f8ff; font-weight: 600;">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default" title="Recalculate" onclick="calculateSqftFromAcres()">
                                                    <i class="fa fa-refresh"></i>
                                                </button>
                                                <button type="button" class="btn btn-info" title="Convert Manually" onclick="toggleManualSqft()">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <small class="form-text text-muted" id="sqft-calculation-info" style="display:none;">
                                            <i class="fa fa-calculator"></i> <span id="sqft-formula-display"></span>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="approved_sqft">Total Approved Sq.ft</label>
                                        <input type="number" id="approved_sqft" name="approved_sqft" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->approved_sqft : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="owners_price_per_sqft">Owner Price / Sq.ft</label>
                                        <input type="number" id="owners_price_per_sqft" name="owners_price_per_sqft" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->owners_price_per_sqft : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="min_selling_price_per_sqft">Min Selling Price / Sq.ft</label>
                                        <input type="number" id="min_selling_price_per_sqft" name="min_selling_price_per_sqft" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->min_selling_price_per_sqft : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="max_selling_price_per_sqft">Max Selling Price / Sq.ft</label>
                                        <input type="number" id="max_selling_price_per_sqft" name="max_selling_price_per_sqft" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->max_selling_price_per_sqft : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="team_commission_type">Commission Type</label>
                                        <select name="team_commission_type" id="team_commission_type" class="form-control selectpicker" data-width="100%" onchange="toggleCommissionDisplay()">
                                            <option value="percentage" <?php echo isset($project) && $project->team_commission_type == 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                                            <option value="slab" <?php echo isset($project) && $project->team_commission_type == 'slab' ? 'selected' : ''; ?>>Slab</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="percentage-input" style="display: <?php echo isset($project) && $project->team_commission_type == 'slab' ? 'none' : 'block'; ?>;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="team_commission_value">Commission %</label>
                                            <input type="number" id="team_commission_value" name="team_commission_value" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->team_commission_value : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="slab-input" style="display: <?php echo isset($project) && $project->team_commission_type == 'slab' ? 'block' : 'none'; ?>;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-sm btn-info" id="open-slab-editor-btn" onclick="openSlabEditor()">
                                            <i class="fa fa-edit"></i> <?php echo _l('edit_commission_slabs'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mtop10">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="team_commission_slab_json">Commission Slab JSON (Read-Only)</label>
                                            <textarea id="team_commission_slab_json" name="team_commission_slab_json" class="form-control" rows="3" readonly><?php echo isset($project) ? $project->team_commission_slab_json : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="section">
                            <h5 class="bold">
                                <i class="fa fa-check-square-o"></i> Approvals Compliance
                            </h5>
                            
                            <!-- Quick Templates -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <label>
                                        <i class="fa fa-magic"></i> Quick Templates
                                        <small class="text-muted">(Click to auto-select common approval combinations)</small>
                                    </label>
                                    <div class="btn-group btn-group-sm" role="group" style="display: flex; flex-wrap: wrap; gap: 5px;">
                                        <button type="button" class="btn btn-default" onclick="applyApprovalTemplate('dtcp_rera')" title="Standard Approved Layout">
                                            <i class="fa fa-home"></i> DTCP + RERA
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="applyApprovalTemplate('panchayat')" title="Panchayat Approved">
                                            <i class="fa fa-users"></i> Panchayat (GO Ms 78)
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="applyApprovalTemplate('cmda')" title="Chennai Region">
                                            <i class="fa fa-building"></i> CMDA Layout
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="applyApprovalTemplate('bda')" title="Bangalore Region">
                                            <i class="fa fa-institution"></i> BDA Layout
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="applyApprovalTemplate('patta')" title="Revenue Patta">
                                            <i class="fa fa-file-text"></i> Patta Layout
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="applyApprovalTemplate('farmland')" title="Agricultural Land">
                                            <i class="fa fa-tree"></i> Farm Land
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="clearAllApprovals()" title="Clear Selection">
                                            <i class="fa fa-times"></i> Clear All
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Approval Type Checkboxes -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <i class="fa fa-certificate"></i> Approval Types
                                            <span class="label label-info" style="margin-left: 8px;">
                                                <i class="fa fa-hand-pointer-o"></i> Select all that apply
                                            </span>
                                        </label>
                                        
                                        <div style="padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 6px;">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="DTCP" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-building text-primary"></i> <strong>DTCP</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Town & Country Planning</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="RERA" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-home text-success"></i> <strong>RERA</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Real Estate Regulatory Authority</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="CMDA" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-map-marker text-info"></i> <strong>CMDA</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Chennai Metro Dev Authority</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="Panchayat" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-users text-warning"></i> <strong>Panchayat</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Local Panchayat (GO Ms 78)</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="BDA" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-institution text-primary"></i> <strong>BDA</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Bangalore Dev Authority</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="HMDA" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-city text-info"></i> <strong>HMDA</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Hyderabad Metro Dev</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="Revenue" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-file-text text-success"></i> <strong>Revenue Dept</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Revenue Department</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="Patta Layout" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-legal text-warning"></i> <strong>Patta Layout</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Revenue Patta</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="Farm Land" class="approval-checkbox" onchange="updateApprovalText()"> 
                                                            <i class="fa fa-tree text-success"></i> <strong>Farm Land</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Agricultural Land</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        <label style="font-weight: 500;">
                                                            <input type="checkbox" name="approval_types_check[]" value="Other" class="approval-checkbox" id="approval-other-check" onchange="toggleOtherApproval(); updateApprovalText()"> 
                                                            <i class="fa fa-plus-circle text-muted"></i> <strong>Other</strong>
                                                            <small class="text-muted d-block" style="margin-left: 20px;">Custom Approval</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Other Approval Field -->
                                            <div id="other-approval-field" style="display:none; margin-top:15px;">
                                                <input type="text" id="other_approval_text" name="other_approval_text" class="form-control" placeholder="Specify other approval type..." oninput="updateApprovalText()">
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden field for form submission -->
                                        <input type="hidden" id="approval_types" name="approval_types" value="<?php echo isset($project) ? $project->approval_types : ''; ?>">
                                        
                                        <!-- Selected Summary -->
                                        <div style="margin-top: 10px; padding: 10px; background: #e3f2fd; border-left: 4px solid #0088cc; border-radius: 4px;" id="approval-summary">
                                            <strong><i class="fa fa-list"></i> Selected Approvals:</strong> 
                                            <span id="selected-approvals-text">None selected</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Approval Details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="approval_details">
                                            <i class="fa fa-info-circle"></i> Approval Details / Reference Numbers
                                        </label>
                                        <textarea id="approval_details" name="approval_details" class="form-control" rows="3" placeholder="Enter approval numbers, dates, and any important details...
Example: DTCP No. 123/2024, Valid till 31-Dec-2025
RERA Registration: ABC123456789"><?php echo isset($project) ? $project->approval_details : ''; ?></textarea>
                                        <small class="form-text text-muted">
                                            <i class="fa fa-lightbulb-o"></i> Include approval numbers, validity dates, and reference information
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="section">
                            <h5 class="bold">EMI Settings</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="emi_enabled"><?php echo _l('emi_available'); ?></label>
                                        <select name="emi_enabled" id="emi_enabled" class="form-control selectpicker" data-width="100%" onchange="toggleEMIFields()">
                                            <option value="0" <?php echo isset($project) && !$project->emi_enabled ? 'selected' : ''; ?>>No</option>
                                            <option value="1" <?php echo isset($project) && $project->emi_enabled ? 'selected' : ''; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="emi-fields-section" style="display: <?php echo isset($project) && $project->emi_enabled ? 'block' : 'none'; ?>;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="emi_interest_type">Interest Type</label>
                                            <select name="emi_interest_type" id="emi_interest_type" class="form-control selectpicker" data-width="100%">
                                                <option value="none" <?php echo isset($project) && $project->emi_interest_type == 'none' ? 'selected' : ''; ?>>None</option>
                                                <option value="flat" <?php echo isset($project) && $project->emi_interest_type == 'flat' ? 'selected' : ''; ?>>Flat</option>
                                                <option value="reducing" <?php echo isset($project) && $project->emi_interest_type == 'reducing' ? 'selected' : ''; ?>>Reducing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="emi_interest_rate_annual">Interest % (Annual)</label>
                                            <input type="number" id="emi_interest_rate_annual" name="emi_interest_rate_annual" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->emi_interest_rate_annual : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="emi_penalty_rate_annual">Penalty % (Annual)</label>
                                            <input type="number" id="emi_penalty_rate_annual" name="emi_penalty_rate_annual" class="form-control" step="0.01" value="<?php echo isset($project) ? $project->emi_penalty_rate_annual : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emi_grace_days">Grace Days</label>
                                            <input type="number" id="emi_grace_days" name="emi_grace_days" class="form-control" value="<?php echo isset($project) ? $project->emi_grace_days : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emi_default_tenor_months">Default Tenor (Months)</label>
                                            <input type="number" id="emi_default_tenor_months" name="emi_default_tenor_months" class="form-control" value="<?php echo isset($project) ? $project->emi_default_tenor_months : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <!-- POWER OF ATTORNEY SECTION -->
                        <div id="poa-section" style="display: none;">
                            <div class="section">
                                <h5 class="bold">
                                    <i class="fa fa-file-text"></i> Power of Attorney Details
                                    <span class="label label-success" id="poa-badge" style="display:none; margin-left:10px;">
                                        <i class="fa fa-check"></i> Active
                                    </span>
                                </h5>

                                <!-- POA Holder Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="poa_grantor_name">
                                                POA Grantor Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="poa_grantor_name" id="poa_grantor_name" class="form-control" placeholder="Name of property owner" value="<?php echo isset($project) ? $project->poa_grantor_name : ''; ?>">
                                            <small class="form-text text-muted">Person who grants the Power of Attorney</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="poa_attorney_name">
                                                POA Attorney/Holder Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="poa_attorney_name" id="poa_attorney_name" class="form-control" placeholder="Authorized representative name" value="<?php echo isset($project) ? $project->poa_attorney_name : ''; ?>">
                                            <small class="form-text text-muted">Person acting on behalf of property owner</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="poa_attorney_phone">POA Attorney Phone</label>
                                            <input type="tel" name="poa_attorney_phone" id="poa_attorney_phone" class="form-control" placeholder="Contact number" value="<?php echo isset($project) ? $project->poa_attorney_phone : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="poa_issue_date">Issue Date</label>
                                            <input type="date" name="poa_issue_date" id="poa_issue_date" class="form-control" value="<?php echo isset($project) && $project->poa_issue_date ? date('Y-m-d', strtotime($project->poa_issue_date)) : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="poa_expiry_date">Expiry Date (if applicable)</label>
                                            <input type="date" name="poa_expiry_date" id="poa_expiry_date" class="form-control" value="<?php echo isset($project) && $project->poa_expiry_date ? date('Y-m-d', strtotime($project->poa_expiry_date)) : ''; ?>">
                                            <small class="form-text text-muted">Leave blank if POA is permanent</small>
                                        </div>
                                    </div>
                                </div>

                                <hr style="margin: 25px 0;" />

                                <!-- POA Rights and Authorities -->
                                <h6 class="bold" style="margin-bottom:15px;">
                                    <i class="fa fa-shield"></i> POA Authorities & Rights
                                    <span class="label label-info" style="margin-left: 8px;">
                                        <i class="fa fa-hand-pointer-o"></i> Select all that apply
                                    </span>
                                </h6>
                                
                                <div style="padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 6px;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label style="font-weight: 500;">
                                                    <input type="checkbox" name="poa_authorities_check[]" value="sales" class="poa-checkbox" onchange="updatePOAText()"> 
                                                    <i class="fa fa-money text-success"></i> <strong>Sales Authority</strong>
                                                    <small class="text-muted d-block" style="margin-left: 20px;">Authority to sell property</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label style="font-weight: 500;">
                                                    <input type="checkbox" name="poa_authorities_check[]" value="financial" class="poa-checkbox" onchange="updatePOAText()"> 
                                                    <i class="fa fa-credit-card text-info"></i> <strong>Financial Authority</strong>
                                                    <small class="text-muted d-block" style="margin-left: 20px;">Financial transactions</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label style="font-weight: 500;">
                                                    <input type="checkbox" name="poa_authorities_check[]" value="legal" class="poa-checkbox" onchange="updatePOAText()"> 
                                                    <i class="fa fa-gavel text-warning"></i> <strong>Legal Authority</strong>
                                                    <small class="text-muted d-block" style="margin-left: 20px;">Legal proceedings</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label style="font-weight: 500;">
                                                    <input type="checkbox" name="poa_authorities_check[]" value="document_signing" class="poa-checkbox" onchange="updatePOAText()"> 
                                                    <i class="fa fa-pencil text-primary"></i> <strong>Document Signing</strong>
                                                    <small class="text-muted d-block" style="margin-left: 20px;">Sign documents</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label style="font-weight: 500;">
                                                    <input type="checkbox" name="poa_authorities_check[]" value="receipt" class="poa-checkbox" onchange="updatePOAText()"> 
                                                    <i class="fa fa-inbox text-danger"></i> <strong>Receipt Authority</strong>
                                                    <small class="text-muted d-block" style="margin-left: 20px;">Receive payments</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label style="font-weight: 500;">
                                                    <input type="checkbox" name="poa_authorities_check[]" value="full" class="poa-checkbox" id="poa-full-check" onchange="toggleFullAuthority(); updatePOAText()"> 
                                                    <i class="fa fa-star text-warning"></i> <strong>Full Authority</strong>
                                                    <small class="text-muted d-block" style="margin-left: 20px;">All authorities</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hidden fields for database submission -->
                                <input type="hidden" name="poa_sales_authority" value="<?php echo isset($project) ? $project->poa_sales_authority : 0; ?>">
                                <input type="hidden" name="poa_financial_authority" value="<?php echo isset($project) ? $project->poa_financial_authority : 0; ?>">
                                <input type="hidden" name="poa_legal_authority" value="<?php echo isset($project) ? $project->poa_legal_authority : 0; ?>">
                                <input type="hidden" name="poa_document_signing" value="<?php echo isset($project) ? $project->poa_document_signing : 0; ?>">
                                <input type="hidden" name="poa_receipt_authority" value="<?php echo isset($project) ? $project->poa_receipt_authority : 0; ?>">
                                <input type="hidden" name="poa_full_authority" value="<?php echo isset($project) ? $project->poa_full_authority : 0; ?>">
                                
                                <!-- Selected Summary -->
                                <div style="margin-top: 10px; padding: 10px; background: #e3f2fd; border-left: 4px solid #0088cc; border-radius: 4px;" id="poa-summary">
                                    <strong><i class="fa fa-list"></i> Selected Authorities:</strong> 
                                    <span id="selected-poa-text">None selected</span>
                                </div>

                                <hr style="margin: 25px 0;" />

                                <!-- POA Verification -->
                                <h6 class="bold" style="margin-bottom:15px;">POA Verification Status</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="poa_verification_status">Verification Status</label>
                                            <select name="poa_verification_status" id="poa_verification_status" class="form-control selectpicker" data-width="100%">
                                                <option value="not_verified" <?php echo isset($project) && $project->poa_verification_status == 'not_verified' ? 'selected' : ''; ?>>Not Verified</option>
                                                <option value="pending" <?php echo isset($project) && $project->poa_verification_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="verified" <?php echo isset($project) && $project->poa_verification_status == 'verified' ? 'selected' : ''; ?>>Verified</option>
                                                <option value="invalid" <?php echo isset($project) && $project->poa_verification_status == 'invalid' ? 'selected' : ''; ?>>Invalid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="poa_verified_date">Verification Date</label>
                                            <input type="date" name="poa_verified_date" id="poa_verified_date" class="form-control" value="<?php echo isset($project) && $project->poa_verified_date ? date('Y-m-d', strtotime($project->poa_verified_date)) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="poa_verified_by">Verified By</label>
                                            <input type="text" name="poa_verified_by" id="poa_verified_by" class="form-control" placeholder="Name or ID of verifier" value="<?php echo isset($project) ? $project->poa_verified_by : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <hr style="margin: 25px 0;" />

                                <!-- POA Document Upload -->
                                <h6 class="bold" style="margin-bottom:15px;">POA Document</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="poa_document_filename">POA Document Upload</label>
                                            <input type="file" name="poa_document_filename" id="poa_document_filename" class="form-control">
                                            <small class="form-text text-muted">Upload POA document (PDF, JPG, PNG - Max 5MB)</small>
                                            <?php if (isset($project) && $project->poa_document_filename) { ?>
                                                <div style="margin-top:10px;">
                                                    <i class="fa fa-check text-success"></i> 
                                                    <a href="javascript:void(0);" onclick="downloadPOADocument()">
                                                        View Current Document
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- POA Notes -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="poa_notes">Additional Notes</label>
                                            <textarea name="poa_notes" id="poa_notes" class="form-control" rows="3" placeholder="Any important notes about POA..."><?php echo isset($project) ? $project->poa_notes : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="section">
                            <h5 class="bold">Documents</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pr_document">PR Document</label>
                                        <input type="file" name="pr_document" id="pr_document" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_document">Current Document</label>
                                        <input type="file" name="current_document" id="current_document" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="layout_plan_document">Layout / Plan</label>
                                        <input type="file" name="layout_plan_document" id="layout_plan_document" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="section">
                            <h5 class="bold">Survey No & Patta Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="survey_info">Survey No. Information</label>
                                        <textarea id="survey_info" name="survey_info" class="form-control" rows="3"><?php echo isset($project) ? $project->survey_info : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patta_info">Patta Details</label>
                                        <textarea id="patta_info" name="patta_info" class="form-control" rows="3"><?php echo isset($project) ? $project->patta_info : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
                
                <?php if (isset($project)) { ?>
                    <!-- Project Statistics -->
                    <div class="panel_s mtop20">
                        <div class="panel-body">
                            <h4><?php echo _l('project_details'); ?></h4>
                            <hr />
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="text-center">
                                        <h3 class="bold text-info"><?php echo $stats['total_plots']; ?></h3>
                                        <p><?php echo _l('total_plots'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="text-center">
                                        <h3 class="bold text-success"><?php echo $stats['available_plots']; ?></h3>
                                        <p><?php echo _l('available'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="text-center">
                                        <h3 class="bold text-warning"><?php echo $stats['booked_plots']; ?></h3>
                                        <p><?php echo _l('booked'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="text-center">
                                        <h3 class="bold text-primary"><?php echo $stats['sold_plots']; ?></h3>
                                        <p><?php echo _l('sold'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mtop20">
                                <div class="col-md-12 text-center">
                                    <h4><?php echo _l('total_revenue'); ?>: <strong><?php echo app_format_money($stats['total_revenue'], get_base_currency()); ?></strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Plots Table -->
                    <div class="panel_s mtop20">
                        <div class="panel-body">
                            <div class="_buttons">
                                <a href="<?php echo admin_url('real_estat/plot?project_id=' . $project->id); ?>" class="btn btn-info pull-left">
                                    <i class="fa fa-plus"></i> <?php echo _l('new_plot'); ?>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <hr />
                            
                            <h4><?php echo _l('real_estate_plots'); ?></h4>
                            <table class="table dt-table">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('plot_number'); ?></th>
                                        <th><?php echo _l('plot_type'); ?></th>
                                        <th><?php echo _l('plot_area'); ?></th>
                                        <th><?php echo _l('rate_per_unit'); ?></th>
                                        <th><?php echo _l('total_price'); ?></th>
                                        <th><?php echo _l('plot_status'); ?></th>
                                        <th><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plots as $plot) { ?>
                                        <tr>
                                            <td><?php echo $plot['plot_number']; ?></td>
                                            <td><?php echo $plot['plot_type']; ?></td>
                                            <td><?php echo $plot['area'] . ' ' . $plot['area_unit']; ?></td>
                                            <td><?php echo app_format_money($plot['rate_per_unit'], get_base_currency()); ?></td>
                                            <td><?php echo app_format_money($plot['total_price'], get_base_currency()); ?></td>
                                            <td>
                                                <?php
                                                $status_class = [
                                                    'available' => 'success',
                                                    'booked' => 'warning',
                                                    'sold' => 'info',
                                                    'reserved' => 'default',
                                                    'blocked' => 'danger'
                                                ];
                                                ?>
                                                <span class="label label-<?php echo $status_class[$plot['status']]; ?>">
                                                    <?php echo _l($plot['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo admin_url('real_estat/plot/' . $plot['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?php echo admin_url('real_estat/delete_plot/' . $plot['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
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

<!-- Commission Slab Editor Modal -->
<div class="modal fade" id="slabEditorModal" tabindex="-1" role="dialog" aria-labelledby="slabEditorLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="slabEditorLabel"><?php echo _l('commission_slab_editor'); ?></h4>
            </div>
            <div class="modal-body">
                <?php $this->load->view('projects/commission_slab_editor'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="button" class="btn btn-primary" id="final-save-slabs" onclick="saveSlabsAndClose()">
                    <i class="fa fa-save"></i> <?php echo _l('save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
function toggleCommissionDisplay() {
    const type = document.getElementById('team_commission_type').value;
    const percentageInput = document.getElementById('percentage-input');
    const slabInput = document.getElementById('slab-input');
    
    if (type === 'percentage') {
        if (percentageInput) percentageInput.style.display = 'block';
        if (slabInput) slabInput.style.display = 'none';
    } else {
        if (percentageInput) percentageInput.style.display = 'none';
        if (slabInput) slabInput.style.display = 'block';
    }
}

function toggleEMIFields() {
    const emiDropdown = document.getElementById('emi_enabled');
    const emiFieldsSection = document.getElementById('emi-fields-section');
    
    if (emiFieldsSection && emiDropdown) {
        if (emiDropdown.value === '1') {
            emiFieldsSection.style.display = 'block';
        } else {
            emiFieldsSection.style.display = 'none';
        }
    }
}

function openSlabEditor() {
    $('#slabEditorModal').modal('show');
}

function saveSlabsAndClose() {
    if (window.saveSlabs && window.saveSlabs()) {
        $('#slabEditorModal').modal('hide');
    }
}

// Auto-calculate sqft from acres
document.addEventListener('DOMContentLoaded', function() {
    const acresInput = document.getElementById('total_acres');
    if (acresInput) {
        acresInput.addEventListener('change', function() {
            if (this.value) {
                const acres = parseFloat(this.value);
                const sqft = acres * 43560;
                const sqftInput = document.getElementById('total_sqft');
                if (sqftInput) {
                    sqftInput.value = sqft.toFixed(2);
                }
            }
        });
    }
    
    // Initialize commission display
    toggleCommissionDisplay();
    
    // Initialize EMI fields visibility
    toggleEMIFields();
});

$(document).ready(function() {
    // Use Perfex standard selectpicker initialization
    if (typeof init_selectpicker === 'function') {
        init_selectpicker();
    }
});
</script>

<style>
/* Form alignment and spacing improvements */
.panel-body {
    padding: 20px;
}

.section {
    margin-bottom: 20px;
}

.section h5 {
    margin-top: 10px;
    margin-bottom: 15px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 15px;
}

.row {
    margin-right: -15px;
    margin-left: -15px;
}

.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, 
.col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, 
.col-md-11, .col-md-12 {
    padding-right: 15px;
    padding-left: 15px;
}

/* Ensure proper alignment of form fields */
.form-control {
    width: 100%;
}

/* Improve textarea alignment */
textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Section separator spacing */
hr {
    margin-top: 25px;
    margin-bottom: 25px;
    border: none;
    border-top: 1px solid #ddd;
}

/* POA Section Styling */
#poa-section {
    padding: 20px;
    background-color: #f9f9f9;
    border-left: 4px solid #0088cc;
    border-radius: 4px;
}

#poa-section .checkbox {
    margin-top: 8px;
    margin-bottom: 8px;
}

#poa-section .checkbox label {
    padding-left: 25px;
    font-weight: 500;
    cursor: pointer;
}

#poa-section h6 {
    color: #333;
    margin-bottom: 15px;
}

#poa-badge {
    font-size: 11px;
    padding: 4px 8px;
}
</style>

<script>
// Toggle POA Section visibility
function togglePOASection() {
    try {
        const poaStatusSelect = document.getElementById('poa_status');
        if (!poaStatusSelect) return; // Element doesn't exist, skip
        
        const poaStatus = poaStatusSelect.value;
        const poaSection = document.getElementById('poa-section');
        const poaBadge = document.getElementById('poa-badge');
        
        if (!poaSection || !poaBadge) return; // Elements don't exist, skip
        
        if (poaStatus !== 'none') {
            poaSection.style.display = 'block';
            poaBadge.style.display = 'inline-block';
        } else {
            poaSection.style.display = 'none';
            poaBadge.style.display = 'none';
        }
    } catch (e) {
        console.error('Error in togglePOASection:', e);
    }
}

// ==========================================
// PROJECT CODE AUTO-GENERATION SYSTEM
// ==========================================

// Generate Project Code (Main Function)
function generateProjectCode(format = 'default') {
    const codeInput = document.getElementById('code');
    const badge = document.getElementById('code-auto-badge');
    const hint = document.getElementById('code-format-hint');
    
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    
    // For format3 (random), no need for AJAX
    if (format === 'format3') {
        const random = Math.random().toString(36).substring(2, 8).toUpperCase();
        const generatedCode = `RES-${random}`;
        codeInput.value = generatedCode;
        hint.textContent = 'Format: RES-RANDOM (Random 6-character code)';
        showCodeBadge();
        return;
    }
    
    // For format4 (name-based), check if name exists
    if (format === 'format4') {
        const projectName = document.getElementById('name').value.trim();
        if (!projectName) {
            alert('Please enter Project Name first to use this format');
            return;
        }
        
        const abbr = getAbbreviation(projectName);
        getNextSequence(format, abbr, function(sequence) {
            const generatedCode = `${abbr}-${year}-${sequence}`;
            codeInput.value = generatedCode;
            hint.textContent = 'Format: NAME-YYYY-SEQ (Project abbreviation with year and sequence)';
            showCodeBadge();
        });
        return;
    }
    
    // For other formats, get sequence from server
    let prefix = 'PRJ';
    let pattern = '';
    
    switch(format) {
        case 'format1':
            prefix = 'PRJ';
            pattern = 'PRJ-YYYY-SSSS';
            break;
        case 'format2':
            prefix = 'RE';
            pattern = 'RE-YYYYMMDD-SSSS';
            break;
        default:
            prefix = 'PRJ';
            pattern = 'PRJ-YYYYMMDD-SSSS';
    }
    
    getNextSequence(format, prefix, function(sequence) {
        let generatedCode = '';
        
        switch(format) {
            case 'format1':
                generatedCode = `PRJ-${year}-${sequence}`;
                hint.textContent = 'Format: PRJ-YYYY-SSSS (Project-Year-Sequence)';
                break;
            case 'format2':
                generatedCode = `RE-${year}${month}${day}-${sequence}`;
                hint.textContent = 'Format: RE-YYYYMMDD-SSSS (RealEstate-Date-Sequence)';
                break;
            default:
                const hour = String(now.getHours()).padStart(2, '0');
                const minute = String(now.getMinutes()).padStart(2, '0');
                const second = String(now.getSeconds()).padStart(2, '0');
                generatedCode = `PRJ-${year}${month}${day}-${sequence}`;
                hint.textContent = 'Format: PRJ-YYYYMMDD-SSSS (Date-based sequence)';
        }
        
        codeInput.value = generatedCode;
        showCodeBadge();
    });
}

// Show badge with animation
function showCodeBadge() {
    const codeInput = document.getElementById('code');
    const badge = document.getElementById('code-auto-badge');
    
    badge.style.display = 'inline-block';
    codeInput.style.backgroundColor = '#d4edda';
    codeInput.style.borderColor = '#28a745';
    
    setTimeout(() => {
        codeInput.style.backgroundColor = '#fff';
        codeInput.style.borderColor = '#ddd';
    }, 1500);
}

// Get next sequence number from server
function getNextSequence(format, prefix, callback) {
    $.ajax({
        url: admin_url + 'real_estat/real_estat/get_next_project_code',
        type: 'GET',
        data: { 
            format: format,
            prefix: prefix
        },
        success: function(response) {
            if (response.success) {
                callback(response.sequence);
            } else {
                // Fallback to client-side generation
                callback('0001');
            }
        },
        error: function() {
            // Fallback to client-side generation
            callback('0001');
        }
    });
}

// Get abbreviation from project name
function getAbbreviation(text) {
    // Remove special characters and split into words
    const words = text.toUpperCase()
        .replace(/[^A-Z0-9\s]/g, '')
        .split(/\s+/)
        .filter(w => w.length > 0);
    
    if (words.length === 0) return 'PRJ';
    
    if (words.length === 1) {
        // Single word: take first 3-4 letters
        return words[0].substring(0, Math.min(4, words[0].length));
    } else {
        // Multiple words: take first letter of each word
        return words.map(w => w[0]).join('').substring(0, 5);
    }
}

// Show custom code settings modal
function showCodeSettings() {
    const prefix = prompt('Enter custom prefix (e.g., PROJ, RE, ESTATE):', 'PRJ');
    if (!prefix) return;
    
    const now = new Date();
    const year = now.getFullYear();
    const hint = document.getElementById('code-format-hint');
    
    getNextSequence('custom', prefix.toUpperCase(), function(sequence) {
        const customCode = `${prefix.toUpperCase()}-${year}-${sequence}`;
        document.getElementById('code').value = customCode;
        document.getElementById('code-auto-badge').style.display = 'inline-block';
        hint.textContent = 'Custom format: ' + prefix.toUpperCase() + '-YYYY-SSSS';
        showCodeBadge();
    });
}

// Validate project code uniqueness (placeholder)
function validateProjectCode() {
    const code = document.getElementById('code').value.trim();
    
    if (!code) {
        return true; // Allow empty
    }
    
    // In production, make AJAX call to check uniqueness
    // For now, just validate format
    if (code.length < 3) {
        alert('Project code should be at least 3 characters');
        return false;
    }
    
    return true;
}

// Auto-generate on project name change (optional)
function autoGenerateFromName() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    // Only auto-generate if code is empty
    if (!codeInput.value.trim()) {
        nameInput.addEventListener('blur', function() {
            if (this.value.trim() && !codeInput.value.trim()) {
                generateProjectCode('format4');
            }
        });
    }
}

// ==========================================
// END PROJECT CODE SYSTEM
// ==========================================

// ==========================================
// APPROVAL TYPES MANAGEMENT SYSTEM
// ==========================================

// Update approval text field from checkboxes
function updateApprovalText() {
    const checkboxes = document.querySelectorAll('.approval-checkbox:checked');
    const selected = [];
    
    checkboxes.forEach(checkbox => {
        if (checkbox.value === 'Other') {
            const otherText = document.getElementById('other_approval_text');
            if (otherText && otherText.value.trim()) {
                selected.push(otherText.value.trim());
            }
        } else {
            selected.push(checkbox.value);
        }
    });
    
    // Update hidden field
    document.getElementById('approval_types').value = selected.join(', ');
    
    // Update summary text
    const summaryText = document.getElementById('selected-approvals-text');
    if (selected.length > 0) {
        summaryText.innerHTML = '<strong class="text-success">' + selected.join(', ') + '</strong>';
    } else {
        summaryText.innerHTML = '<span class="text-muted">None selected</span>';
    }
}

// Toggle Other approval text field
function toggleOtherApproval() {
    const checkbox = document.getElementById('approval-other-check');
    const field = document.getElementById('other-approval-field');
    
    if (checkbox.checked) {
        field.style.display = 'block';
        document.getElementById('other_approval_text').focus();
    } else {
        field.style.display = 'none';
        document.getElementById('other_approval_text').value = '';
    }
}

// ==========================================
// POA AUTHORITIES MANAGEMENT SYSTEM
// ==========================================

// Update POA authorities from checkboxes
function updatePOAText() {
    try {
        const checkboxes = document.querySelectorAll('.poa-checkbox:checked');
        if (!checkboxes) return;
        
        const selected = [];
        const values = [];
        
        checkboxes.forEach(checkbox => {
            selected.push(getPoaLabel(checkbox.value));
            values.push(checkbox.value === 'sales' ? 1 : checkbox.value === 'financial' ? 1 : checkbox.value === 'legal' ? 1 : checkbox.value === 'document_signing' ? 1 : checkbox.value === 'receipt' ? 1 : checkbox.value === 'full' ? 1 : 0);
        });
        
        // Update actual database fields based on checkbox state
        updatePoaDatabaseFields();
        
        // Update summary text
        const summaryText = document.getElementById('selected-poa-text');
        if (summaryText) {
            if (selected.length > 0) {
                summaryText.innerHTML = '<strong class="text-success">' + selected.join(', ') + '</strong>';
            } else {
                summaryText.innerHTML = '<span class="text-muted">None selected</span>';
            }
        }
    } catch (e) {
        console.error('Error updating POA text:', e);
    }
}

// Helper function to get POA label from value
function getPoaLabel(value) {
    const labels = {
        'sales': 'Sales Authority',
        'financial': 'Financial Authority',
        'legal': 'Legal Authority',
        'document_signing': 'Document Signing',
        'receipt': 'Receipt Authority',
        'full': 'Full Authority'
    };
    return labels[value] || value;
}

// Update actual POA database fields based on checkbox selections
function updatePoaDatabaseFields() {
    try {
        const salesCheck = document.querySelector('.poa-checkbox[value="sales"]');
        const financialCheck = document.querySelector('.poa-checkbox[value="financial"]');
        const legalCheck = document.querySelector('.poa-checkbox[value="legal"]');
        const docSignCheck = document.querySelector('.poa-checkbox[value="document_signing"]');
        const receiptCheck = document.querySelector('.poa-checkbox[value="receipt"]');
        const fullCheck = document.querySelector('.poa-checkbox[value="full"]');
        
        // Only update if checkboxes exist
        if (salesCheck) updateOrCreateHiddenInput('poa_sales_authority', salesCheck.checked ? 1 : 0);
        if (financialCheck) updateOrCreateHiddenInput('poa_financial_authority', financialCheck.checked ? 1 : 0);
        if (legalCheck) updateOrCreateHiddenInput('poa_legal_authority', legalCheck.checked ? 1 : 0);
        if (docSignCheck) updateOrCreateHiddenInput('poa_document_signing', docSignCheck.checked ? 1 : 0);
        if (receiptCheck) updateOrCreateHiddenInput('poa_receipt_authority', receiptCheck.checked ? 1 : 0);
        if (fullCheck) updateOrCreateHiddenInput('poa_full_authority', fullCheck.checked ? 1 : 0);
    } catch (e) {
        console.error('Error updating POA database fields:', e);
    }
}

// Helper function to update or create hidden input
function updateOrCreateHiddenInput(name, value) {
    let input = document.querySelector('input[name="' + name + '"]');
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        document.querySelector('form').appendChild(input);
    }
    input.value = value;
}

// Toggle Full Authority checkbox
function toggleFullAuthority() {
    try {
        const fullCheck = document.getElementById('poa-full-check');
        if (!fullCheck) return; // Element doesn't exist
        
        const checkboxes = document.querySelectorAll('.poa-checkbox:not(#poa-full-check)');
        if (!checkboxes) return;
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = fullCheck.checked;
        });
        
        updatePOAText();
    } catch (e) {
        console.error('Error toggling full authority:', e);
    }
}

// Initialize POA checkboxes on page load
function initPOACheckboxes() {
    // Load existing values from database hidden fields and check the appropriate checkboxes
    const salesAuth = document.querySelector('input[name="poa_sales_authority"]');
    const financialAuth = document.querySelector('input[name="poa_financial_authority"]');
    const legalAuth = document.querySelector('input[name="poa_legal_authority"]');
    const docSignAuth = document.querySelector('input[name="poa_document_signing"]');
    const receiptAuth = document.querySelector('input[name="poa_receipt_authority"]');
    const fullAuth = document.querySelector('input[name="poa_full_authority"]');
    
    // Set checkbox states based on hidden field values
    try {
        if (salesAuth && (salesAuth.value == 1 || salesAuth.value == '1')) {
            const cb = document.querySelector('.poa-checkbox[value="sales"]');
            if (cb) cb.checked = true;
        }
        if (financialAuth && (financialAuth.value == 1 || financialAuth.value == '1')) {
            const cb = document.querySelector('.poa-checkbox[value="financial"]');
            if (cb) cb.checked = true;
        }
        if (legalAuth && (legalAuth.value == 1 || legalAuth.value == '1')) {
            const cb = document.querySelector('.poa-checkbox[value="legal"]');
            if (cb) cb.checked = true;
        }
        if (docSignAuth && (docSignAuth.value == 1 || docSignAuth.value == '1')) {
            const cb = document.querySelector('.poa-checkbox[value="document_signing"]');
            if (cb) cb.checked = true;
        }
        if (receiptAuth && (receiptAuth.value == 1 || receiptAuth.value == '1')) {
            const cb = document.querySelector('.poa-checkbox[value="receipt"]');
            if (cb) cb.checked = true;
        }
        if (fullAuth && (fullAuth.value == 1 || fullAuth.value == '1')) {
            const cb = document.querySelector('.poa-checkbox[value="full"]');
            if (cb) cb.checked = true;
        }
    } catch (e) {
        console.log('POA initialization error:', e);
    }
    
    // Update the summary display
    updatePOAText();
    
    // Update the summary text
    updatePOAText();
}

// Apply approval template
function applyApprovalTemplate(template) {
    // First, clear all checkboxes
    clearAllApprovals();
    
    // Define templates
    const templates = {
        'dtcp_rera': ['DTCP', 'RERA'],
        'panchayat': ['Panchayat'],
        'cmda': ['CMDA', 'RERA'],
        'bda': ['BDA', 'RERA'],
        'patta': ['Patta Layout', 'Revenue'],
        'farmland': ['Farm Land', 'Revenue']
    };
    
    const approvals = templates[template] || [];
    
    // Check the appropriate checkboxes
    const checkboxes = document.querySelectorAll('.approval-checkbox');
    checkboxes.forEach(checkbox => {
        if (approvals.includes(checkbox.value)) {
            checkbox.checked = true;
        }
    });
    
    // Update the text field
    updateApprovalText();
    
    // Visual feedback
    const summary = document.getElementById('approval-summary');
    summary.style.background = '#d4edda';
    summary.style.borderColor = '#28a745';
    
    setTimeout(() => {
        summary.style.background = '#e3f2fd';
        summary.style.borderColor = '#0088cc';
    }, 1500);
}

// Clear all approvals
function clearAllApprovals() {
    const checkboxes = document.querySelectorAll('.approval-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Clear other field
    const otherField = document.getElementById('other-approval-field');
    if (otherField) {
        otherField.style.display = 'none';
        document.getElementById('other_approval_text').value = '';
    }
    
    updateApprovalText();
}

// Load existing approvals on page load
function loadExistingApprovals() {
    const approvalTypes = document.getElementById('approval_types').value;
    if (!approvalTypes) return;
    
    const types = approvalTypes.split(',').map(s => s.trim()).filter(s => s);
    const checkboxes = document.querySelectorAll('.approval-checkbox');
    
    checkboxes.forEach(checkbox => {
        if (types.includes(checkbox.value)) {
            checkbox.checked = true;
        }
    });
    
    // Check if there are types not in checkboxes (Other)
    const checkboxValues = Array.from(checkboxes).map(cb => cb.value);
    const otherTypes = types.filter(t => !checkboxValues.includes(t));
    
    if (otherTypes.length > 0) {
        const otherCheckbox = document.getElementById('approval-other-check');
        if (otherCheckbox) {
            otherCheckbox.checked = true;
            toggleOtherApproval();
            document.getElementById('other_approval_text').value = otherTypes.join(', ');
        }
    }
    
    updateApprovalText();
}

// ==========================================
// END APPROVAL TYPES SYSTEM
// ==========================================

// ==========================================
// ACRES TO SQFT AUTO-CONVERSION SYSTEM
// ==========================================

// Calculate Sqft from Acres (Main Function)
function calculateSqftFromAcres() {
    const acresInput = document.getElementById('total_acres');
    const sqftInput = document.getElementById('total_sqft');
    const badge = document.getElementById('sqft-auto-badge');
    const calcInfo = document.getElementById('sqft-calculation-info');
    const formulaDisplay = document.getElementById('sqft-formula-display');
    
    const acres = parseFloat(acresInput.value) || 0;
    
    if (acres > 0) {
        // 1 acre = 43,560 square feet
        const sqft = acres * 43560;
        sqftInput.value = sqft.toFixed(2);
        
        // Show badge and calculation info
        badge.style.display = 'inline-block';
        calcInfo.style.display = 'block';
        formulaDisplay.textContent = acres + ' acres × 43,560 = ' + sqft.toLocaleString() + ' sq.ft';
        
        // Add visual feedback
        sqftInput.style.backgroundColor = '#d4edda';
        sqftInput.style.borderColor = '#28a745';
        
        setTimeout(function() {
            sqftInput.style.backgroundColor = '#f0f8ff';
            sqftInput.style.borderColor = '#ddd';
        }, 1500);
        
    } else {
        sqftInput.value = '';
        badge.style.display = 'none';
        calcInfo.style.display = 'none';
    }
}

// Toggle Manual Sqft Entry
function toggleManualSqft() {
    const sqftInput = document.getElementById('total_sqft');
    const badge = document.getElementById('sqft-auto-badge');
    
    if (sqftInput.readOnly) {
        // Enable manual entry
        sqftInput.readOnly = false;
        sqftInput.style.backgroundColor = '#fff';
        sqftInput.style.fontWeight = 'normal';
        sqftInput.focus();
        badge.innerHTML = '<i class="fa fa-pencil"></i> Manual';
        badge.className = 'label label-warning';
        badge.style.display = 'inline-block';
        
        alert('Manual entry enabled. Auto-calculation disabled. Click Recalculate to re-enable auto-calculation.');
    } else {
        // Disable manual entry, re-enable auto-calculation
        sqftInput.readOnly = true;
        sqftInput.style.backgroundColor = '#f0f8ff';
        sqftInput.style.fontWeight = '600';
        badge.innerHTML = '<i class="fa fa-check"></i> Calculated';
        badge.className = 'label label-success';
        
        calculateSqftFromAcres();
    }
}

// Calculate Acres from Sqft (Reverse Calculation)
function calculateAcresFromSqft() {
    const sqftInput = document.getElementById('total_sqft');
    const acresInput = document.getElementById('total_acres');
    
    const sqft = parseFloat(sqftInput.value) || 0;
    
    if (sqft > 0) {
        const acres = sqft / 43560;
        acresInput.value = acres.toFixed(4);
        
        // Visual feedback
        acresInput.style.backgroundColor = '#fff3cd';
        acresInput.style.borderColor = '#ffc107';
        
        setTimeout(function() {
            acresInput.style.backgroundColor = '#fff';
            acresInput.style.borderColor = '#ddd';
        }, 1500);
    }
}

// Show Unit Conversion Tooltip
function showConversionTooltip() {
    const tooltip = document.createElement('div');
    tooltip.id = 'conversion-tooltip';
    tooltip.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border: 2px solid #0088cc; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; min-width: 350px;';
    
    tooltip.innerHTML = `
        <h5 style="margin-top: 0; color: #0088cc;">
            <i class="fa fa-calculator"></i> Area Conversion Reference
        </h5>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f0f8ff;">
                <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Unit</th>
                <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Square Feet</th>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">1 Acre</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">43,560 sq.ft</td>
            </tr>
            <tr style="background: #f9f9f9;">
                <td style="padding: 8px; border: 1px solid #ddd;">1 Hectare</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">107,639 sq.ft</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">1 Cent</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">435.6 sq.ft</td>
            </tr>
            <tr style="background: #f9f9f9;">
                <td style="padding: 8px; border: 1px solid #ddd;">1 Ground</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">2,400 sq.ft</td>
            </tr>
        </table>
        <button type="button" class="btn btn-sm btn-primary" style="margin-top: 15px; width: 100%;" onclick="closeConversionTooltip()">
            <i class="fa fa-times"></i> Close
        </button>
    `;
    
    document.body.appendChild(tooltip);
}

function closeConversionTooltip() {
    const tooltip = document.getElementById('conversion-tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// ==========================================
// END ACRES TO SQFT SYSTEM
// ==========================================

// POA Authority Management
function checkFullAuthority() {
    const fullAuthCheckbox = document.getElementById('poa_full_authority');
    const salesAuth = document.getElementById('poa_sales_authority');
    const financialAuth = document.getElementById('poa_financial_authority');
    const legalAuth = document.getElementById('poa_legal_authority');
    const docSignAuth = document.getElementById('poa_document_signing');
    const receiptAuth = document.getElementById('poa_receipt_authority');
    
    if (fullAuthCheckbox.checked) {
        // Check all individual authorities when Full Authority is checked
        salesAuth.checked = true;
        financialAuth.checked = true;
        legalAuth.checked = true;
        docSignAuth.checked = true;
        receiptAuth.checked = true;
    } else {
        // Uncheck all individual authorities when Full Authority is unchecked
        salesAuth.checked = false;
        financialAuth.checked = false;
        legalAuth.checked = false;
        docSignAuth.checked = false;
        receiptAuth.checked = false;
    }
    
    // Trigger change event on all boxes to update visuals
    [salesAuth, financialAuth, legalAuth, docSignAuth, receiptAuth, fullAuthCheckbox].forEach(function(checkbox) {
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
    });
}

// Update Full Authority checkbox based on individual authorities
function updateFullAuthority() {
    const fullAuthCheckbox = document.getElementById('poa_full_authority');
    const salesAuth = document.getElementById('poa_sales_authority');
    const financialAuth = document.getElementById('poa_financial_authority');
    const legalAuth = document.getElementById('poa_legal_authority');
    const docSignAuth = document.getElementById('poa_document_signing');
    const receiptAuth = document.getElementById('poa_receipt_authority');
    
    // Check if all 5 individual authorities are checked
    const allChecked = salesAuth.checked && financialAuth.checked && 
                      legalAuth.checked && docSignAuth.checked && receiptAuth.checked;
    
    // Update Full Authority checkbox accordingly
    fullAuthCheckbox.checked = allChecked;
    
    // Trigger change event to update visuals
    fullAuthCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
}

// Download POA Document
function downloadPOADocument() {
    // Implement document download logic
    alert('Download POA document functionality');
}

// Validate POA Form on Submit
function validatePOAForm() {
    const poaStatus = document.getElementById('poa_status').value;
    
    if (poaStatus !== 'none') {
        const grantorName = document.getElementById('poa_grantor_name').value.trim();
        const attorneyName = document.getElementById('poa_attorney_name').value.trim();
        
        if (!grantorName || !attorneyName) {
            alert('Please fill in Grantor Name and Attorney Name for POA');
            return false;
        }
    }
    
    return true;
}

// Enhanced checkbox handling for better UX
function setupCheckboxHandlers() {
    const poaCheckboxes = [
        'poa_sales_authority',
        'poa_financial_authority',
        'poa_legal_authority',
        'poa_document_signing',
        'poa_receipt_authority',
        'poa_full_authority'
    ];
    
    poaCheckboxes.forEach(function(checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            // Get parent elements
            const parentCheckbox = checkbox.closest('.checkbox');
            const parentLabel = checkbox.closest('label');
            
            // Function to update visual state
            function updateCheckboxStyle() {
                if (checkbox.checked) {
                    if (parentLabel) {
                        parentLabel.style.fontWeight = 'bold';
                        parentLabel.style.color = '#28a745';
                    }
                    if (parentCheckbox) {
                        parentCheckbox.style.backgroundColor = '#e8f5e9';
                        parentCheckbox.style.borderLeft = '3px solid #4caf50';
                    }
                } else {
                    if (parentLabel) {
                        parentLabel.style.fontWeight = 'normal';
                        parentLabel.style.color = 'inherit';
                    }
                    if (parentCheckbox) {
                        parentCheckbox.style.backgroundColor = 'transparent';
                        parentCheckbox.style.borderLeft = 'none';
                    }
                }
            }
            
            // Add change event listener
            checkbox.addEventListener('change', function() {
                updateCheckboxStyle();
                
                // Trigger appropriate function
                if (checkboxId === 'poa_full_authority') {
                    setTimeout(function() { checkFullAuthority(); }, 0);
                } else {
                    setTimeout(function() { updateFullAuthority(); }, 0);
                }
            });
            
            // Add click event listener for immediate visual feedback
            checkbox.addEventListener('click', function() {
                // Force update after click
                setTimeout(function() {
                    updateCheckboxStyle();
                }, 10);
            });
            
            // Apply initial styling if checkbox is checked
            updateCheckboxStyle();
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    try {
        setupCheckboxHandlers();
        initPOACheckboxes();
        togglePOASection();
        loadExistingApprovals();
        autoGenerateFromName(); // Initialize auto-generate on name change
        init_selectpicker();
        
        // Hide badge initially on edit mode if code exists
        const codeInput = document.getElementById('code');
        const badge = document.getElementById('code-auto-badge');
        if (codeInput && codeInput.value.trim()) {
            badge.style.display = 'none';
        }
    } catch (e) {
        console.error('Error during page initialization:', e);
    }
});
</script>

<style>
/* Enhanced POA Checkbox Styling - Aggressive Override */
.checkbox input[type="checkbox"] {
    cursor: pointer !important;
    margin-right: 8px !important;
    width: 18px !important;
    height: 18px !important;
    vertical-align: middle !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    position: relative !important;
    appearance: checkbox !important;
    -webkit-appearance: checkbox !important;
    -moz-appearance: checkbox !important;
    accent-color: #007bff !important;
}

.checkbox label {
    cursor: pointer !important;
    margin-bottom: 0 !important;
    user-select: none !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
}

.checkbox label:hover {
    text-decoration: none !important;
}

/* Visual feedback for checked boxes */
.checkbox {
    padding: 10px !important;
    border-radius: 4px !important;
    margin-bottom: 15px !important;
    transition: all 0.2s ease !important;
}

.checkbox input[type="checkbox"]:checked {
    background-color: #007bff !important;
}

.checkbox input[type="checkbox"]:focus {
    outline: 2px solid #007bff !important;
    outline-offset: 2px !important;
}

/* Better spacing for checkbox rows */
.checkbox small {
    display: block !important;
    margin-top: 4px !important;
}

/* POA Section styling */
#poa_section .checkbox {
    border-radius: 4px !important;
    padding: 10px !important;
    transition: background-color 0.2s ease !important;
}

#poa_section .checkbox:hover {
    background-color: #f8f9fa !important;
}

#poa_section .checkbox input[type="checkbox"]:checked {
    background-color: #007bff !important;
}

/* Additional important override for Bootstrap conflicts */
.form-group .checkbox input[type="checkbox"] {
    cursor: pointer !important;
    margin-right: 8px !important;
    width: 18px !important;
    height: 18px !important;
}

/* Ensure clickability on labels */
.checkbox label strong {
    font-weight: normal !important;
    transition: all 0.2s ease !important;
}

.checkbox input[type="checkbox"]:checked ~ strong {
    font-weight: bold !important;
    color: #28a745 !important;
}
</style>


