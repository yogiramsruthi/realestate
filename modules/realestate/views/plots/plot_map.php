<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?> - <?php echo $project->name; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="plot-map-container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="plot-grid">
                                        <?php foreach ($plots as $plot) { 
                                            $status_class = [
                                                'available' => 'plot-available',
                                                'reserved' => 'plot-reserved',
                                                'booked' => 'plot-booked',
                                                'sold' => 'plot-sold'
                                            ];
                                            $class = isset($status_class[$plot['status']]) ? $status_class[$plot['status']] : 'plot-default';
                                        ?>
                                            <div class="plot-box <?php echo $class; ?>" 
                                                 data-plot-id="<?php echo $plot['id']; ?>"
                                                 title="<?php echo $plot['plot_number'] . ' - ' . ucfirst($plot['status']); ?>">
                                                <div class="plot-number"><?php echo $plot['plot_number']; ?></div>
                                                <div class="plot-size"><?php echo $plot['plot_size']; ?> sq.ft</div>
                                                <div class="plot-price"><?php echo app_format_money($plot['price'], ''); ?></div>
                                                <?php if ($plot['corner_plot']) { ?>
                                                    <span class="badge badge-info">C</span>
                                                <?php } ?>
                                                <?php if ($plot['main_road_facing']) { ?>
                                                    <span class="badge badge-success">MR</span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mtop30">
                                <div class="col-md-12">
                                    <h4><?php echo _l('realestate_plot_status'); ?> Legend</h4>
                                    <div class="plot-legend">
                                        <span class="legend-item"><span class="legend-box plot-available"></span> <?php echo _l('realestate_status_available'); ?></span>
                                        <span class="legend-item"><span class="legend-box plot-reserved"></span> <?php echo _l('realestate_status_reserved'); ?></span>
                                        <span class="legend-item"><span class="legend-box plot-booked"></span> <?php echo _l('realestate_status_booked'); ?></span>
                                        <span class="legend-item"><span class="legend-box plot-sold"></span> <?php echo _l('realestate_status_sold'); ?></span>
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
<style>
.plot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    padding: 20px;
}

.plot-box {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    min-height: 120px;
}

.plot-box:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.plot-available {
    background-color: #d4edda;
    border-color: #28a745;
}

.plot-reserved {
    background-color: #fff3cd;
    border-color: #ffc107;
}

.plot-booked {
    background-color: #d1ecf1;
    border-color: #17a2b8;
}

.plot-sold {
    background-color: #f8d7da;
    border-color: #dc3545;
}

.plot-number {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 8px;
}

.plot-size {
    font-size: 12px;
    color: #666;
}

.plot-price {
    font-weight: bold;
    color: #007bff;
    margin-top: 8px;
}

.plot-box .badge {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 10px;
}

.plot-legend {
    display: flex;
    gap: 20px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-box {
    display: inline-block;
    width: 30px;
    height: 30px;
    border: 2px solid #ddd;
    border-radius: 4px;
}
</style>
<script>
// Click on plot to view details
$('.plot-box').on('click', function() {
    var plotId = $(this).data('plot-id');
    window.location.href = '<?php echo admin_url('realestate/plots/plot/'); ?>' + plotId;
});
</script>
</body>
</html>
