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
                        
                        <?php if (!empty($project_id) && $project_id != '') { ?>
                        <!-- Additional Information Tabs -->
                        <hr class="mtop30" />
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#owners_tab" aria-controls="owners_tab" role="tab" data-toggle="tab">
                                    <i class="fa fa-users"></i> <?php echo _l('realestate_owner_details'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#patta_tab" aria-controls="patta_tab" role="tab" data-toggle="tab">
                                    <i class="fa fa-file-text-o"></i> <?php echo _l('realestate_patta_details'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#documents_tab" aria-controls="documents_tab" role="tab" data-toggle="tab">
                                    <i class="fa fa-folder-open"></i> <?php echo _l('realestate_documents'); ?>
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content mtop20">
                            <!-- Owners Tab -->
                            <div role="tabpanel" class="tab-pane active" id="owners_tab">
                                <button type="button" class="btn btn-primary btn-sm pull-right" onclick="addOwner()">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_owner'); ?>
                                </button>
                                <div class="clearfix"></div>
                                <table class="table table-bordered mtop15">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_owner_name'); ?></th>
                                            <th><?php echo _l('realestate_owner_type'); ?></th>
                                            <th><?php echo _l('realestate_contact_number'); ?></th>
                                            <th><?php echo _l('realestate_ownership_percentage'); ?></th>
                                            <th><?php echo _l('realestate_actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($owners)) { ?>
                                            <?php foreach ($owners as $owner) { ?>
                                                <tr>
                                                    <td><?php echo $owner['owner_name']; ?></td>
                                                    <td><?php echo $owner['owner_type']; ?></td>
                                                    <td><?php echo $owner['contact_number']; ?></td>
                                                    <td><?php echo $owner['ownership_percentage']; ?>%</td>
                                                    <td>
                                                        <button type="button" class="btn btn-default btn-icon btn-sm" onclick="editOwner(<?php echo $owner['id']; ?>)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-icon btn-sm" onclick="deleteOwner(<?php echo $owner['id']; ?>)">
                                                            <i class="fa fa-remove"></i>
                                                        </button>
                                                    </td>
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
                            
                            <!-- Patta Tab -->
                            <div role="tabpanel" class="tab-pane" id="patta_tab">
                                <button type="button" class="btn btn-primary btn-sm pull-right" onclick="addPatta()">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_patta'); ?>
                                </button>
                                <div class="clearfix"></div>
                                <table class="table table-bordered mtop15">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_patta_number'); ?></th>
                                            <th><?php echo _l('realestate_survey_number'); ?></th>
                                            <th><?php echo _l('realestate_patta_holder_name'); ?></th>
                                            <th><?php echo _l('realestate_extent'); ?></th>
                                            <th><?php echo _l('realestate_actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($patta_details)) { ?>
                                            <?php foreach ($patta_details as $patta) { ?>
                                                <tr>
                                                    <td><?php echo $patta['patta_number']; ?></td>
                                                    <td><?php echo $patta['survey_number']; ?></td>
                                                    <td><?php echo $patta['patta_holder_name']; ?></td>
                                                    <td><?php echo $patta['extent']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-default btn-icon btn-sm" onclick="editPatta(<?php echo $patta['id']; ?>)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-icon btn-sm" onclick="deletePatta(<?php echo $patta['id']; ?>)">
                                                            <i class="fa fa-remove"></i>
                                                        </button>
                                                    </td>
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
                            
                            <!-- Documents Tab -->
                            <div role="tabpanel" class="tab-pane" id="documents_tab">
                                <button type="button" class="btn btn-primary btn-sm pull-right" onclick="$('#upload_document_modal').modal('show')">
                                    <i class="fa fa-upload"></i> <?php echo _l('realestate_upload_document'); ?>
                                </button>
                                <div class="clearfix"></div>
                                <table class="table table-bordered mtop15">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_document_type'); ?></th>
                                            <th><?php echo _l('realestate_document_name'); ?></th>
                                            <th><?php echo _l('realestate_uploaded_date'); ?></th>
                                            <th><?php echo _l('realestate_actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($documents)) { ?>
                                            <?php foreach ($documents as $doc) { ?>
                                                <tr>
                                                    <td><?php echo $doc['document_type']; ?></td>
                                                    <td><?php echo $doc['document_name']; ?></td>
                                                    <td><?php echo _dt($doc['date_uploaded']); ?></td>
                                                    <td>
                                                        <a href="<?php echo admin_url('realestate/projects/download_document/' . $doc['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                        <a href="<?php echo admin_url('realestate/projects/delete_document/' . $doc['id'] . '/' . $project_id); ?>" class="btn btn-danger btn-icon btn-sm _delete">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    </td>
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

<!-- Upload Document Modal -->
<div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title"><?php echo _l('realestate_upload_document'); ?></h4>
            </div>
            <?php echo form_open_multipart(admin_url('realestate/projects/upload_document')); ?>
            <div class="modal-body">
                <input type="hidden" name="project_id" value="<?php echo isset($project_id) ? $project_id : ''; ?>">
                
                <div class="form-group">
                    <label for="document_type"><?php echo _l('realestate_document_type'); ?> *</label>
                    <select name="document_type" class="form-control selectpicker" required>
                        <option value="">Select Document Type</option>
                        <option value="parent_document"><?php echo _l('realestate_doc_parent_document'); ?></option>
                        <option value="current_document"><?php echo _l('realestate_doc_current_document'); ?></option>
                        <option value="layout_sketch"><?php echo _l('realestate_doc_layout_sketch'); ?></option>
                        <option value="patta"><?php echo _l('realestate_doc_patta'); ?></option>
                        <option value="sale_deed"><?php echo _l('realestate_doc_sale_deed'); ?></option>
                        <option value="encumbrance"><?php echo _l('realestate_doc_encumbrance'); ?></option>
                        <option value="other"><?php echo _l('realestate_doc_other'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="document_name"><?php echo _l('realestate_document_name'); ?> *</label>
                    <input type="text" name="document_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="file"><?php echo _l('realestate_file'); ?> *</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description"><?php echo _l('realestate_description'); ?></label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('realestate_cancel'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('realestate_upload_document'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Owner Modal -->
<div class="modal fade" id="owner_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="owner_modal_title"><?php echo _l('realestate_add_owner'); ?></h4>
            </div>
            <form id="owner_form">
                <div class="modal-body">
                    <input type="hidden" name="id" id="owner_id">
                    <input type="hidden" name="project_id" value="<?php echo isset($project_id) ? $project_id : ''; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="owner_name"><?php echo _l('realestate_owner_name'); ?> *</label>
                                <input type="text" name="owner_name" id="owner_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="owner_type"><?php echo _l('realestate_owner_type'); ?></label>
                                <select name="owner_type" id="owner_type" class="form-control selectpicker">
                                    <option value="individual"><?php echo _l('realestate_owner_type_individual'); ?></option>
                                    <option value="company"><?php echo _l('realestate_owner_type_company'); ?></option>
                                    <option value="partnership"><?php echo _l('realestate_owner_type_partnership'); ?></option>
                                    <option value="trust"><?php echo _l('realestate_owner_type_trust'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_number"><?php echo _l('realestate_contact_number'); ?></label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><?php echo _l('realestate_email'); ?></label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="aadhar_number"><?php echo _l('realestate_aadhar_number'); ?></label>
                                <input type="text" name="aadhar_number" id="aadhar_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pan_number"><?php echo _l('realestate_pan_number'); ?></label>
                                <input type="text" name="pan_number" id="pan_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ownership_percentage"><?php echo _l('realestate_ownership_percentage'); ?></label>
                                <input type="number" name="ownership_percentage" id="ownership_percentage" class="form-control" step="0.01" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address"><?php echo _l('realestate_address'); ?></label>
                        <textarea name="address" id="address" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="owner_notes"><?php echo _l('realestate_notes'); ?></label>
                        <textarea name="notes" id="owner_notes" class="form-control" rows="2"></textarea>
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

<!-- Patta Modal -->
<div class="modal fade" id="patta_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="patta_modal_title"><?php echo _l('realestate_add_patta'); ?></h4>
            </div>
            <form id="patta_form">
                <div class="modal-body">
                    <input type="hidden" name="id" id="patta_id">
                    <input type="hidden" name="project_id" value="<?php echo isset($project_id) ? $project_id : ''; ?>">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="patta_number"><?php echo _l('realestate_patta_number'); ?></label>
                                <input type="text" name="patta_number" id="patta_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="survey_number"><?php echo _l('realestate_survey_number'); ?></label>
                                <input type="text" name="survey_number" id="survey_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subdivision_number"><?php echo _l('realestate_subdivision_number'); ?></label>
                                <input type="text" name="subdivision_number" id="subdivision_number" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patta_holder_name"><?php echo _l('realestate_patta_holder_name'); ?></label>
                                <input type="text" name="patta_holder_name" id="patta_holder_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="extent"><?php echo _l('realestate_extent'); ?></label>
                                <input type="text" name="extent" id="extent" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="classification"><?php echo _l('realestate_classification'); ?></label>
                                <input type="text" name="classification" id="classification" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="patta_remarks"><?php echo _l('realestate_remarks'); ?></label>
                        <textarea name="remarks" id="patta_remarks" class="form-control" rows="3"></textarea>
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

// Owner Management Functions
function addOwner() {
    $('#owner_id').val('');
    $('#owner_form')[0].reset();
    $('#owner_modal_title').text('<?php echo _l('realestate_add_owner'); ?>');
    $('#owner_modal').modal('show');
}

function editOwner(id) {
    // Fetch owner data and populate form
    $.get('<?php echo admin_url('realestate/projects/get_owner/'); ?>' + id, function(data) {
        if (data) {
            $('#owner_id').val(data.id);
            $('#owner_name').val(data.owner_name);
            $('#owner_type').val(data.owner_type).selectpicker('refresh');
            $('#contact_number').val(data.contact_number);
            $('#email').val(data.email);
            $('#aadhar_number').val(data.aadhar_number);
            $('#pan_number').val(data.pan_number);
            $('#ownership_percentage').val(data.ownership_percentage);
            $('#address').val(data.address);
            $('#owner_notes').val(data.notes);
            $('#owner_modal_title').text('<?php echo _l('realestate_edit_owner'); ?>');
            $('#owner_modal').modal('show');
        }
    });
}

function deleteOwner(id) {
    if (confirm('Are you sure you want to delete this owner?')) {
        $.get('<?php echo admin_url('realestate/projects/delete_owner/'); ?>' + id, function(response) {
            if (response.success) {
                alert_float('success', response.message);
                location.reload();
            } else {
                alert_float('danger', response.message);
            }
        });
    }
}

$('#owner_form').on('submit', function(e) {
    e.preventDefault();
    $.post('<?php echo admin_url('realestate/projects/save_owner'); ?>', $(this).serialize(), function(response) {
        if (response.success) {
            alert_float('success', response.message);
            $('#owner_modal').modal('hide');
            location.reload();
        } else {
            alert_float('danger', response.message);
        }
    });
});

// Patta Management Functions
function addPatta() {
    $('#patta_id').val('');
    $('#patta_form')[0].reset();
    $('#patta_modal_title').text('<?php echo _l('realestate_add_patta'); ?>');
    $('#patta_modal').modal('show');
}

function editPatta(id) {
    // Fetch patta data and populate form
    $.get('<?php echo admin_url('realestate/projects/get_patta/'); ?>' + id, function(data) {
        if (data) {
            $('#patta_id').val(data.id);
            $('#patta_number').val(data.patta_number);
            $('#survey_number').val(data.survey_number);
            $('#subdivision_number').val(data.subdivision_number);
            $('#patta_holder_name').val(data.patta_holder_name);
            $('#extent').val(data.extent);
            $('#classification').val(data.classification);
            $('#patta_remarks').val(data.remarks);
            $('#patta_modal_title').text('<?php echo _l('realestate_edit_patta'); ?>');
            $('#patta_modal').modal('show');
        }
    });
}

function deletePatta(id) {
    if (confirm('Are you sure you want to delete this patta detail?')) {
        $.get('<?php echo admin_url('realestate/projects/delete_patta/'); ?>' + id, function(response) {
            if (response.success) {
                alert_float('success', response.message);
                location.reload();
            } else {
                alert_float('danger', response.message);
            }
        });
    }
}

$('#patta_form').on('submit', function(e) {
    e.preventDefault();
    $.post('<?php echo admin_url('realestate/projects/save_patta'); ?>', $(this).serialize(), function(response) {
        if (response.success) {
            alert_float('success', response.message);
            $('#patta_modal').modal('hide');
            location.reload();
        } else {
            alert_float('danger', response.message);
        }
    });
});
</script>
</body>
</html>
