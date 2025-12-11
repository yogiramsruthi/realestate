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
                                <a href="<?php echo admin_url('realestate/team/assignment'); ?>" class="btn btn-info pull-left">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_team_member'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        
                        <table class="table dt-table table-team">
                            <thead>
                                <tr>
                                    <th><?php echo _l('realestate_staff_member'); ?></th>
                                    <th><?php echo _l('realestate_project_name'); ?></th>
                                    <th><?php echo _l('realestate_role'); ?></th>
                                    <th><?php echo _l('realestate_assigned_date'); ?></th>
                                    <th><?php echo _l('realestate_team_status'); ?></th>
                                    <th><?php echo _l('realestate_actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($team_assignments as $assignment) { ?>
                                    <tr>
                                        <td><?php echo $assignment['staff_name']; ?></td>
                                        <td><?php echo $assignment['project_name']; ?></td>
                                        <td><?php echo $assignment['role']; ?></td>
                                        <td><?php echo _d($assignment['assigned_date']); ?></td>
                                        <td>
                                            <?php if ($assignment['status'] == 'active') { ?>
                                                <span class="label label-success"><?php echo _l('realestate_active'); ?></span>
                                            <?php } else { ?>
                                                <span class="label label-default"><?php echo _l('realestate_inactive'); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if (has_permission('realestate', '', 'edit')) { ?>
                                                <a href="<?php echo admin_url('realestate/team/assignment/' . $assignment['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (has_permission('realestate', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('realestate/team/delete/' . $assignment['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
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
        $('.table-team').DataTable();
    });
</script>
</body>
</html>
