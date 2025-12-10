<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('real_estate_projects', '', 'create')) { ?>
                                <a href="<?php echo admin_url('real_estat/project'); ?>" class="btn btn-info pull-left display-block">
                                    <i class="fa fa-plus"></i> <?php echo _l('new_project'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        
                        <!-- Enhanced Filters -->
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo _l('district'); ?></label>
                                    <input type="text" class="form-control project-filter" data-filter="district" placeholder="Filter by district...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo _l('area'); ?></label>
                                    <input type="text" class="form-control project-filter" data-filter="area" placeholder="Filter by area...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo _l('project_status'); ?></label>
                                    <select class="form-control project-filter selectpicker" data-filter="status" data-live-search="true">
                                        <option value="">-- <?php echo _l('all'); ?> --</option>
                                        <option value="draft"><?php echo _l('status_draft'); ?></option>
                                        <option value="active"><?php echo _l('status_active'); ?></option>
                                        <option value="archived"><?php echo _l('status_archived'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-default btn-block" id="reset-filters">
                                        <i class="fa fa-refresh"></i> <?php echo _l('reset'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                        
                        <table class="table dt-table table-projects" data-order-col="0" data-order-type="desc">
                            <thead>
                                <tr>
                                    <th><?php echo _l('project_code'); ?></th>
                                    <th><?php echo _l('project_name'); ?></th>
                                    <th><?php echo _l('district'); ?></th>
                                    <th><?php echo _l('area'); ?></th>
                                    <th><?php echo _l('total_plots'); ?></th>
                                    <th><?php echo _l('total_acres'); ?></th>
                                    <th><?php echo _l('total_owners'); ?></th>
                                    <th><?php echo _l('emi_enabled'); ?></th>
                                    <th><?php echo _l('project_status'); ?></th>
                                    <th><?php echo _l('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project) { ?>
                                    <tr>
                                        <td><?php echo $project['code']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('real_estat/project/' . $project['id']); ?>">
                                                <strong><?php echo $project['name']; ?></strong>
                                            </a>
                                        </td>
                                        <td><?php echo isset($project['district']) ? $project['district'] : '-'; ?></td>
                                        <td><?php echo isset($project['area']) ? $project['area'] : '-'; ?></td>
                                        <td><?php echo isset($project['total_plots']) ? $project['total_plots'] : 0; ?></td>
                                        <td>
                                            <span class="text-muted">
                                                <?php echo isset($project['total_acres']) ? round($project['total_acres'], 2) . ' ' . _l('acres') : '-'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo isset($project['total_owners']) ? $project['total_owners'] : '-'; ?></td>
                                        <td>
                                            <?php if (isset($project['emi_enabled']) && $project['emi_enabled']): ?>
                                                <span class="badge badge-success"><?php echo _l('yes'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-default"><?php echo _l('no'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status_class = [
                                                'draft' => 'default',
                                                'active' => 'success',
                                                'archived' => 'warning',
                                                'planning' => 'info',
                                                'on_hold' => 'warning',
                                                'completed' => 'success'
                                            ];
                                            $status = isset($project['status']) ? $project['status'] : 'draft';
                                            ?>
                                            <span class="label label-<?php echo isset($status_class[$status]) ? $status_class[$status] : 'default'; ?>">
                                                <?php echo _l('status_' . $status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo admin_url('real_estat/project/' . $project['id']); ?>" class="btn btn-default btn-icon btn-sm" title="<?php echo _l('edit'); ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?php echo admin_url('real_estat/projects/' . $project['id']); ?>" class="btn btn-info btn-icon btn-sm" title="<?php echo _l('plots'); ?>">
                                                    <i class="fa fa-th"></i>
                                                </a>
                                                <?php if (has_permission('real_estate_projects', '', 'delete')) { ?>
                                                    <a href="<?php echo admin_url('real_estat/delete_project/' . $project['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete" title="<?php echo _l('delete'); ?>">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
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
        initDataTable('.table-projects', window.location.href, [9], [9]);
        
        // Filter functionality
        $('.project-filter').on('change keyup', function() {
            var filterType = $(this).data('filter');
            var filterValue = $(this).val().toLowerCase();
            var columnIndex = {
                'district': 2,
                'area': 3,
                'status': 8
            }[filterType];
            
            if (columnIndex !== undefined) {
                $('.table-projects').DataTable().column(columnIndex).search(filterValue).draw();
            }
        });
        
        $('#reset-filters').on('click', function() {
            $('.project-filter').val('').change();
            $('.table-projects').DataTable().search('').columns().search('').draw();
            if (typeof $('.selectpicker').selectpicker === 'function') {
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
</script>
</body>
</html>
