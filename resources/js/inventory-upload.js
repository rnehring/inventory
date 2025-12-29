import { formatterUSD } from './app';
import { createInventoryGrid, truncateToTwoDecimals, addSearchLabel, createCurrencyCell, showGridLoading, hideGridLoading } from './utils/gridFactory';

let allData = [];
let currentData = [];

// Show loading spinner
showGridLoading('grid');

/**
 * Fetch and render upload data
 */
fetch('/get-uploaded-data')
    .then(res => res.json())
    .then(data => {
        allData = data.map(row => ({...row}));
        currentData = allData;
        renderGrid(allData);
        addSearchLabel();
    })
    .catch(error => {
        hideGridLoading('grid');
        console.error('Error fetching upload data:', error);
        if (window.showToast) {
            window.showToast('Failed to load upload data', 'error');
        }
    });

/**
 * Render the grid
 */
function renderGrid(data) {
    const columns = [
        { id: 'tag', name: 'Tag', width: '5%'},
        { id: 'part', name: 'Part', width: '10%'},
        { id: 'bin', name: 'Bin', width: '5%'},
        { id: 'lot_number', name: 'Lot #', width: '5%'},
        { id: 'serial_number', name: 'Serial #', width: '5%'},
        { id: 'uom', name: 'UOM', width: '4%'},
        { id: 'warehouse', name: 'Warehouse', hidden: true},
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
        }
    ];

    createInventoryGrid('grid', columns, data);
}
