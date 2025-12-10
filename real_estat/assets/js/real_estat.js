/* Real Estate Module JavaScript */

$(document).ready(function() {
    'use strict';
    
    // Auto-calculate plot total price
    $(document).on('change keyup', '#plot_area, #plot_rate_per_unit', function() {
        calculatePlotTotal();
    });
    
    // Auto-calculate booking final amount
    $(document).on('change keyup', '#total_amount, #discount', function() {
        calculateBookingTotal();
    });
    
    // Load plots when project is selected
    $(document).on('change', '#project_id_booking', function() {
        var project_id = $(this).val();
        loadAvailablePlots(project_id);
    });
    
    // Update booking amount when plot is selected
    $(document).on('change', '#plot_id', function() {
        var plot_price = $(this).find('option:selected').data('price');
        if (plot_price) {
            $('#total_amount').val(plot_price);
            calculateBookingTotal();
        }
    });
});

function calculatePlotTotal() {
    var area = parseFloat($('#plot_area').val()) || 0;
    var rate = parseFloat($('#plot_rate_per_unit').val()) || 0;
    var total = area * rate;
    $('#total_price').val(total.toFixed(2));
}

function calculateBookingTotal() {
    var total = parseFloat($('#total_amount').val()) || 0;
    var discount = parseFloat($('#discount').val()) || 0;
    var final = total - discount;
    $('#final_amount').val(final.toFixed(2));
}

function loadAvailablePlots(project_id) {
    if (!project_id) {
        $('#plot_id').html('<option value="">-- Select Plot --</option>');
        $('#plot_id').selectpicker('refresh');
        return;
    }
    
    $.ajax({
        url: admin_url + 'real_estat/get_available_plots/' + project_id,
        type: 'GET',
        dataType: 'json',
        success: function(plots) {
            var options = '<option value="">-- Select Plot --</option>';
            $.each(plots, function(i, plot) {
                options += '<option value="' + plot.id + '" data-price="' + plot.total_price + '">';
                options += plot.plot_number + ' - ' + plot.area + ' ' + plot.area_unit;
                options += ' - ' + format_money(plot.total_price);
                options += '</option>';
            });
            $('#plot_id').html(options);
            $('#plot_id').selectpicker('refresh');
        }
    });
}

function loadProjectBlocks(project_id, selected_block_id) {
    if (!project_id) {
        $('#block_id').html('<option value="">-- Select Block --</option>');
        $('#block_id').selectpicker('refresh');
        return;
    }
    
    $.ajax({
        url: admin_url + 'real_estat/get_blocks_by_project/' + project_id,
        type: 'GET',
        dataType: 'json',
        success: function(blocks) {
            var options = '<option value="">-- None --</option>';
            $.each(blocks, function(i, block) {
                var selected = (selected_block_id && block.id == selected_block_id) ? 'selected' : '';
                options += '<option value="' + block.id + '" ' + selected + '>' + block.name + '</option>';
            });
            $('#block_id').html(options);
            $('#block_id').selectpicker('refresh');
        }
    });
}

// Confirm delete actions
$(document).on('click', '._delete', function(e) {
    if (!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault();
        return false;
    }
});

// Format money helper
function format_money(amount) {
    return accounting.formatMoney(amount, {
        symbol: get_base_currency_symbol(),
        decimal: '.',
        thousand: ',',
        precision: 2,
        format: '%s%v'
    });
}

// Get base currency symbol
function get_base_currency_symbol() {
    return $('body').data('base-currency-symbol') || '$';
}

// Plot grid view
function renderPlotGrid(plots, container_id) {
    var html = '<div class="plot-grid">';
    
    $.each(plots, function(i, plot) {
        html += '<div class="plot-grid-item ' + plot.status + '" data-plot-id="' + plot.id + '">';
        html += '<div class="plot-number"><strong>' + plot.plot_number + '</strong></div>';
        html += '<div class="plot-area">' + plot.area + ' ' + plot.area_unit + '</div>';
        html += '<div class="plot-price">' + format_money(plot.total_price) + '</div>';
        html += '<div class="plot-status-label"><span class="label label-' + getStatusClass(plot.status) + '">' + plot.status + '</span></div>';
        html += '</div>';
    });
    
    html += '</div>';
    
    $('#' + container_id).html(html);
}

function getStatusClass(status) {
    var classes = {
        'available': 'success',
        'booked': 'warning',
        'sold': 'info',
        'reserved': 'default',
        'blocked': 'danger'
    };
    return classes[status] || 'default';
}

// Initialize payment plan calculator
function initPaymentPlanCalculator() {
    $(document).on('change', '#payment_plan_id', function() {
        var plan_id = $(this).val();
        var final_amount = parseFloat($('#final_amount').val()) || 0;
        
        if (plan_id && final_amount > 0) {
            calculateInstallments(plan_id, final_amount);
        }
    });
}

function calculateInstallments(plan_id, total_amount) {
    $.ajax({
        url: admin_url + 'real_estat/calculate_payment_plan',
        type: 'POST',
        data: {
            payment_plan_id: plan_id,
            total_amount: total_amount
        },
        dataType: 'json',
        success: function(result) {
            if (result.success) {
                displayInstallmentSchedule(result.installments);
            }
        }
    });
}

function displayInstallmentSchedule(installments) {
    var html = '<div class="payment-schedule"><h4>Payment Schedule</h4>';
    html += '<table class="table table-bordered">';
    html += '<thead><tr><th>#</th><th>Due Date</th><th>Amount</th></tr></thead>';
    html += '<tbody>';
    
    $.each(installments, function(i, inst) {
        html += '<tr>';
        html += '<td>' + (inst.installment_number == 0 ? 'Down Payment' : 'Installment ' + inst.installment_number) + '</td>';
        html += '<td>' + inst.due_date + '</td>';
        html += '<td>' + format_money(inst.amount) + '</td>';
        html += '</tr>';
    });
    
    html += '</tbody></table></div>';
    
    $('#installment_schedule').html(html);
}
