<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('real_estate_bookings', '', 'create')) { ?>
                                <a href="<?php echo admin_url('real_estat/booking'); ?>" class="btn btn-info pull-left display-block">
                                    <i class="fa fa-plus"></i> <?php echo _l('new_booking'); ?>
                                </a>
                            <?php } ?>
                            
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo admin_url('real_estat/bookings'); ?>" class="btn btn-default <?php if ($selected_status == '') echo 'active'; ?>">
                                        <?php echo _l('all'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('real_estat/bookings/pending'); ?>" class="btn btn-default <?php if ($selected_status == 'pending') echo 'active'; ?>">
                                        <?php echo _l('pending'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('real_estat/bookings/confirmed'); ?>" class="btn btn-default <?php if ($selected_status == 'confirmed') echo 'active'; ?>">
                                        <?php echo _l('confirmed'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('real_estat/bookings/converted_to_sale'); ?>" class="btn btn-default <?php if ($selected_status == 'converted_to_sale') echo 'active'; ?>">
                                        <?php echo _l('converted_to_sale'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('real_estat/bookings/cancelled'); ?>" class="btn btn-default <?php if ($selected_status == 'cancelled') echo 'active'; ?>">
                                        <?php echo _l('cancelled'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        
                        <table class="table dt-table table-bookings" data-order-col="0" data-order-type="desc">
                            <thead>
                                <tr>
                                    <th><?php echo _l('booking_code'); ?></th>
                                    <th><?php echo _l('customer'); ?></th>
                                    <th><?php echo _l('project_name'); ?></th>
                                    <th><?php echo _l('plot_number'); ?></th>
                                    <th><?php echo _l('booking_amount'); ?></th>
                                    <th><?php echo _l('final_amount'); ?></th>
                                    <th><?php echo _l('booking_status'); ?></th>
                                    <th><?php echo _l('booking_date'); ?></th>
                                    <th><?php echo _l('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking) { ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo admin_url('real_estat/booking/' . $booking['id']); ?>">
                                                <strong><?php echo $booking['booking_code']; ?></strong>
                                            </a>
                                        </td>
                                        <td><?php echo $booking['customer_name']; ?></td>
                                        <td><?php echo $booking['project_name']; ?></td>
                                        <td><?php echo $booking['plot_number']; ?></td>
                                        <td><?php echo app_format_money($booking['booking_amount'], get_base_currency()); ?></td>
                                        <td><?php echo app_format_money($booking['final_amount'], get_base_currency()); ?></td>
                                        <td>
                                            <?php
                                            $status_class = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'cancelled' => 'danger',
                                                'converted_to_sale' => 'info'
                                            ];
                                            ?>
                                            <span class="label label-<?php echo $status_class[$booking['status']]; ?>">
                                                <?php echo _l($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo _d($booking['booking_date']); ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('real_estat/booking/' . $booking['id']); ?>" class="btn btn-default btn-icon btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
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
        initDataTable('.table-bookings', window.location.href, [8], [8]);
    });
</script>
</body>
</html>
