import { Grid } from "gridjs";
import { html } from "gridjs";
import { formatterUSD, epicorCodeToCompanyName } from './app';

let allData = []; // Store all data

function truncateString(str, maxLength) {
    if (str.length <= maxLength) {
        return str;
    } else {
        return str.slice(0, maxLength - 3) + "...";
    }
}

// Fetch data once and store it
fetch('/get-all-data')
    .then(res => res.json())
    .then(data => {
        allData = data;
        createCompanyFilters(data);
        renderGrid(data); // Initial render with all data
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
            { id: 'part', name: 'Part', width: '9%'},
            {
                id: 'part_description',
                name: 'Part Description',
                width: '14%',
                formatter: (cell) => truncateString(`${cell}`, 40)
            },
            { id: 'bin', name: 'Bin', width: '5%'},
            { id: 'description', name: 'Location', width: '8%'},
            {
                id: 'company',
                name: 'Company',
                width: '6%',
                formatter: (cell) => epicorCodeToCompanyName(`${cell}`)
            },
            { id: 'lot_number', name: 'Lot #', width: '5%'},
            { id: 'serial_number', name: 'Serial #', width: '5%'},
            { id: 'count', name: 'Count', width: '4%'},
            { id: 'user', name: 'User', hidden: true},
            { id: 'uom', name: 'UOM', width: '4%'},
            { id: 'by_weight', name: 'By Weight', width: '4%'},
            { id: 'expected_qty', name: 'Expected Qty', width: '7%'},
            {
                id: 'standard_cost',
                name: 'Cost',
                formatter: (cell) => formatterUSD.format(`${cell}`)
            },
            { id: 'date_counted', name: 'Date Counted', hidden: true},
            { id: 'time_counted', name: 'Time Counted', hidden: true},
            {
                id: 'cost_expected',
                name: 'Expected',
                formatter: (cell) => formatterUSD.format(`${cell}`),
                width: '3%',
                attributes: { 'style': 'text-align: right' }
            },
            {
                id: 'cost_counted',
                name: 'Counted',
                formatter: (cell) => formatterUSD.format(`${cell}`),
                width: '3%',
                attributes: { 'style': 'text-align: right' }
            },
            {
                id: 'plus_minus',
                name: '+/-',
                formatter: (cell) => formatterUSD.format(`${cell}`),
                width: '3%',
                attributes: { 'style': 'text-align: right' }
            },
        ],
        data: data, // Use client-side data
        sort: true,
        height: '900px',
        pagination: {
            limit: 18
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
<label for="companies" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">${epicorCodeToCompanyName(company)}</label>
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
}
