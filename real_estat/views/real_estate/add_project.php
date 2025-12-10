<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
<div class="content">
<div class="row">
<div class="col-md-12">

<h4 class="tw-mt-0 tw-mb-3">
    <i class="fa fa-plus"></i> <?php echo _l('real_estate_add_project'); ?>
</h4>

<?php echo form_open(admin_url('real_estate/save_project'), ['id' => 'project_form']); ?>

<div class="panel_s">
<div class="panel-body">

<!-- 1️⃣ BASIC DETAILS -->
<h4 class="bold mbot20">Project Basic Information</h4>
<div class="row">
    <div class="col-md-4">
        <?php echo render_input('project_code', 'Project Code'); ?>
    </div>
    <div class="col-md-8">
        <?php echo render_input('project_name', 'Project Name', '', 'text', ['required' => true]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?php echo render_input('district', 'District'); ?>
    </div>
    <div class="col-md-4">
        <?php echo render_input('taluk', 'Taluk'); ?>
    </div>
    <div class="col-md-4">
        <?php echo render_input('village', 'Village'); ?>
    </div>
</div>

<?php echo render_input('survey_numbers', 'Survey Number(s)'); ?>

<hr>

<!-- 2️⃣ APPROVAL / LAND DETAILS -->
<h4 class="bold mbot20">Land & Approval Information</h4>

<div class="row">
    <div class="col-md-4">
        <?php echo render_select('approval_type', [
            ['id' => 'dtcp_rera', 'name' => 'DTCP + RERA Approved'],
            ['id' => 'panchayat_78go', 'name' => 'Panchayat 78 GO'],
            ['id' => 'patta_layout', 'name' => 'Patta Layout'],
            ['id' => 'farm_land', 'name' => 'Farm Land'],
            ['id' => 'other', 'name' => 'Other'],
        ], ['id', 'name'], 'Approval Type'); ?>
    </div>

    <div class="col-md-2">
        <?php echo render_input('total_raw_acres', 'Total Raw Acres', '', 'number'); ?>
    </div>

    <div class="col-md-2">
        <?php echo render_input('total_raw_sqft', 'Total Raw Sqft', '', 'number'); ?>
    </div>

    <div class="col-md-2">
        <?php echo render_input('total_approved_sqft', 'Total Approved Sqft', '', 'number'); ?>
    </div>

    <div class="col-md-2">
        <?php echo render_input('total_plots', 'Total Plots', '', 'number'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?php echo render_input('dtcp_no', 'DTCP Number'); ?>
    </div>
    <div class="col-md-4">
        <?php echo render_input('rera_no', 'RERA Number'); ?>
    </div>
    <div class="col-md-2">
        <?php echo render_date_input('approval_date', 'Approval Date'); ?>
    </div>
    <div class="col-md-2">
        <?php echo render_date_input('approval_expiry_date', 'Approval Expiry Date'); ?>
    </div>
</div>

<hr>

<!-- 3️⃣ EMI SETTINGS -->
<h4 class="bold mbot20">Booking & EMI Settings</h4>

<div class="row">
    <div class="col-md-4">
        <?php echo render_input('booking_validity_days', 'Booking Validity Days', '30', 'number'); ?>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="allow_emi" class="control-label">Enable EMI Option</label>
            <div class="checkbox">
                <input type="checkbox" id="allow_emi" name="allow_emi" checked>
                <label for="allow_emi"> Yes</label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?php echo render_input('default_emi_interest', 'Default EMI Interest (%)', '0', 'number'); ?>
    </div>
    <div class="col-md-4">
        <?php echo render_input('default_emi_due_day', 'EMI Due Day (1-31)', '', 'number'); ?>
    </div>
</div>

<hr>

<!-- 4️⃣ SAVE -->
<div class="text-center">
    <button type="submit" class="btn btn-success">
        <i class="fa fa-save"></i> Save Project
    </button>
    <a href="<?php echo admin_url('real_estate/projects'); ?>" class="btn btn-default">Cancel</a>
</div>

</div> <!-- panel-body -->
</div> <!-- panel -->
<?php echo form_close(); ?>

</div>
</div>
</div>
</div>

<?php init_tail(); ?>
</body>
</html>
