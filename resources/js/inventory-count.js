const submitButton = document.getElementById("get-part");
submitButton.addEventListener("click", getPart);

let cells = [];

const formatterUSD = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

function makeCell(value, classes){
    let cell = document.createElement('td');
    cell.innerHTML = value;
    cell.classList = 'text-gray-400 px-2 border-b ' + classes;
    cells.push(cell);
    return cell;
}


function makeRow(part){
    let row = document.createElement('tr');
        row.id = `row${part['id']}`;
        var classes = "";
        if(part['top_eighty'] == '1' && part['counted'] == '1') {
            classes += ' bg-green-300';
        }
        if(part['top_eighty'] == '1' && part['counted'] == '0') {
            classes += 'bg-yellow-300';
        }
        if(part['top_eighty'] == '0' && part['counted'] == '1') {
            classes += 'bg-green-300';
        }
        row.classList = `${classes}`;
        row.dataset.lot_number = `${part['lot_number']}`;
        row.dataset.serial_number = `${part['serial_number']}`;
    cells.forEach((cell) => {
        row.appendChild(cell);
    });

    return row;
}


function saveCount(event, partid){
    let count = document.getElementById('count'+partid).value;
    console.log(count);
}

function getPart(event) {
    event.preventDefault();

    let allRows = document.querySelectorAll('tr');
    console.log(allRows);

    axios.post('/inventory-search', {
        part: document.getElementById('part').value,
        bin: document.getElementById('bin').value
    })
        .then(function (response) {
            let tableBody = document.getElementById('partData').getElementsByTagName('tbody')[0];
            let newTbody = document.createElement('tbody');
            tableBody.parentNode.replaceChild(newTbody, tableBody);

            let parts = response.data;

            parts.forEach((part) => {
                let tag = makeCell(`${part['tag']}`);
                let part_number = makeCell(`${part['part']}`);
                let uom = makeCell(`${part['uom']}`, 'text-center');
                let count = makeCell(`<input type='text' name='count' id='count${part['id']}' class='text-right px-2 mx-auto block' value='${part['count']}' />`);
                let by_weight = makeCell(`<input type='checkbox' class='mx-auto block' id='by_weight' ${part['by_weight'] === 1 ? 'checked />' : '/>'}`);
                let lot_number = makeCell(`${part['lot_number']}`);
                let serial_number = makeCell(`${part['serial_number']}`);
                let expected_qty = makeCell(`${part['expected_qty']}`, 'text-right');
                let standard_cost = makeCell(`${formatterUSD.format(part['standard_cost'])}`, 'text-right');
                let cost_counted = makeCell(`${formatterUSD.format(part['cost_counted'])}`, 'text-right');
                let cost_expected = makeCell(`${formatterUSD.format(part['cost_expected'])}`, 'text-right');
                let plus_minus = makeCell(`${formatterUSD.format(part['plus_minus'])}`, 'text-right');
                let save_button = makeCell(`<button type="button" data-part_id = '${part['id']}' name='saveCount' class="justify-center px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><svg id='Layer_1' class='w-3 h-3 text-white me-2' fill='currentColor' viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'><path d='M22,4h-2v6c0,0.552-0.448,1-1,1h-9c-0.552,0-1-0.448-1-1V4H6C4.895,4,4,4.895,4,6v18c0,1.105,0.895,2,2,2h18  c1.105,0,2-0.895,2-2V8L22,4z M22,24H8v-6c0-1.105,0.895-2,2-2h10c1.105,0,2,0.895,2,2V24z'/><rect height="5" width="2" x="16" y="4"/></svg>Save</button>`, 'text-center');
                save_button.addEventListener('click', function(event){
                    axios.post('/update-count', {
                        count: document.getElementById('count'+part['id']).value,
                        part: part['id']
                    })
                    .then(function (response) {
                        alert("Updated!");
                    });
                });

                let thisRow = makeRow(part);
                newTbody.appendChild(thisRow);
                cells = [];
            });



        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
}

