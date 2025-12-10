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
                                <a href="<?php echo admin_url('realestate/accounts/transaction'); ?>" class="btn btn-info pull-left">
                                    <i class="fa fa-plus"></i> <?php echo _l('realestate_add_transaction'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        
                        <table class="table dt-table table-transactions">
                            <thead>
                                <tr>
                                    <th><?php echo _l('realestate_customer'); ?></th>
                                    <th><?php echo _l('realestate_plot_number'); ?></th>
                                    <th><?php echo _l('realestate_transaction_date'); ?></th>
                                    <th><?php echo _l('realestate_transaction_amount'); ?></th>
                                    <th><?php echo _l('realestate_payment_mode'); ?></th>
                                    <th><?php echo _l('realestate_reference_number'); ?></th>
                                    <th><?php echo _l('realestate_actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction) { ?>
                                    <tr>
                                        <td><?php echo $transaction['customer_name']; ?></td>
                                        <td><?php echo $transaction['plot_number']; ?></td>
                                        <td><?php echo _d($transaction['transaction_date']); ?></td>
                                        <td><?php echo app_format_money($transaction['amount'], get_base_currency()); ?></td>
                                        <td><?php echo $transaction['payment_mode']; ?></td>
                                        <td><?php echo $transaction['reference_number']; ?></td>
                                        <td>
                                            <?php if (has_permission('realestate', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('realestate/accounts/delete/' . $transaction['id']); ?>" class="btn btn-danger btn-icon btn-sm _delete">
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
        $('.table-transactions').DataTable();
    });
</script>
</body>
</html>
