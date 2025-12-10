<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="commission-slab-editor" style="display: none; margin-top: 20px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
    <h5><?php echo _l('commission_slab_editor'); ?></h5>
    <hr />
    
    <div id="slabs-container">
        <!-- Slabs will be added here dynamically -->
    </div>
    
    <button type="button" class="btn btn-sm btn-primary" id="add-slab-btn">
        <i class="fa fa-plus"></i> <?php echo _l('add_slab'); ?>
    </button>
    
    <button type="button" class="btn btn-sm btn-success" id="save-slabs-btn">
        <i class="fa fa-save"></i> <?php echo _l('save_slabs'); ?>
    </button>
</div>

<script>
    let slabCounter = 0;
    let slabsData = [];
    
    // Load existing slabs from JSON
    function loadSlabs() {
        const jsonField = document.getElementById('team_commission_slab_json');
        if (jsonField && jsonField.value) {
            try {
                slabsData = JSON.parse(jsonField.value);
                renderSlabs();
            } catch (e) {
                console.error('Invalid JSON in slab field');
            }
        }
    }
    
    // Render slabs in the editor
    function renderSlabs() {
        const container = document.getElementById('slabs-container');
        container.innerHTML = '';
        
        slabsData.forEach((slab, index) => {
            addSlabRow(slab.min, slab.max, slab.rate, index);
        });
        
        slabCounter = slabsData.length;
    }
    
    // Add a slab row
    function addSlabRow(minVal = '', maxVal = '', rateVal = '', index = null) {
        const container = document.getElementById('slabs-container');
        const rowId = index !== null ? index : slabCounter++;
        const rowHtml = `
            <div class="slab-row" data-index="${rowId}" style="margin-bottom: 10px; padding: 10px; background: white; border: 1px solid #e0e0e0; border-radius: 3px;">
                <div class="row">
                    <div class="col-md-3">
                        <label><?php echo _l('slab_from'); ?></label>
                        <input type="number" class="form-control slab-min" value="${minVal}" placeholder="Min Amount" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <label><?php echo _l('slab_to'); ?></label>
                        <input type="number" class="form-control slab-max" value="${maxVal}" placeholder="Max Amount" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <label><?php echo _l('commission_percent'); ?></label>
                        <input type="number" class="form-control slab-rate" value="${rateVal}" placeholder="Rate %" step="0.01" min="0" max="100">
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block btn-sm remove-slab-btn">
                            <i class="fa fa-trash"></i> <?php echo _l('remove'); ?>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = rowHtml;
        const newRow = tempDiv.firstElementChild;
        container.appendChild(newRow);
        
        // Remove button handler
        newRow.querySelector('.remove-slab-btn').addEventListener('click', function() {
            newRow.remove();
        });
    }
    
    // Save slabs to JSON field
    function saveSlabs() {
        const rows = document.querySelectorAll('.slab-row');
        const slabs = [];
        
        rows.forEach(row => {
            const min = parseFloat(row.querySelector('.slab-min').value);
            const max = parseFloat(row.querySelector('.slab-max').value);
            const rate = parseFloat(row.querySelector('.slab-rate').value);
            
            if (!isNaN(min) && !isNaN(max) && !isNaN(rate)) {
                slabs.push({
                    min: min,
                    max: max,
                    rate: rate
                });
            }
        });
        
        if (slabs.length === 0) {
            alert('<?php echo _l("please_add_at_least_one_slab"); ?>');
            return false;
        }
        
        // Validate slab ranges
        for (let i = 0; i < slabs.length; i++) {
            if (slabs[i].min >= slabs[i].max) {
                alert('<?php echo _l("slab_min_must_be_less_than_max"); ?>');
                return false;
            }
        }
        
        const jsonField = document.getElementById('team_commission_slab_json');
        jsonField.value = JSON.stringify(slabs, null, 2);
        
        // Hide editor
        document.getElementById('commission-slab-editor').style.display = 'none';
        
        // Show confirmation
        alert('<?php echo _l("slabs_saved_successfully"); ?>');
        return true;
    }
    
    // Toggle commission slab editor
    function toggleSlabEditor() {
        const type = document.getElementById('team_commission_type').value;
        const editor = document.getElementById('commission-slab-editor');
        const percentageInput = document.getElementById('percentage-input');
        
        if (type === 'slab') {
            editor.style.display = 'block';
            if (percentageInput) {
                percentageInput.style.display = 'none';
            }
            loadSlabs();
        } else {
            editor.style.display = 'none';
            if (percentageInput) {
                percentageInput.style.display = 'block';
            }
        }
    }
    
    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Commission type change
        const commissionType = document.getElementById('team_commission_type');
        if (commissionType) {
            commissionType.addEventListener('change', toggleSlabEditor);
        }
        
        // Add slab button
        const addSlabBtn = document.getElementById('add-slab-btn');
        if (addSlabBtn) {
            addSlabBtn.addEventListener('click', function() {
                addSlabRow();
            });
        }
        
        // Save slabs button
        const saveSlabsBtn = document.getElementById('save-slabs-btn');
        if (saveSlabsBtn) {
            saveSlabsBtn.addEventListener('click', function() {
                saveSlabs();
            });
        }
        
        // Initialize
        toggleSlabEditor();
    });
</script>
