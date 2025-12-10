<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id"><?php echo _l('project_name'); ?> *</label>
                                    <select name="project_id" id="project_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required onchange="loadBlocks(this.value)">
                                        <option value="">-- <?php echo _l('select'); ?> --</option>
                                        <?php if (isset($projects) && is_array($projects)) {
                                            foreach ($projects as $proj) { ?>
                                                <option value="<?php echo $proj['id']; ?>" <?php if (isset($plot) && $plot->project_id == $proj['id']) echo 'selected'; ?>>
                                                    <?php echo $proj['name']; ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="block_id"><?php echo _l('block_name'); ?></label>
                                    <select name="block_id" id="block_id" class="form-control selectpicker" data-width="100%">
                                        <option value="">-- <?php echo _l('select'); ?> --</option>
                                        <?php if (isset($blocks) && is_array($blocks)) {
                                            foreach ($blocks as $block) { ?>
                                                <option value="<?php echo $block['id']; ?>" <?php if (isset($plot) && $plot->block_id == $block['id']) echo 'selected'; ?>>
                                                    <?php echo $block['name']; ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="plot_number"><?php echo _l('plot_number'); ?> *</label>
                                    <input type="text" id="plot_number" name="plot_number" class="form-control" value="<?php echo isset($plot) ? $plot->plot_number : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="plot_type"><?php echo _l('plot_type'); ?></label>
                                    <select name="plot_type" id="plot_type" class="form-control selectpicker" data-width="100%">
                                        <option value="">--</option>
                                        <option value="residential" <?php if (isset($plot) && $plot->plot_type == 'residential') echo 'selected'; ?>><?php echo _l('residential'); ?></option>
                                        <option value="commercial" <?php if (isset($plot) && $plot->plot_type == 'commercial') echo 'selected'; ?>><?php echo _l('commercial'); ?></option>
                                        <option value="industrial" <?php if (isset($plot) && $plot->plot_type == 'industrial') echo 'selected'; ?>><?php echo _l('industrial'); ?></option>
                                        <option value="agricultural" <?php if (isset($plot) && $plot->plot_type == 'agricultural') echo 'selected'; ?>><?php echo _l('agricultural'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="facing"><?php echo _l('plot_facing'); ?></label>
                                    <select name="facing" id="facing" class="form-control selectpicker" data-width="100%">
                                        <option value="">--</option>
                                        <option value="north" <?php if (isset($plot) && $plot->facing == 'north') echo 'selected'; ?>><?php echo _l('north'); ?></option>
                                        <option value="south" <?php if (isset($plot) && $plot->facing == 'south') echo 'selected'; ?>><?php echo _l('south'); ?></option>
                                        <option value="east" <?php if (isset($plot) && $plot->facing == 'east') echo 'selected'; ?>><?php echo _l('east'); ?></option>
                                        <option value="west" <?php if (isset($plot) && $plot->facing == 'west') echo 'selected'; ?>><?php echo _l('west'); ?></option>
                                        <option value="north_east" <?php if (isset($plot) && $plot->facing == 'north_east') echo 'selected'; ?>><?php echo _l('north_east'); ?></option>
                                        <option value="north_west" <?php if (isset($plot) && $plot->facing == 'north_west') echo 'selected'; ?>><?php echo _l('north_west'); ?></option>
                                        <option value="south_east" <?php if (isset($plot) && $plot->facing == 'south_east') echo 'selected'; ?>><?php echo _l('south_east'); ?></option>
                                        <option value="south_west" <?php if (isset($plot) && $plot->facing == 'south_west') echo 'selected'; ?>><?php echo _l('south_west'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="area"><?php echo _l('plot_area'); ?> *</label>
                                    <input type="number" id="area" name="area" class="form-control" value="<?php echo isset($plot) ? $plot->area : ''; ?>" required step="0.01" onchange="calculateTotal()">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="area_unit"><?php echo _l('area_unit'); ?></label>
                                    <select name="area_unit" id="area_unit" class="form-control selectpicker" data-width="100%">
                                        <option value="sqft" <?php if (isset($plot) && $plot->area_unit == 'sqft') echo 'selected'; ?>>Sq.Ft</option>
                                        <option value="sqm" <?php if (isset($plot) && $plot->area_unit == 'sqm') echo 'selected'; ?>>Sq.M</option>
                                        <option value="cent" <?php if (isset($plot) && $plot->area_unit == 'cent') echo 'selected'; ?>>Cent</option>
                                        <option value="acre" <?php if (isset($plot) && $plot->area_unit == 'acre') echo 'selected'; ?>>Acre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dimensions"><?php echo _l('plot_dimensions'); ?></label>
                                    <input type="text" id="dimensions" name="dimensions" class="form-control" placeholder="e.g., 30x40" value="<?php echo isset($plot) ? $plot->dimensions : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rate_per_unit"><?php echo _l('rate_per_unit'); ?> *</label>
                                    <input type="number" id="rate_per_unit" name="rate_per_unit" class="form-control" value="<?php echo isset($plot) ? $plot->rate_per_unit : ''; ?>" required step="0.01" onchange="calculateTotal()">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_price"><?php echo _l('total_price'); ?></label>
                                    <input type="number" id="total_price" name="total_price" class="form-control" value="<?php echo isset($plot) ? $plot->total_price : ''; ?>" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status"><?php echo _l('plot_status'); ?></label>
                                    <select name="status" id="status" class="form-control selectpicker" data-width="100%">
                                        <option value="available" <?php if (isset($plot) && $plot->status == 'available') echo 'selected'; ?>><?php echo _l('available'); ?></option>
                                        <option value="booked" <?php if (isset($plot) && $plot->status == 'booked') echo 'selected'; ?>><?php echo _l('booked'); ?></option>
                                        <option value="sold" <?php if (isset($plot) && $plot->status == 'sold') echo 'selected'; ?>><?php echo _l('sold'); ?></option>
                                        <option value="reserved" <?php if (isset($plot) && $plot->status == 'reserved') echo 'selected'; ?>><?php echo _l('reserved'); ?></option>
                                        <option value="blocked" <?php if (isset($plot) && $plot->status == 'blocked') echo 'selected'; ?>><?php echo _l('blocked'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description"><?php echo _l('description'); ?></label>
                                    <textarea id="description" name="description" class="form-control" rows="4"><?php echo isset($plot) ? $plot->description : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    function calculateTotal() {
        var area = parseFloat($('#area').val()) || 0;
        var rate = parseFloat($('#rate_per_unit').val()) || 0;
        var total = area * rate;
        $('#total_price').val(total.toFixed(2));
    }
    
    function loadBlocks(project_id) {
        if (!project_id) {
            $('#block_id').html('<option value="">--</option>');
            $('#block_id').selectpicker('refresh');
            return;
        }
        
        $.get('<?php echo admin_url('real_estat/get_blocks_by_project/'); ?>' + project_id, function(blocks) {
            var options = '<option value="">--</option>';
            $.each(blocks, function(i, block) {
                options += '<option value="' + block.id + '">' + block.name + '</option>';
            });
            $('#block_id').html(options);
            $('#block_id').selectpicker('refresh');
        });
    }
    
    $(document).ready(function() {
        calculateTotal();
    });
</script>
</body>
</html>
