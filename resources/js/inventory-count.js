import {
    updateTextColors,
    formatToTwoDigits,
    makeCell,
    makeRow,
    setCells,
    formatterUSD,
    showToast
} from './app';

setCells();
const submitButton = document.getElementById("get-part");
submitButton.addEventListener("click", getPart);
updateTextColors();

// Initialize Autocomplete for Part and Bin fields
window.onload = function () {
    const autoCompleteBins = new autoComplete({
        selector: "#bin",
        placeHolder: "Search Bins...",
        searchEngine: "strict",
        data: {
            src: acBins,
            filter: (list) => {
                const query = autoCompleteBins.input.value.toLowerCase();

                return list.sort((a, b) => {
                    const aVal = a.value.toLowerCase();
                    const bVal = b.value.toLowerCase();

                    const aStarts = aVal.startsWith(query);
                    const bStarts = bVal.startsWith(query);

                    if (aStarts && !bStarts) return -1;
                    if (!aStarts && bStarts) return 1;

                    return aVal.localeCompare(bVal);
                });
            }
        },
        resultItem: {
            highlight: true,
        }
    });

    const autoCompleteParts = new autoComplete({
        selector: "#part",
        placeHolder: "Search Parts...",
        searchEngine: "strict",
        data: {
            src: acParts,
            filter: (list) => {
                const query = autoCompleteParts.input.value.toLowerCase();

                return list.sort((a, b) => {
                    const aVal = a.value.toLowerCase();
                    const bVal = b.value.toLowerCase();

                    const aStarts = aVal.startsWith(query);
                    const bStarts = bVal.startsWith(query);

                    if (aStarts && !bStarts) return -1;
                    if (!aStarts && bStarts) return 1;

                    return aVal.localeCompare(bVal);
                });
            }
        },
        resultItem: {
            highlight: true,
        }
    });

    document.querySelector("#part").addEventListener("selection", function (event) {
        document.querySelector("#part").value = event.detail.selection.value;
    });

    document.querySelector("#bin").addEventListener("selection", function (event) {
        document.querySelector("#bin").value = event.detail.selection.value;
    });
}

function getPart(event) {
    event.preventDefault();

    axios.post('/inventory-search', {
        part: document.getElementById('part').value,
        bin: document.getElementById('bin').value
    })
        .then(function (response) {
            let tableBody = document.getElementById('partData').getElementsByTagName('tbody')[0];
            let newTbody = document.createElement('tbody');
            tableBody.parentNode.replaceChild(newTbody, tableBody);

            let parts = response.data;

            if (parts.length === 0) {
                showToast('No parts found matching your search', 'info');
                return;
            }

            parts.forEach((part) => {
                let top_eighty_star = part['top_eighty'] == 1 ? 
                    makeCell(part['top_eighty_star']) : 
                    makeCell('');
                    
                let tag = makeCell(part['tag']);
                let tag_printed = part['tag_printed'] == 1 ? 
                    makeCell('Printed') : 
                    makeCell('');
                    
                let part_number = makeCell(part['part']);
                let part_warehouse = makeCell(part['warehouse']);
                let bin = makeCell(part['bin'], 'text-center');
                let uom = makeCell(part['uom'], 'text-center');
                let count = makeCell(`<input type='text' name='count' id='count${part['id']}' class='count text-right px-2 py-0 mx-auto block rounded-sm border-gray-600 w-20' value='${part['count']}' onfocus="this.value=''"/>`);
                let by_weight = makeCell(`<input type='checkbox' class='mx-auto block' id='by_weight' ${part['by_weight'] === 1 ? 'checked' : ''}/>`);
                let lot_number = makeCell(part['lot_number']);
                let serial_number = makeCell(part['serial_number']);
                let expected_qty = makeCell(formatToTwoDigits(part['expected_qty']), 'text-right');
                
                if(userType == 2){
                    let standard_cost = makeCell(formatterUSD.format(part['standard_cost']), 'text-right');
                    let cost_counted = makeCell(formatterUSD.format(part['cost_counted']), 'text-right');
                    let cost_expected = makeCell(formatterUSD.format(part['cost_expected']), 'text-right');
                    let plus_minus = makeCell(formatterUSD.format(part['plus_minus']), 'text-right');
                }

                let save_button = makeCell(`<button type="button" data-part_id='${part['id']}' name='saveCount' class="justify-center px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><svg id='Layer_1' class='w-3 h-3 text-white me-2' fill='currentColor' viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'><path d='M22,4h-2v6c0,0.552-0.448,1-1,1h-9c-0.552,0-1-0.448-1-1V4H6C4.895,4,4,4.895,4,6v18c0,1.105,0.895,2,2,2h18  c1.105,0,2-0.895,2-2V8L22,4z M22,24H8v-6c0-1.105,0.895-2,2-2h10c1.105,0,2,0.895,2,2V24z'/><rect height="5" width="2" x="16" y="4"/></svg>Save</button>`, 'text-center');
                
                save_button.addEventListener('click', function(event){
                    const countValue = document.getElementById('count'+part['id']).value;
                    
                    // Client-side validation
                    if (!countValue || isNaN(countValue) || parseFloat(countValue) < 0) {
                        showToast('Please enter a valid count (positive number)', 'error');
                        return;
                    }
                    
                    axios.post('/update-count', {
                        count: countValue,
                        part: part['id']
                    })
                    .then(function (response) {
                        let row = document.getElementById('row'+part['id']);
                        row.classList = ('bg-green-300');
                        updateTextColors();
                        row.cells[2].textContent = 'Printed';
                        
                        if (userType == 2) {
                            row.cells[13].textContent = formatterUSD.format(response.data[0]['cost_counted']);
                            row.cells[15].textContent = formatterUSD.format(response.data[0]['plus_minus']);
                        }
                        
                        showToast('Part Count Updated!');
                    })
                    .catch(function (error) {
                        const message = error.response?.data?.message || 'Failed to update count';
                        showToast(message, 'error');
                        console.error('Error updating count:', error);
                    });
                });

                let thisRow = makeRow(part);
                newTbody.appendChild(thisRow);
                cells = [];
                updateTextColors();
            });
        })
        .catch(function (error) {
            const message = error.response?.data?.message || 'Error searching for parts';
            showToast(message, 'error');
            console.error('Error searching parts:', error);
        });
}
