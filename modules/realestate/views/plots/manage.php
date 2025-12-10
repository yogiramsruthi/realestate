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
                                <a href="<?php echo admin_url('realestate/plots/plot'); ?>" class="btn btn-info pull-left">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_plot'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        
                        <table class="table dt-table table-plots">
                            <thead>
                                <tr>
                                    <th><?php echo _l('realestate_project_name'); ?></th>
                                    <th><?php echo _l('realestate_plot_number'); ?></th>
                                    <th><?php echo _l('realestate_plot_size'); ?></th>
                                    <th><?php echo _l('realestate_plot_type'); ?></th>
                                    <th><?php echo _l('realestate_plot_price'); ?></th>
                                    <th><?php echo _l('realestate_plot_status'); ?></th>
                                    <th><?php echo _l('realestate_actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plots as $plot) { ?>
                                    <tr>
                                        <td><?php echo $plot['project_name']; ?></td>
                                        <td><?php echo $plot['plot_number']; ?></td>
                                        <td><?php echo $plot['plot_size']; ?></td>
                                        <td><?php echo $plot['plot_type']; ?></td>
                                        <td><?php echo app_format_money($plot['price'], get_base_currency()); ?></td>
                                        <td>
                                            <?php if ($plot['status'] == 'available') { ?>
                                                <span class="label label-success"><?php echo _l('realestate_status_available'); ?></span>
                                            <?php } elseif ($plot['status'] == 'booked') { ?>
                                                <span class="label label-warning"><?php echo _l('realestate_status_booked'); ?></span>
                                            <?php } elseif ($plot['status'] == 'sold') { ?>
                                                <span class="label label-info"><?php echo _l('realestate_status_sold'); ?></span>
                                            <?php } else { ?>
                                                <span class="label label-default"><?php echo $plot['status']; ?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if (has_permission('realestate', '', 'edit')) { ?>
                                                <a href="<?php echo admin_url('realestate/plots/plot/' . $plot['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (has_permission('realestate', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('realestate/plots/delete/' . $plot['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
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
        $('.table-plots').DataTable();
    });
</script>
</body>
</html>
