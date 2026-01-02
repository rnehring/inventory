import { formatterUSD, csvButton } from './app';
import { createInventoryGrid, truncateToTwoDecimals, addSearchLabel, createCurrencyCell, showGridLoading, hideGridLoading } from './utils/gridFactory';
import { html } from "gridjs";

let allData = [];
let currentData = [];
let allUsers = [];

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

function getPlants() {
    return fetch('/get-plants')
        .then((response) => {
            return response.json().then((data) => {
                console.log(data);
                return data;
            }).catch((err) => {
                console.log(err);
            })
        });
}

function getUsers() {
    return fetch('/get-users')
        .then((response) => {
            return response.json().then((data) => {
                console.log(data);
                return data;
            }).catch((err) => {
                console.log(err);
            })
        });
}

/**
 * Fetch and render grid data
 */
fetch('/get-all-data')
    .then(res => res.json())
    .then(data => {
        allData = data.map(row => ({...row}));
        currentData = allData;
        createFilters(allData);
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
            id: 'tag_printed',
            formatter: (cell) => {
                if (cell == 1) {
                    return html('<span class="flex w-3 h-3 bg-green-500 rounded-full mx-auto"></span>');
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
 * Create a checkbox filter
 */
function createFilter(value, name, column){
    const span = document.createElement('span');
    span.className = 'flex-1 min-w-0';
    span.innerHTML = `
        <div class="flex items-center ps-3 dark:bg-gray-800 border border-gray-200 rounded-sm shadow-sm dark:border-gray-800 px-3 dark:cb-filters dark:hover:bg-gray-700 h-full">
            <input id="filter-${column}-${value}" type="checkbox" value="${value}" name="bordered-checkbox" data-column="${column}" class="bg-gray-600 w-4 h-4 border-2 border-gray-400 rounded-xs mr-2 filters shrink-0">
            <label for="filter-${column}-${value}" class="select-none py-2 text-sm dark:font-sans dark:font-bold cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis">${name}</label>
        </div>`;
    return span;
}

/**
 * Create user dropdown with checkboxes (filtered by selected plants)
 */
function createUserDropdown(users, selectedPlants = []) {
    const wrapper = document.createElement('span');
    wrapper.className = 'flex-1 min-w-0 relative';
    wrapper.id = 'user-dropdown-wrapper';

    const selectDiv = document.createElement('div');
    selectDiv.className = 'flex items-center ps-3 dark:bg-gray-800 border border-gray-200 rounded-sm shadow-sm dark:border-gray-800 px-3 dark:cb-filters dark:hover:bg-gray-700 h-full cursor-pointer';

    const button = document.createElement('button');
    button.type = 'button';
    button.id = 'user-dropdown-button';
    button.className = 'flex items-center justify-between w-full py-2 text-sm dark:font-sans dark:font-bold text-gray-300 min-w-0';
    button.innerHTML = `
        <span id="user-dropdown-label" class="whitespace-nowrap overflow-hidden text-ellipsis">Users <span class="text-blue-400" id="user-count"></span></span>
        <svg class="w-4 h-4 transition-transform ml-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    `;

    const dropdown = document.createElement('div');
    dropdown.id = 'user-dropdown-menu';
    dropdown.className = 'hidden absolute z-50 mt-1 w-full max-h-64 overflow-y-auto bg-gray-800 border border-gray-700 rounded-sm shadow-lg';
    dropdown.style.top = '100%';

    // Filter users based on selected plants (if any)
    let filteredUsers = users;
    if (selectedPlants.length > 0) {
        filteredUsers = users.filter(user => selectedPlants.includes(user.plant));
    }

    // Group users by plant
    const usersByPlant = {};
    filteredUsers.forEach(user => {
        if (!usersByPlant[user.plant]) {
            usersByPlant[user.plant] = [];
        }
        usersByPlant[user.plant].push(user);
    });

    // Create dropdown content grouped by plant
    Object.keys(usersByPlant).sort().forEach(plant => {
        // Plant header
        const plantHeader = document.createElement('div');
        plantHeader.className = 'px-4 py-2 text-xs font-bold text-gray-500 uppercase border-b border-gray-700 bg-gray-900';
        plantHeader.textContent = plant;
        dropdown.appendChild(plantHeader);

        // User checkboxes for this plant
        usersByPlant[plant].forEach(user => {
            const userName = user.initials || `${user.first_name} ${user.last_name}`.trim();
            const userItem = document.createElement('label');
            userItem.className = 'flex items-center px-4 py-2 hover:bg-gray-700 cursor-pointer';
            userItem.innerHTML = `
                <input type="checkbox"
                       value="${user.id}"
                       data-column="user"
                       class="bg-gray-600 w-4 h-4 border-2 border-gray-400 rounded-xs mr-2 user-filter">
                <span class="text-sm text-gray-300">${userName}</span>
            `;
            dropdown.appendChild(userItem);
        });
    });

    // If no users match the filter, show a message
    if (filteredUsers.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'px-4 py-3 text-sm text-gray-500 text-center';
        noResults.textContent = 'No users found for selected plants';
        dropdown.appendChild(noResults);
    }

    selectDiv.appendChild(button);
    wrapper.appendChild(selectDiv);
    wrapper.appendChild(dropdown);

    // Toggle dropdown
    button.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        button.querySelector('svg').classList.toggle('rotate-180');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
            button.querySelector('svg').classList.remove('rotate-180');
        }
    });

    return wrapper;
}

/**
 * Update user dropdown based on selected plants
 */
function updateUserDropdown(selectedPlants) {
    const wrapper = document.getElementById('user-dropdown-wrapper');
    if (!wrapper) return;

    // Store currently selected users
    const selectedUsers = Array.from(
        document.querySelectorAll('.user-filter:checked')
    ).map(cb => cb.value);

    // Remove old dropdown
    wrapper.remove();

    // Create new dropdown with filtered users
    const newDropdown = createUserDropdown(allUsers, selectedPlants);
    const filterContainer = document.getElementById('filters');
    filterContainer.appendChild(newDropdown);

    // Re-check previously selected users (if they're still in the list)
    selectedUsers.forEach(userId => {
        const checkbox = document.querySelector(`.user-filter[value="${userId}"]`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Add event listeners to new user checkboxes
    document.querySelectorAll('.user-filter').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateUserCount();
            filterGrid();
        });
    });

    updateUserCount();
}

