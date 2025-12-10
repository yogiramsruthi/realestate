<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('real_estate_plots', '', 'create')) { ?>
                                <a href="<?php echo admin_url('real_estat/plot'); ?>" class="btn btn-info pull-left display-block mright5">
                                    <i class="fa fa-plus"></i> <?php echo _l('new_plot'); ?>
                                </a>
                                <a href="<?php echo admin_url('real_estat/bulk_import_plots'); ?>" class="btn btn-success pull-left display-block">
                                    <i class="fa fa-upload"></i> <?php echo _l('bulk_import_plots'); ?>
                                </a>
                            <?php } ?>
                            
                            <div class="pull-right">
                                <div class="form-group" style="margin-bottom:0;">
                                    <select name="project_filter" id="project_filter" class="selectpicker" data-width="250px" onchange="filterByProject(this.value)">
                                        <option value=""><?php echo _l('select_project'); ?></option>
                                        <?php foreach ($projects as $proj) { ?>
                                            <option value="<?php echo $proj['id']; ?>" <?php if ($selected_project == $proj['id']) echo 'selected'; ?>>
                                                <?php echo $proj['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        
                        <table class="table dt-table table-plots" data-order-col="0" data-order-type="desc">
                            <thead>
                                <tr>
                                    <th><?php echo _l('plot_number'); ?></th>
                                    <th><?php echo _l('project_name'); ?></th>
                                    <th><?php echo _l('block_name'); ?></th>
                                    <th><?php echo _l('plot_type'); ?></th>
                                    <th><?php echo _l('plot_area'); ?></th>
                                    <th><?php echo _l('plot_facing'); ?></th>
                                    <th><?php echo _l('total_price'); ?></th>
                                    <th><?php echo _l('plot_status'); ?></th>
                                    <th><?php echo _l('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plots as $plot) { ?>
                                    <tr>
                                        <td><strong><?php echo $plot['plot_number']; ?></strong></td>
                                        <td><?php echo $plot['project_name']; ?></td>
                                        <td><?php echo $plot['block_name'] ?: '--'; ?></td>
                                        <td><?php echo $plot['plot_type'] ?: '--'; ?></td>
                                        <td><?php echo $plot['area'] . ' ' . $plot['area_unit']; ?></td>
                                        <td><?php echo $plot['facing'] ? _l($plot['facing']) : '--'; ?></td>
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
                                            <?php if (has_permission('real_estate_plots', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('real_estat/delete_plot/' . $plot['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-plots', window.location.href, [8], [8]);
    });
    
    function filterByProject(project_id) {
        if (project_id) {
            window.location.href = '<?php echo admin_url('real_estat/plots/'); ?>' + project_id;
        } else {
            window.location.href = '<?php echo admin_url('real_estat/plots'); ?>';
        }
    }
</script>
</body>
</html>
