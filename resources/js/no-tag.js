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


axios.get('/get-part-numbers',)
    .then(function (response) {
        let partNumbers = response.data;
        console.log(partNumbers);
        let partField = document.getElementById('part');

        partField.addEventListener('change', function(event){
            const performSearch = (partNumbers, query) => {
                if ( partNumbers.includes(query) ) {
                    return true;
                }
                return false;
            };

            let query = event.target.value;
            console.log(query);
            let partExists = performSearch(partNumbers, query);

            if (partExists) {
               partField.style.backgroundColor = "rgb(55, 65, 81)";
               let partFieldError = document.getElementById('partError');
               if( partFieldError ){
                   let partFieldDiv = partFieldError.parentElement;
                   partFieldDiv.removeChild(partFieldError);
               }
                var submitButton = document.getElementById('add-notag');
                submitButton.disabled = false;
            } else {
                let partFieldDiv = partField.parentElement;
                partField.style.backgroundColor = "#c40000";
                let partFieldError = document.getElementById('partError');
                if( partFieldError ){
                    partFieldDiv.removeChild(partFieldError);
                }
                let error = document.createElement("p");
                error.id = "partError";
                error.style.color = "#c40000";
                error.style.marginBottom = "4px";
                error.style.fontWeight = "bold";
                error.textContent = "Part Number Not Found.";
                partFieldDiv.appendChild(error);
                var submitButton = document.getElementById('add-notag');
                submitButton.disabled = true;
            }
        });
    })

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
            let warehouse = makeCell(`${part.warehouse}`, 'text-center');
            let lot_number = makeCell(`${part.lot_number}`, 'text-center');
            let serial_number = makeCell(`${part.serial_number}`, 'text-center');
            if(userType == 2){
                let standard_cost = makeCell(`${formatterUSD.format(part.standard_cost)}`, 'text-right');
                let cost_counted = makeCell(`${formatterUSD.format(part.cost_counted)}`, 'text-right');
            }
            let edit_link = makeCell(`<a href="notag/update/${part.id}"> Edit </a>`, 'text-center');
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

