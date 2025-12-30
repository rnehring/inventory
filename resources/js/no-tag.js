
import {
    updateTextColors,
    makeCell,
    makeRow,
    setCells,
    formatterUSD,
    showToast,
    plantCodeToName
} from './app';

setCells();

const submitButton = document.getElementById("add-notag");
const lotField = document.getElementById("lot_number");
const lotFieldDiv = lotField.parentElement;
const serialField = document.getElementById("serial_number");
const serialFieldDiv = serialField.parentElement;
const partField = document.getElementById("part");
const partFieldDiv = partField.parentElement;
const binField = document.getElementById("bins");
const binFieldDiv = binField.parentElement;

function buildError(field, message){
    let fieldParent = field.parentElement;
    let error = document.createElement("p");
    error.id = field.name+"Error";
    error.style.color = "#c40000";
    error.style.marginBottom = "4px";
    error.style.fontWeight = "bold";
    error.textContent = message;
    fieldParent.appendChild(error);
}

function removeErrors(){
    if ( document.getElementById("serial_numberError") ){
        serialFieldDiv.removeChild(document.getElementById("serial_numberError"));
    }
    if (document.getElementById("lot_numberError")){
        lotFieldDiv.removeChild(document.getElementById("lot_numberError"));
    }
    if (document.getElementById("partError")){
        document.getElementById("partError").remove();
    }
    if (document.getElementById("binsError")){
        document.getElementById("binsError").remove();
    }
}

function checkTracking(){
    let part = document.getElementById("part").value;
    return axios.post('/check-tracking', { part: part });
}

function checkSerial(){
    let serial = document.getElementById("serial_number").value;
    return axios.post('/check-serial', { serial: serial })
}

function canSubmit() {
    removeErrors();
    Promise.all([checkTracking(), checkSerial()])
        .then(function ([tracking, serial]) {
            let lot = document.getElementById("lot_number").value;
            let canSubmit = false;

            if( tracking.data[0].track_lot == 1 && lot.length > 0 ) {
                canSubmit = true;
            } else {
                buildError(lotField, "This Part Requires a Lot Number");
                submitButton.disabled = false;
                submitButton.innerHTML = "Add Part";
                return false;
            }

            console.log(serial)
            if ( serial.data == "false" ) {
                canSubmit = true;
            } else {
                buildError(serialField, "Serial Number must be unique and is already in use.");
                submitButton.disabled = false;
                submitButton.innerHTML = "Add Part";
                return false;
            }

            if ( canSubmit == true ) {
                addNoTag();
            }
        });
}

const performSearch = (list, query) => {
    console.log(query);
    console.log(list);
    if ( list.includes(query) ) {
        return true;
    }
    return false;
};

function checkIsValid(field, list){
    removeErrors();
    console.log(list);
    console.log(field);
    let errId = field.name + "Error";
    let fieldError = document.getElementById(errId);
    let fieldParent = field.parentElement;
    let query = field.value;
    let exists = performSearch(list, query);
    let errorMsgStart = field.name == "part" ? "Part Number " : "Bin Number ";

    if( exists ) {
        field.style.backgroundColor = "rgb(55, 65, 81)";
        if( fieldError ){
            fieldError.remove();
        }
        submitButton.disabled = false;
        return true;
    } else {
        field.style.backgroundColor = "#c40000";
        if( fieldError ){
            fieldError.remove();
        }
        let errMsg = errorMsgStart + "Not Found.";
        buildError(field, errMsg);
        submitButton.disabled = false;
        return false;
    }
}

function getPartUom(partField){
    if( partField.value == ''){
        return false;
    }
    let partCheck = checkIsValid(partField, acParts);
    if( partCheck ) {
        axios.post('/get-part-uom',{
            part: document.getElementById('part').value
        })
        .then(function (response) {
            document.getElementById('uom').value = response.data.uom;
        });
    }
}

lotField.onchange = event => {
    if (event.target.value !== ""){
        let lotFieldError = document.getElementById('lot_numberError');
        if( lotFieldError ){
            lotFieldDiv.removeChild(lotFieldError);
        }
    }
}



submitButton.addEventListener("click", function(event) {
    event.preventDefault();
    submitButton.textContent = 'Processing...';
    submitButton.disabled = true;
    removeErrors();
    canSubmit();
    return false;
    // if ( canSubmit() ) {
    //     addNoTag();
    // }
});

