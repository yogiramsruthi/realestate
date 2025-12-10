<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('accounting_exports'); ?></h4>
                        <p class="text-muted mtop5"><?php echo _l('accounting_export_dashboard'); ?></p>

                        <form method="get" action="<?php echo admin_url('real_estat/accounting_exports'); ?>" class="form-inline mtop15">
                            <div class="form-group mright10">
                                <label class="control-label mright5"><?php echo _l('from'); ?></label>
                                <input type="date" name="from" class="form-control" value="<?php echo html_escape($from); ?>">
                            </div>
                            <div class="form-group mright10">
                                <label class="control-label mright5"><?php echo _l('to'); ?></label>
                                <input type="date" name="to" class="form-control" value="<?php echo html_escape($to); ?>">
                            </div>
                            <button type="submit" class="btn btn-default"><?php echo _l('filter'); ?></button>
                        </form>

                        <div class="table-responsive mtop20">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('export_type'); ?></th>
                                        <th><?php echo _l('export_count'); ?></th>
                                        <th class="text-right"><?php echo _l('actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($types as $key => $config) { ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo html_escape($config['label']); ?></strong><br>
                                                <small class="text-muted"><?php echo _l('accounting_export_filters'); ?></small>
                                            </td>
                                            <td>
                                                <span class="label label-success">
                                                    <?php echo isset($counts[$key]) ? (int)$counts[$key] : 0; ?>
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo admin_url('real_estat/export_accounting_csv?type=' . $key . '&from=' . $from . '&to=' . $to); ?>"
                                                   class="btn btn-default btn-sm">
                                                    <?php echo _l('export_csv'); ?>
                                                </a>
                                                <a href="<?php echo admin_url('real_estat/accounting_tally_xml?type=' . $key . '&from=' . $from . '&to=' . $to); ?>"
                                                   class="btn btn-default btn-sm">
                                                    <?php echo _l('export_tally_xml'); ?>
                                                </a>
                                                <form method="post" action="<?php echo admin_url('real_estat/accounting_tally_push'); ?>"
                                                      class="d-inline-block mleft5">
                                                    <input type="hidden" name="type" value="<?php echo $key; ?>">
                                                    <input type="hidden" name="from" value="<?php echo $from; ?>">
                                                    <input type="hidden" name="to" value="<?php echo $to; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <?php echo _l('tally_push'); ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="text-muted"><?php echo _l('accounting_exports_note'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
