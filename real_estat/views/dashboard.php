<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('real_estate'); ?> - <?php echo _l('dashboard'); ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center bg-info">
                                        <h3 class="bold"><?php echo $stats['total_projects']; ?></h3>
                                        <p class="text-white"><?php echo _l('total') . ' ' . _l('real_estate_projects'); ?></p>
                                        <span class="label label-success"><?php echo $stats['active_projects']; ?> <?php echo _l('active'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center bg-success">
                                        <h3 class="bold"><?php echo $stats['total_plots']; ?></h3>
                                        <p class="text-white"><?php echo _l('total') . ' ' . _l('real_estate_plots'); ?></p>
                                        <span class="label label-info"><?php echo $stats['available_plots']; ?> <?php echo _l('available'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center bg-warning">
                                        <h3 class="bold"><?php echo $stats['total_bookings']; ?></h3>
                                        <p class="text-white"><?php echo _l('total') . ' ' . _l('real_estate_bookings'); ?></p>
                                        <span class="label label-success"><?php echo $stats['confirmed_bookings']; ?> <?php echo _l('confirmed'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s">
                                    <div class="panel-body text-center bg-danger">
                                        <h3 class="bold"><?php echo $stats['overdue_payments']; ?></h3>
                                        <p class="text-white"><?php echo _l('overdue') . ' ' . _l('installments'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Bookings -->
                        <div class="row mtop20">
                            <div class="col-md-12">
                                <h4><?php echo _l('recent') . ' ' . _l('real_estate_bookings'); ?></h4>
                                <table class="table dt-table">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('booking_code'); ?></th>
                                            <th><?php echo _l('customer'); ?></th>
                                            <th><?php echo _l('project_name'); ?></th>
                                            <th><?php echo _l('plot_number'); ?></th>
                                            <th><?php echo _l('booking_amount'); ?></th>
                                            <th><?php echo _l('booking_status'); ?></th>
                                            <th><?php echo _l('booking_date'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_bookings as $booking) { ?>
                                            <tr>
                                                <td><a href="<?php echo admin_url('real_estat/booking/' . $booking['id']); ?>"><?php echo $booking['booking_code']; ?></a></td>
                                                <td><?php echo $booking['customer_name']; ?></td>
                                                <td><?php echo $booking['project_name']; ?></td>
                                                <td><?php echo $booking['plot_number']; ?></td>
                                                <td><?php echo app_format_money($booking['booking_amount'], get_base_currency()); ?></td>
                                                <td><span class="label label-<?php echo $booking['status'] == 'confirmed' ? 'success' : ($booking['status'] == 'pending' ? 'warning' : 'default'); ?>"><?php echo _l($booking['status']); ?></span></td>
                                                <td><?php echo _d($booking['booking_date']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Overdue Payments -->
                        <?php if (count($overdue_installments) > 0) { ?>
                            <div class="row mtop20">
                                <div class="col-md-12">
                                    <h4 class="text-danger"><?php echo _l('overdue') . ' ' . _l('installments'); ?></h4>
                                    <table class="table dt-table">
                                        <thead>
                                            <tr>
                                                <th><?php echo _l('booking_code'); ?></th>
                                                <th><?php echo _l('installment_number'); ?></th>
                                                <th><?php echo _l('due_date'); ?></th>
                                                <th><?php echo _l('amount'); ?></th>
                                                <th><?php echo _l('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($overdue_installments as $installment) { 
                                                $booking = $this->real_estate_model->get_bookings($installment['booking_id']);
                                            ?>
                                                <tr>
                                                    <td><a href="<?php echo admin_url('real_estat/booking/' . $booking->id); ?>"><?php echo $booking->booking_code; ?></a></td>
                                                    <td><?php echo $installment['installment_number']; ?></td>
                                                    <td class="text-danger"><?php echo _d($installment['due_date']); ?></td>
                                                    <td><?php echo app_format_money($installment['amount'], get_base_currency()); ?></td>
                                                    <td>
                                                        <a href="<?php echo admin_url('real_estat/booking/' . $booking->id); ?>" class="btn btn-sm btn-default"><?php echo _l('view'); ?></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
