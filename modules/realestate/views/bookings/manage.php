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
                                <a href="<?php echo admin_url('realestate/bookings/booking'); ?>" class="btn btn-info pull-left">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_booking'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        
                        <table class="table dt-table table-bookings">
                            <thead>
                                <tr>
                                    <th><?php echo _l('realestate_customer'); ?></th>
                                    <th><?php echo _l('realestate_project_name'); ?></th>
                                    <th><?php echo _l('realestate_plot_number'); ?></th>
                                    <th><?php echo _l('realestate_booking_date'); ?></th>
                                    <th><?php echo _l('realestate_total_amount'); ?></th>
                                    <th><?php echo _l('realestate_paid_amount'); ?></th>
                                    <th><?php echo _l('realestate_balance_amount'); ?></th>
                                    <th><?php echo _l('realestate_booking_status'); ?></th>
                                    <th><?php echo _l('realestate_actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking) { ?>
                                    <tr>
                                        <td><?php echo $booking['customer_name']; ?></td>
                                        <td><?php echo $booking['project_name']; ?></td>
                                        <td><?php echo $booking['plot_number']; ?></td>
                                        <td><?php echo _d($booking['booking_date']); ?></td>
                                        <td><?php echo app_format_money($booking['total_amount'], get_base_currency()); ?></td>
                                        <td><?php echo app_format_money($booking['paid_amount'], get_base_currency()); ?></td>
                                        <td><?php echo app_format_money($booking['balance_amount'], get_base_currency()); ?></td>
                                        <td>
                                            <?php if ($booking['status'] == 'confirmed') { ?>
                                                <span class="label label-success"><?php echo _l('realestate_status_confirmed'); ?></span>
                                            <?php } elseif ($booking['status'] == 'pending') { ?>
                                                <span class="label label-warning"><?php echo _l('realestate_status_pending'); ?></span>
                                            <?php } elseif ($booking['status'] == 'cancelled') { ?>
                                                <span class="label label-danger"><?php echo _l('realestate_status_cancelled'); ?></span>
                                            <?php } else { ?>
                                                <span class="label label-default"><?php echo $booking['status']; ?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if (has_permission('realestate', '', 'edit')) { ?>
                                                <a href="<?php echo admin_url('realestate/bookings/booking/' . $booking['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (has_permission('realestate', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('realestate/bookings/delete/' . $booking['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
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
        $('.table-bookings').DataTable();
    });
</script>
</body>
</html>