/**
 * Update user count badge
 */
function updateUserCount() {
    const count = document.querySelectorAll('.user-filter:checked').length;
    const badge = document.getElementById('user-count');
    if (badge) {
        badge.textContent = count > 0 ? `(${count})` : '';
    }
}

/**
 * Create filter checkboxes and user dropdown
 */
async function createFilters(data) {
    let plants = await getPlants();
    let users = await getUsers();

    const filterContainer = document.getElementById('filters');
    if (!filterContainer) return;

    filterContainer.innerHTML = '';

    // Store users globally
    allUsers = users;

    // Add plant filters
    plants.forEach(plant => {
        let span = createFilter(plant['plant'], plant['display_name'], 'warehouse');
        filterContainer.appendChild(span);
    });

    // Add counted filter
    let countedSpan = createFilter('1', 'Counted', 'counted');
    filterContainer.appendChild(countedSpan);

    // Add user dropdown (initially shows all users grouped by plant)
    const userDropdown = createUserDropdown(users);
    filterContainer.appendChild(userDropdown);

    // Add event listeners to plant and counted filters
    document.querySelectorAll('.filters').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            // If it's a plant filter, update the user dropdown
            if (checkbox.dataset.column === 'warehouse') {
                const selectedPlants = Array.from(
                    document.querySelectorAll('.filters[data-column="warehouse"]:checked')
                ).map(cb => cb.value);

                updateUserDropdown(selectedPlants);
            }

            filterGrid();
        });
    });

    // Add event listeners to user filters
    document.querySelectorAll('.user-filter').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateUserCount();
            filterGrid();
        });
    });
}

/**
 * Filter grid based on selected filters
 */
function filterGrid() {
    console.log('filtering...');

    // Wait for grid to be ready if not yet available
    if (!window.gridInstance) {
        console.log('Grid not ready yet, waiting...');
        setTimeout(filterGrid, 100);
        return;
    }

    // Get all checked filters (plants and counted status)
    const checkedFilters = Array.from(
        document.querySelectorAll('.filters:checked')
    ).map(cb => ({
        value: cb.value,
        column: cb.dataset.column
    }));

    // Get selected users
    const selectedUsers = Array.from(
        document.querySelectorAll('.user-filter:checked')
    ).map(cb => ({
        value: cb.value,
        column: 'user'
    }));

    // Combine all filters
    const allFilters = [...checkedFilters, ...selectedUsers];

    console.log('All filters:', allFilters);

    let filteredData;

    if (allFilters.length === 0) {
        // No filters selected - show all data
        filteredData = allData;
    } else {
        // Group filters by column
        const filtersByColumn = {};
        allFilters.forEach(filter => {
            if (!filtersByColumn[filter.column]) {
                filtersByColumn[filter.column] = [];
            }
            filtersByColumn[filter.column].push(filter.value);
        });

        console.log('Filters by column:', filtersByColumn);

        // Filter data: row must match ALL columns (AND), but ANY value within a column (OR)
        filteredData = allData.filter(row => {
            // Check each column's filters
            for (const [column, values] of Object.entries(filtersByColumn)) {
                // Get the row's value for this column
                const rowValue = String(row[column]);

                // Check if row value matches ANY of the selected values for this column
                const matchesColumn = values.some(filterValue => {
                    return rowValue === filterValue;
                });

                // If doesn't match this column's filters, exclude the row
                if (!matchesColumn) {
                    return false;
                }
            }

            // Row matched all column filters
            return true;
        });
    }

    console.log(`Filtered ${filteredData.length} of ${allData.length} rows`);
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
