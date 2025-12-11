<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('realestate', '', 'create')) { ?>
                                <a href="<?php echo admin_url('realestate/projects/project'); ?>" class="btn btn-info pull-left">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_project'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        
                        <table class="table dt-table table-projects">
                            <thead>
                                <tr>
                                    <th><?php echo _l('realestate_project_code'); ?></th>
                                    <th><?php echo _l('realestate_project_name'); ?></th>
                                    <th><?php echo _l('realestate_district'); ?></th>
                                    <th><?php echo _l('realestate_project_type'); ?></th>
                                    <th><?php echo _l('realestate_total_plots'); ?></th>
                                    <th><?php echo _l('realestate_available_plots'); ?></th>
                                    <th><?php echo _l('realestate_project_status'); ?></th>
                                    <th><?php echo _l('realestate_actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project) { ?>
                                    <tr>
                                        <td><?php echo $project['project_code']; ?></td>
                                        <td><?php echo $project['name']; ?></td>
                                        <td><?php echo $project['district']; ?></td>
                                        <td><?php echo $project['project_type']; ?></td>
                                        <td><?php echo $project['total_plots']; ?></td>
                                        <td><?php echo $project['available_plots']; ?></td>
                                        <td>
                                            <?php if ($project['status'] == 'active') { ?>
                                                <span class="label label-success"><?php echo _l('realestate_status_active'); ?></span>
                                            <?php } elseif ($project['status'] == 'draft') { ?>
                                                <span class="label label-warning"><?php echo _l('realestate_status_draft'); ?></span>
                                            <?php } elseif ($project['status'] == 'archived') { ?>
                                                <span class="label label-default"><?php echo _l('realestate_status_archived'); ?></span>
                                            <?php } else { ?>
                                                <span class="label label-info"><?php echo $project['status']; ?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if (has_permission('realestate', '', 'edit')) { ?>
                                                <a href="<?php echo admin_url('realestate/projects/project/' . $project['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (has_permission('realestate', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('realestate/projects/delete/' . $project['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
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
        $('.table-projects').DataTable();
    });
</script>
</body>
</html>
