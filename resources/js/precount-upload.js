import { formatterUSD, csvButton } from './app';
import { createInventoryGrid, truncateToTwoDecimals, truncateString, addSearchLabel, createCurrencyCell, showGridLoading, hideGridLoading } from './utils/gridFactory';
import { html } from 'gridjs';

let allData = [];
let currentData = [];

// Show loading spinner
showGridLoading('grid');

fetch('/get-uploaded-precount-data')
    .then(res => res.json())
    .then(data => {
        allData = data.map(row => ({...row}));
        currentData = allData;
        renderGrid(allData);
        addSearchLabel();
    })
    .catch(error => {
        hideGridLoading('grid');
        console.error('Error fetching precount data:', error);
        if (window.showToast) {
            window.showToast('Failed to load precount data', 'error');
        }
    });

function renderGrid(data) {
    const columns = [
        { id: 'tag', name: 'Tag', width: '5%'},
        { id: 'part', name: 'Part', width: '10%'},
        {
            id: 'part_description',
            name: 'Part Description',
            width: '14%',
            formatter: (cell) => truncateString(`${cell}`, 40)
        },
        { id: 'bin', name: 'Bin', width: '5%'},
        { id: 'bin_description', name: 'Location', width: '9%'},
        { id: 'warehouse', name: 'Warehouse', hidden: true},
        { id: 'lot_number', name: 'Lot #', width: '5%'},
        { id: 'serial_number', name: 'Serial #', width: '5%'},
        { id: 'uom', name: 'UOM', width: '4%'},
        {
            id: 'by_weight',
            formatter: (cell) => {
                if (cell == 1) {
                    return html('<span>&#10003;</span>');
                }
                return html('<span></span>');
            },
            name: 'By Weight?',
            width: '4%'
        },
        {
            id: 'expected_qty',
            name: 'Exp Qty',
            width: '5%',
            formatter: (cell) => truncateToTwoDecimals(`${cell}`)
        },
        {
            id: 'standard_cost',
            name: 'Cost',
            formatter: (cell) => createCurrencyCell(cell, formatterUSD),
            width: '4%'
        },
        {
            id: 'cost_expected',
            name: 'Expected',
            formatter: (cell) => createCurrencyCell(cell, formatterUSD),
            width: '5%'
        }
    ];

    createInventoryGrid('grid', columns, data);
}


