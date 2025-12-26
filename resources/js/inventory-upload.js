import { Grid } from "gridjs";
import { html } from "gridjs";
import { formatterUSD, csvButton } from './app';

let allData = [];
let currentData = [];

function truncateString(str, maxLength) {
    if (str.length <= maxLength) {
        return str;
    } else {
        return str.slice(0, maxLength - 3) + "...";
    }
}

function truncateToTwoDecimals(num) {
    const multiplied = num * 100;
    const truncated = Math.trunc(multiplied); // or Math.floor() for positive numbers
    const result = truncated / 100;
    return result;
}

fetch('/get-uploaded-data')
    .then(res => res.json())
    .then(data => {
        allData = data.map(row => ({
            ...row,
        }));
        currentData = allData;
        renderGrid(allData);
        const gridHead = document.getElementsByClassName('gridjs-head');
        const label = document.createElement('label');
        label.innerHTML = `<p class="float-left mr-2 ml-1 font-bold text-lg leading-10">Search</p>`;
        gridHead[0].prepend(label);
    });

function renderGrid(data) {
    const grid = new Grid({
        columns: [
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
                formatter: (cell) => truncateToTwoDecimals(`${cell}` )
            },
            {
                id: 'standard_cost',
                name: 'Cost',
                formatter: (cell) => html(`<span style="font-family: monospace">${formatterUSD.format(cell)}</span>`),
                width: '4%'
            }
        ],
        data: data, // Use client-side data
        sort: true,
        height: '900px',
        pagination: {
            limit: 18,
            buttonsCount: 10
        },
        fixedHeader: true,
        autoWidth: true,
        search: true,
    });

    grid.render(document.getElementById("grid"));

    // Store grid reference globally so filterGrid can access it
    window.gridInstance = grid;
}




