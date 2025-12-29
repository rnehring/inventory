import { Grid, html } from "gridjs";

/**
 * Show loading spinner for grid
 */
const loadingStartTimes = {};

export function showGridLoading(containerId) {
    loadingStartTimes[containerId] = Date.now();
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const loadingDiv = document.createElement('div');
    loadingDiv.id = `${containerId}-loading`;
    loadingDiv.className = 'flex items-center justify-center p-12';
    loadingDiv.innerHTML = `
        <div class="flex flex-col items-center">
            <svg class="animate-spin h-12 w-12 text-blue-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-400 text-lg font-semibold">Loading data...</p>
        </div>
    `;
    
    container.innerHTML = '';
    container.appendChild(loadingDiv);
}

/**
 * Hide loading spinner for grid (simple version for error handling)
 */
export function hideGridLoading(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = '';
    }
    delete loadingStartTimes[containerId];
}

/**
 * Create a reusable inventory grid with loading state
 */
export function createInventoryGrid(containerId, columns, data, options = {}) {
    const defaultOptions = {
        sort: true,
        height: '900px',
        pagination: {
            limit: 18,
            buttonsCount: 10
        },
        fixedHeader: true,
        autoWidth: true,
        search: true,
        className: {
            table: 'gridjs-table',
            th: 'gridjs-th',
            td: 'gridjs-td'
        }
    };

    // Get the container
    const container = document.getElementById(containerId);
    if (!container) {
        console.error(`Container ${containerId} not found`);
        return null;
    }

    // Calculate minimum display time for loading spinner
    const minDisplayTime = 500;
    const startTime = loadingStartTimes[containerId] || Date.now();
    const elapsedTime = Date.now() - startTime;
    const remainingTime = Math.max(0, minDisplayTime - elapsedTime);

    // Function to actually render the grid
    const renderGrid = () => {
        // Clear container (remove loading spinner)
        container.innerHTML = '';
        delete loadingStartTimes[containerId];

        // Create and render grid
        const grid = new Grid({
            columns,
            data,
            ...defaultOptions,
            ...options
        });

        grid.render(container);
        
        // Store grid reference globally if needed
        window.gridInstance = grid;
        
        return grid;
    };

    // If we need to wait for minimum display time, delay the grid render
    if (remainingTime > 0) {
        setTimeout(renderGrid, remainingTime);
        return null; // Grid will be available as window.gridInstance after timeout
    } else {
        // Render immediately
        return renderGrid();
    }
}

/**
 * Truncate a number to two decimal places
 */
export function truncateToTwoDecimals(num) {
    if (num === null || num === undefined) return 0;
    return Math.trunc(num * 100) / 100;
}

/**
 * Truncate a string to a maximum length
 */
export function truncateString(str, maxLength) {
    if (!str) return '';
    if (str.length <= maxLength) {
        return str;
    }
    return str.slice(0, maxLength - 3) + "...";
}

/**
 * Add search label to grid
 */
export function addSearchLabel() {
    const gridHead = document.getElementsByClassName('gridjs-head');
    if (gridHead.length > 0) {
        const label = document.createElement('label');
        label.innerHTML = `<p class="float-left mr-2 ml-1 font-bold text-lg leading-10">Search</p>`;
        gridHead[0].prepend(label);
    }
}

/**
 * Create a currency cell with monospace formatting
 */
export function createCurrencyCell(value, formatter) {
    return html(`<span style="font-family: monospace">${formatter.format(value)}</span>`);
}
