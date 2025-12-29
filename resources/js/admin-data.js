import { formatterUSD, csvButton } from './app';
import { createInventoryGrid, truncateToTwoDecimals, addSearchLabel, createCurrencyCell, showGridLoading, hideGridLoading } from './utils/gridFactory';
import { html } from "gridjs";

let allData = [];
let currentData = [];

// Show loading spinner immediately
showGridLoading('grid');


/**
 * Add CSV export button to grid header
 */
function addExportButton(){
    const gridHead = document.getElementsByClassName('gridjs-head');
    if (gridHead.length > 0) {
        const csvExportButton = document.createElement('div');
        csvExportButton.id = 'export-csv';
        csvExportButton.classList = 'block mx-auto float-right';
        csvExportButton.innerHTML = csvButton;
        gridHead[0].append(csvExportButton);
        document.getElementById('export-csv').addEventListener('click', exportToCSV);
    }
}

/**
 * Fetch and render grid data
 */
fetch('/get-all-data')
    .then(res => res.json())
    .then(data => {
        allData = data.map(row => ({...row}));
        currentData = allData;
        createPlantFilters(allData);
        renderGrid(allData);
        addSearchLabel();
        addExportButton();
    })
    .catch(error => {
        hideGridLoading('grid');
        console.error('Error fetching data:', error);
        if (window.showToast) {
            window.showToast('Failed to load inventory data', 'error');
        }
    });

/**
 * Render the grid with given data
 */
function renderGrid(data) {
    const columns = [
        {
            id: 'counted',
            name: '',
            width: '3%',
            formatter: (cell) => {
                if (cell == 1) {
                    return html('<span class="flex w-3 h-3 bg-green-500 rounded-full mx-auto"></span>');
                }
                return html('<span></span>');
            }
        },
        { id: 'id', name: 'ID', hidden: true},
        { id: 'tag', name: 'Tag', width: '5%'},
        {
            id: 'tag_status',
            formatter: (cell) => {
                if (cell == 1) {
                    return html('<span>Printed</span>');
                }
                return html('<span></span>');
            },
            name: 'Tag Printed',
            width: '4%'
        },
        { id: 'part', name: 'Part', width: '10%'},
        { id: 'bin', name: 'Bin', width: '5%'},
        { id: 'lot_number', name: 'Lot #', width: '5%'},
        { id: 'serial_number', name: 'Serial #', width: '5%'},
        { id: 'count', name: 'Count', width: '4%'},
        { id: 'user', name: 'User', hidden: true},
        { id: 'uom', name: 'UOM', width: '4%'},
        {
            id: 'by_weight',
            formatter: (cell) => {
                if (cell == 1) {
                    return html('<span>&#10003;</span>');
                }
                return html('<span></span>');
            },
            name: 'Weight',
            width: '4%'
        },
        {
            id: 'expected_qty',
            name: 'Exp Qty',
            width: '5%',
            formatter: (cell) => truncateToTwoDecimals(cell)
        },
        {
            id: 'standard_cost',
            name: 'Cost',
            formatter: (cell) => createCurrencyCell(cell, formatterUSD),
            width: '4%'
        },
        { id: 'date_counted', name: 'Date Counted', hidden: true},
        { id: 'time_counted', name: 'Time Counted', hidden: true},
        {
            id: 'cost_expected',
            name: 'Expected',
            formatter: (cell) => createCurrencyCell(cell, formatterUSD),
            width: '5%'
        },
        {
            id: 'cost_counted',
            name: 'Counted',
            formatter: (cell) => createCurrencyCell(cell, formatterUSD),
            width: '5%'
        },
        {
            id: 'plus_minus',
            name: '+/-',
            formatter: (cell) => createCurrencyCell(cell, formatterUSD),
            width: '4%'
        }
    ];

    createInventoryGrid('grid', columns, data);
}

/**
 * Create plant filter checkboxes
 */
function createPlantFilters(data) {
    const warehouseSet = new Set();

    data.forEach(row => {
        if (row.warehouse) {
            warehouseSet.add(row.warehouse);
        }
    });

    console.log(warehouseSet);

    const filterContainer = document.getElementById('plant-filters');
    if (!filterContainer) return;

    filterContainer.innerHTML = '';

    warehouseSet.forEach(warehouse => {
        const li = document.createElement('li');
        li.className = 'w-48 border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600';
        li.innerHTML = `
            <div class="flex items-center ps-3">
                <input id="plants" name="plants[]" type="checkbox" value="${warehouse}"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 plant-filter">
                <label for="plants" class="w-48 py-3 ms-2 text-sm font-bold text-gray-900 dark:text-gray-300">${warehouse}</label>
            </div>
        `;
        filterContainer.appendChild(li);
    });

    // Add event listeners
    document.querySelectorAll('.plant-filter').forEach(checkbox => {
        checkbox.addEventListener('change', filterGrid);
    });
}

/**
 * Filter grid based on selected warehouses
 */
function filterGrid() {
    // Wait for grid to be ready if not yet available
    if (!window.gridInstance) {
        console.log('Grid not ready yet, waiting...');
        setTimeout(filterGrid, 100);
        return;
    }

    const checkedPlants = Array.from(
        document.querySelectorAll('.plant-filter:checked')
    ).map(cb => cb.value);

    let filteredData;

    if (checkedPlants.length === 0) {
        filteredData = allData;
    } else {
        filteredData = allData.filter(row =>
            checkedPlants.includes(row.warehouse)
        );
    }

    currentData = filteredData;

    // Update the grid
    window.gridInstance.updateConfig({
        data: filteredData
    }).forceRender();

    addExportButton();
}

/**
 * Convert JSON to CSV
 */
function jsonToCSV(data) {
    if (data.length === 0) return '';

    const headers = Object.keys(data[0]);
    const csvHeaders = headers.join(',');

    const csvRows = data.map(row => {
        return headers.map(header => {
            let cell = row[header];

            if (cell === null || cell === undefined) {
                cell = '';
            }

            cell = String(cell);
            if (cell.includes(',') || cell.includes('"') || cell.includes('\n')) {
                cell = `"${cell.replace(/"/g, '""')}"`;
            }

            return cell;
        }).join(',');
    });

    return [csvHeaders, ...csvRows].join('\n');
}

/**
 * Export current data to CSV
 */
function exportToCSV() {
    const csv = jsonToCSV(currentData);
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');

    const date = new Date().toISOString().split('T')[0];
    link.download = `inventory-export-${date}.csv`;
    link.href = url;
    link.click();

    window.URL.revokeObjectURL(url);
}
