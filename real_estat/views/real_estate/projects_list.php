<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h4 class="tw-mt-0 tw-mb-3">
                <?php echo _l('real_estate_projects'); ?>
                <a href="<?php echo admin_url('real_estate/add_project'); ?>" class="btn btn-primary pull-right">
                    <i class="fa fa-plus"></i> <?php echo _l('real_estate_add_project'); ?>
                </a>
            </h4>
        </div>

        <div class="col-md-12">
            <table class="table dt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo _l('real_estate_project'); ?></th>
                        <th>District</th>
                        <th>Village</th>
                        <th>Total Plots</th>
                        <th>Approval Type</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($projects as $p) { ?>
                    <tr>
                        <td><?php echo $p->id; ?></td>
                        <td><?php echo $p->project_name; ?></td>
                        <td><?php echo $p->district; ?></td>
                        <td><?php echo $p->village; ?></td>
                        <td><?php echo $p->total_plots; ?></td>
                        <td><?php echo strtoupper($p->approval_type); ?></td>
                        <td><?php echo $p->date_created; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
</body>
</html>
