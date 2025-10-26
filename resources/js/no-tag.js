import {
    epicorCodeToCompanyName,
    updateTextColors,
    formatToTwoDigits,
    makeCell,
    makeRow,
    setCells,
    formatterUSD,
    showToast
} from './app';
setCells();

const submitButton = document.getElementById("add-notag");
submitButton.addEventListener("click", addNoTag);
updateTextColors();


function saveCount(event, partid){
    let count = document.getElementById('count'+partid).value;
    console.log(count);
}

function addNoTag(event) {
    event.preventDefault();
    submitButton.disabled = true;
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Processing...';

    let allRows = document.querySelectorAll('tr');
    let checkbox = document.getElementById('by_weight');
    let isChecked = checkbox.checked;

    axios.post('/notag/save', {
        part: document.getElementById('part').value,
        bin: document.getElementById('bin').value,
        count: document.getElementById('count').value,
        uom: document.getElementById('uom').value,
        by_weight: isChecked,
        company: document.getElementById('company').value,
        warehouse: document.getElementById('warehouse').value,
        lot_number: document.getElementById('lot_number').value,
        serial_number: document.getElementById('serial_number').value
    })
        .then(function (response) {

            console.log(response);
            let tableBody = document.getElementById('noTagData').getElementsByTagName('tbody')[0];


            let part = response.data;

            let part_number = makeCell(`${part.part}`, 'px-2 py-4');
            let count = makeCell(`${part.count}`, 'text-center');
            let bin = makeCell(`${part.bin}`, 'text-center');
            let uom = makeCell(`${part.uom}`, 'text-center');
            let by_weight = makeCell(`${part.by_weight}` == 1 ? "Yes" : "No", 'text-center');
            let company = makeCell(`${epicorCodeToCompanyName(part.company)}`, 'text-center');
            let warehouse = makeCell(`${part.warehouse}`, 'text-center');
            let lot_number = makeCell(`${part.lot_number}`, 'text-center');
            let serial_number = makeCell(`${part.serial_number}`, 'text-center');
            let standard_cost = makeCell(`${formatterUSD.format(part.standard_cost)}`, 'text-right');
            let cost_counted = makeCell(`${formatterUSD.format(part.cost_counted)}`, 'text-right');
            let thisRow = makeRow(part);
            tableBody.appendChild(thisRow);
            cells = [];

            let row = document.getElementById('row'+part['id']);
            row.classList = ('bg-green-300');
            updateTextColors();

            showToast('No Tag Part Count Added!');

        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                const form = document.querySelector('#NoTagForm');
                form.reset();
            }, 3000);
        });
}

