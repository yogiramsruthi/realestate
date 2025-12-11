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
                        
                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <h3 class="text-info"><?php echo $total_projects; ?></h3>
                                        <p class="text-muted"><?php echo _l('realestate_total_projects'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <h3 class="text-success"><?php echo $available_plots; ?></h3>
                                        <p class="text-muted"><?php echo _l('realestate_total_plots_available'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <h3 class="text-warning"><?php echo $total_bookings; ?></h3>
                                        <p class="text-muted"><?php echo _l('realestate_total_bookings'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <h3 class="text-danger"><?php echo app_format_money($total_revenue, get_base_currency()); ?></h3>
                                        <p class="text-muted"><?php echo _l('realestate_total_revenue'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Projects Overview -->
                        <div class="row mtop20">
                            <div class="col-md-6">
                                <h4><?php echo _l('realestate_project_overview'); ?></h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_project_name'); ?></th>
                                            <th><?php echo _l('realestate_total_plots'); ?></th>
                                            <th><?php echo _l('realestate_available_plots'); ?></th>
                                            <th><?php echo _l('realestate_project_status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($projects)) { ?>
                                            <?php foreach ($projects as $project) { ?>
                                                <tr>
                                                    <td><?php echo $project['name']; ?></td>
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

                            <!-- Recent Bookings -->
                            <div class="col-md-6">
                                <h4><?php echo _l('realestate_recent_bookings'); ?></h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('realestate_customer'); ?></th>
                                            <th><?php echo _l('realestate_plot_number'); ?></th>
                                            <th><?php echo _l('realestate_booking_amount'); ?></th>
                                            <th><?php echo _l('realestate_booking_status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recent_bookings)) { ?>
                                            <?php foreach ($recent_bookings as $booking) { ?>
                                                <tr>
                                                    <td><?php echo $booking['customer_name']; ?></td>
                                                    <td><?php echo $booking['plot_number']; ?></td>
                                                    <td><?php echo app_format_money($booking['booking_amount'], get_base_currency()); ?></td>
                                                    <td>
                                                        <?php if ($booking['status'] == 'confirmed') { ?>
                                                            <span class="label label-success"><?php echo _l('realestate_status_confirmed'); ?></span>
                                                        <?php } elseif ($booking['status'] == 'pending') { ?>
                                                            <span class="label label-warning"><?php echo _l('realestate_status_pending'); ?></span>
                                                        <?php } else { ?>
                                                            <span class="label label-default"><?php echo $booking['status']; ?></span>
                                                        <?php } ?>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