updateTextColors();

function addNoTag(event) {
    let checkbox = document.getElementById('by_weight');
    let isChecked = checkbox.checked;

    axios.post('/notag/save', {
        part: document.getElementById('part').value,
        bin: document.getElementById('bins').value,
        count: document.getElementById('count').value,
        uom: document.getElementById('uom').value,
        by_weight: isChecked,
        warehouse: document.getElementById('warehouse').value,
        lot_number: document.getElementById('lot_number').value,
        serial_number: document.getElementById('serial_number').value
    })
        .then(function (response) {
            let tableBody = document.getElementById('noTagData').getElementsByTagName('tbody')[0];
            let part = response.data;
            let tag = makeCell(`${part.tag == null ? '' : part.tag}`, 'px-2 py-4');
            let part_number = makeCell(`${part.part}`, 'px-2 py-4');
            let count = makeCell(`${part.count}`, 'text-center');
            let bin = makeCell(`${part.bin}`, 'text-center');
            let uom = makeCell(`${part.uom}`, 'text-center');
            let by_weight = makeCell(`${part.by_weight == 1 ? "Yes" : "No"}`, 'text-center');
            let warehouse = makeCell(`${plantCodeToName(part.warehouse)}`, 'text-center');
            let lot_number = makeCell(`${part.lot_number == null ? '' : part.lot_number}`, 'text-center');
            let serial_number = makeCell(`${part.serial_number == null ? '' : part.serial_number}`, 'text-center');
            if (userType == 2) {
                let standard_cost = makeCell(`${formatterUSD.format(part.standard_cost)}`, 'text-right');
                let cost_counted = makeCell(`${formatterUSD.format(part.cost_counted)}`, 'text-right');
            }
            let edit_link = makeCell(`<a href="notag/edit/${part.id}"><button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 py-2 px-2 mr-0"><svg class="w-4 h-4" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg" fill="white"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h274.9c-2.4-6.8-3.4-14-2.6-21.3l6.8-60.9 1.2-11.1 7.9-7.9 77.3-77.3c-24.5-27.7-60-45.5-99.9-45.5zm45.3 145.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-37.8 37.8-4.1 4.1 71.8 71.7 41.8-41.8c9.3-9.4 9.3-24.5 0-33.9z"/></svg><span class="sr-only">Edit No Tag Part</span></button></a>`, 'text-center');

            let thisRow = makeRow(part);
            tableBody.prepend(thisRow);
            cells = [];

            let row = document.getElementById('row' + part['id']);
            row.classList = ('bg-green-300');
            updateTextColors();

            showToast('No Tag Part Count Added!');

        })
        .catch(function (error) {
            console.log(error);
        })
        .finally(function () {
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.textContent = "Add Part";
                const form = document.querySelector('#NoTagForm');
                form.reset();
            }, 3000);
        });
}

window.onload = function () {
    removeErrors();

    const autoCompleteBins = new autoComplete({
        selector: "#bins",
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

                    if (aStarts && !bStarts) return -1; // "a" comes first
                    if (!aStarts && bStarts) return 1;  // "b" comes first

                    // Otherwise, sort alphabetically
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

                    if (aStarts && !bStarts) return -1; // "a" comes first
                    if (!aStarts && bStarts) return 1;  // "b" comes first

                    // Otherwise, sort alphabetically
                    return aVal.localeCompare(bVal);
                });
            }
        },
        resultItem: {
            highlight: true,
        }
    });

    // PART CHECKS AND EVENT LISTENERS
    // SET AUTOCOMPLETE VALUE TO FORM FIELD AND GET PART UOM AND SET HIDDEN SELECT
    let partField = document.getElementById('part');
    partField.addEventListener("selection", function (event) {
        document.querySelector("#part").value = event.detail.selection.value;
        getPartUom(partField);
    });
    partField.addEventListener("change", function(event){
        getPartUom(event.target);
    });

    // BIN CHECKS AND EVENT LISTENERS
    // PUT AUTOCOMPLETE VALUE INTO FORM FIELD
    let binsField = document.querySelector("#bins");
    binsField.addEventListener("selection", function (event) {
        document.querySelector("#bins").value = event.detail.selection.value;
    });

    //  ADD VALID BIN CHECK AND ERROR FIELD
    binsField.addEventListener('change', function(event){
        let binCheck = checkIsValid(binsField, acBins);
    });

}






