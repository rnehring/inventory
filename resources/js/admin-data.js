import { Grid } from "gridjs";
import { html } from "gridjs";
import { formatterUSD, epicorCodeToCompanyName, csvButton } from './app';

let allData = []; // Store all data
let currentData = [];

function truncateString(str, maxLength) {
    if (str.length <= maxLength) {
        return str;
    } else {
        return str.slice(0, maxLength - 3) + "...";
    }
}

function truncateToTwoDecimals(num) {
    // Multiply by 100 to shift two decimal places to the left
    const multiplied = num * 100;

    // Use Math.trunc() to remove the remaining decimal part
    const truncated = Math.trunc(multiplied); // or Math.floor() for positive numbers

    // Divide by 100 to shift the decimal back to its original position
    const result = truncated / 100;

    return result;
}

function addExportButton(){
    const gridHead = document.getElementsByClassName('gridjs-head');
    const csvExportButton = document.createElement('div');
    csvExportButton.id = 'export-csv';
    csvExportButton.classList = 'block mx-auto float-right';
    csvExportButton.innerHTML = csvButton;
    gridHead[0].append(csvExportButton);
    document.getElementById('export-csv').addEventListener('click', exportToCSV);
}

// Fetch data once and store it
fetch('/get-all-data')
    .then(res => res.json())
    .then(data => {
        allData = data.map(row => ({
            ...row,
            company_code: row.company, // Store original code
            company: epicorCodeToCompanyName(row.company) // Replace with full name
        }));
        currentData = allData;
        createCompanyFilters(allData);
        renderGrid(allData);
        const gridHead = document.getElementsByClassName('gridjs-head');
        const label = document.createElement('label');
        label.innerHTML = `<p class="float-left mr-2 ml-1 font-bold text-lg leading-10">Search</p>`;
        gridHead[0].prepend(label);
        addExportButton();
    });

function renderGrid(data) {
    const grid = new Grid({
        columns: [
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
            { id: 'part', name: 'Part', width: '10%'},
            {
                id: 'part_description',
                name: 'Part Description',
                width: '14%',
                formatter: (cell) => truncateString(`${cell}`, 40)
            },
            { id: 'bin', name: 'Bin', width: '5%'},
            { id: 'description', name: 'Location', width: '9%'},
            {
                id: 'company',
                name: 'Company',
                width: '6%',
            },
            { id: 'company_code', name: 'Company Code', hidden: true},
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
                formatter: (cell) => truncateToTwoDecimals(`${cell}` )
            },
            {
                id: 'standard_cost',
                name: 'Cost',
                formatter: (cell) => html(`<span style="font-family: monospace">${formatterUSD.format(cell)}</span>`),
                width: '4%'
            },
            { id: 'date_counted', name: 'Date Counted', hidden: true},
            { id: 'time_counted', name: 'Time Counted', hidden: true},
            {
                id: 'cost_expected',
                name: 'Expected',
                formatter: (cell) => html(`<span style="font-family: monospace">${formatterUSD.format(cell)}</span>`),
                width: '5%'
            },
            {
                id: 'cost_counted',
                name: 'Counted',
                formatter: (cell) => html(`<span style="font-family: monospace">${formatterUSD.format(cell)}</span>`),
                width: '5%'
            },
            {
                id: 'plus_minus',
                name: '+/-',
                formatter: (cell) => html(`<span style="font-family: monospace">${formatterUSD.format(cell)}</span>`),
                width: '4%'
            },
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

// Create checkboxes based on unique company values
function createCompanyFilters(data) {
    const companySet = new Set();

    data.forEach(row => {
        if (row.company) {
            companySet.add(row.company);
        }
    });

    const filterContainer = document.getElementById('company-filters');
    filterContainer.innerHTML = ''; // Clear existing

    companySet.forEach(company => {
        const li = document.createElement('li');
        li.className = 'w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600';
        li.innerHTML = `
<div class="flex items-center ps-3">
<input id="companies" name="companies[]" type="checkbox" value="${company}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500  company-filter">
<label for="companies" class="w-full py-3 ms-2 text-sm font-bold text-gray-900 dark:text-gray-300">${company}</label>
</div>`;
        filterContainer.appendChild(li);
    });

    // Add event listeners
    document.querySelectorAll('.company-filter').forEach(checkbox => {
        checkbox.addEventListener('change', filterGrid);
    });
}

// Filter the grid based on selected checkboxes
function filterGrid() {
    const checkedCompanies = Array.from(
        document.querySelectorAll('.company-filter:checked')
    ).map(cb => cb.value);

    let filteredData;

    if (checkedCompanies.length === 0) {
        // No filters selected, show all
        filteredData = allData;
    } else {
        // Filter data
        filteredData = allData.filter(row =>
            checkedCompanies.includes(row.company)
        );
    }

    // Update the grid with filtered data
    window.gridInstance.updateConfig({
        data: filteredData
    }).forceRender();
    addExportButton();
}


// Convert JSON to CSV
function jsonToCSV(data) {
    if (data.length === 0) return '';

    // Get headers from the first object
    const headers = Object.keys(data[0]);

    // Create header row
    const csvHeaders = headers.join(',');

    // Create data rows
    const csvRows = data.map(row => {
        return headers.map(header => {
            let cell = row[header];

            // Handle special cases
            if (cell === null || cell === undefined) {
                cell = '';
            }

            // Convert company codes to names
            if (header === 'company') {
                cell = epicorCodeToCompanyName(cell);
            }

            // Escape quotes and wrap in quotes if contains comma, quote, or newline
            cell = String(cell);
            if (cell.includes(',') || cell.includes('"') || cell.includes('\n')) {
                cell = `"${cell.replace(/"/g, '""')}"`;
            }

            return cell;
        }).join(',');
    });

    return [csvHeaders, ...csvRows].join('\n');
}

// Export to CSV and trigger download
function exportToCSV() {
    const csv = jsonToCSV(currentData);
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    let filenamecompanies = '';

    let checkedCompanies = Array.from(
        document.querySelectorAll('.company-filter:checked')
    )

    checkedCompanies.forEach( function(company){
        filenamecompanies += epicorCodeToCompanyName(company.value).toLowerCase() + '_';
    })

    if( checkedCompanies.length == 6 || checkedCompanies.length == 0){
        filenamecompanies = '';
    }

    const date = new Date().toISOString().split('T')[0];
    link.download = filenamecompanies + `inventory-export-${date}.csv`;
    link.href = url;
    link.click();

    window.URL.revokeObjectURL(url);
}
