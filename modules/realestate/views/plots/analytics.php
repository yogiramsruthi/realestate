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
                        
                        <!-- Project Filter -->
                        <div class="row">
                            <div class="col-md-4">
                                <form method="get">
                                    <?php 
                                    $project_options = [['value' => '', 'label' => 'All Projects']];
                                    foreach ($projects as $project) {
                                        $project_options[] = ['value' => $project['id'], 'label' => $project['name']];
                                    }
                                    echo render_select('project_id', $project_options, ['value', 'label'], 'realestate_project_name', $this->input->get('project_id')); 
                                    ?>
                                    <button type="submit" class="btn btn-info"><?php echo _l('realestate_apply_filter'); ?></button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Analytics Dashboard -->
                        <div class="row mtop20">
                            <!-- By Status -->
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('realestate_by_status'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="statusChart"></canvas>
                                        <table class="table table-condensed mtop15">
                                            <tbody>
                                                <?php foreach ($analytics['by_status'] as $stat) { ?>
                                                    <tr>
                                                        <td><?php echo ucfirst($stat['status']); ?></td>
                                                        <td class="text-right"><strong><?php echo $stat['count']; ?></strong></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- By Category -->
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('realestate_by_category'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="categoryChart"></canvas>
                                        <table class="table table-condensed mtop15">
                                            <tbody>
                                                <?php foreach ($analytics['by_category'] as $cat) { ?>
                                                    <tr>
                                                        <td><?php echo ucfirst($cat['plot_category']); ?></td>
                                                        <td class="text-right"><strong><?php echo $cat['count']; ?></strong></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Statistics -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('realestate_price_statistics'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h3 class="text-info"><?php echo app_format_money($analytics['price_stats']['avg_price'], ''); ?></h3>
                                                    <p><?php echo _l('realestate_average_price'); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h3 class="text-success"><?php echo app_format_money($analytics['price_stats']['min_price'], ''); ?></h3>
                                                    <p><?php echo _l('realestate_min_price'); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h3 class="text-danger"><?php echo app_format_money($analytics['price_stats']['max_price'], ''); ?></h3>
                                                    <p><?php echo _l('realestate_max_price'); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h3 class="text-warning"><?php echo $analytics['sales_velocity']; ?></h3>
                                                    <p><?php echo _l('realestate_sold_last_30_days'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Status Chart
var statusData = <?php echo json_encode(array_column($analytics['by_status'], 'count')); ?>;
var statusLabels = <?php echo json_encode(array_map('ucfirst', array_column($analytics['by_status'], 'status'))); ?>;

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Category Chart
var categoryData = <?php echo json_encode(array_column($analytics['by_category'], 'count')); ?>;
var categoryLabels = <?php echo json_encode(array_map('ucfirst', array_column($analytics['by_category'], 'plot_category'))); ?>;

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Plots',
            data: categoryData,
            backgroundColor: ['#007bff', '#6c757d', '#28a745']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>
</html>
