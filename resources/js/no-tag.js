
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

const loadingButton = `<button disabled type="button" class="text-white bg-brand inline-flex items-center box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
<svg aria-hidden="true" role="status" class="w-4 h-4 me-2 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
</svg>
Loading...
</button>`;

const disabledClasses = "dark:text-gray-400 dark:bg-gray-700 dark:hover:bg-gray-700 box-border border dark:border-gray-900 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none";

const enabledClasses = "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mb-4";


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

function enableSubmit(){
    submitButton.disabled = false;
    submitButton.innerHTML = "Add Part";
    submitButton.classList = enabledClasses;
}

function disableSubmit(){
    submitButton.disabled = true;
    submitButton.innerHTML = "Add Part";
    submitButton.classList += " " + disabledClasses;
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
                enableSubmit()
                canSubmit = true;
            } else if( tracking.data[0].track_lot == 0) {
                enableSubmit()
                canSubmit = true;
            } else {
                buildError(lotField, "This Part Requires a Lot Number");
                disableSubmit();
                return false;
            }

            if ( serial.data == "false" ) {
                enableSubmit();
                canSubmit = true;
            } else {
                buildError(serialField, "Serial Number must be unique and is already in use.");
                disableSubmit();
                serialField.addEventListener("change", enableSubmit);
                return false;
            }

            if ( canSubmit == true ) {
                addNoTag();
            }
        });
}

const performSearch = (list, query) => {
    if ( list.includes(query) ) {
        return true;
    }
    return false;
};

function checkIsValid(field, list){
    removeErrors();
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
        enableSubmit();
        return true;
    } else {
        field.style.backgroundColor = "#c40000";
        if( fieldError ){
            fieldError.remove();
        }
        let errMsg = errorMsgStart + "Not Found.";
        buildError(field, errMsg);
        disableSubmit();
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
            if (response.data.uom == "EA"){
                let countField = document.getElementById("count");
                countField.addEventListener("keydown", function(event){
                    if (event.key === '.' || event.key === ',') {
                        event.preventDefault();
                    }
                });
            }
        });
    }
}

lotField.onchange = event => {
    if (event.target.value !== ""){
        let lotFieldError = document.getElementById('lot_numberError');
        if( lotFieldError ){
            lotFieldDiv.removeChild(lotFieldError);
            enableSubmit();
        }
    }
}



submitButton.addEventListener("click", function(event) {
    event.preventDefault();
    if(submitButton.disabled){
        return false;
    }
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
        console.log('ERROR caught!');
        console.log('Error:', error);
        console.log('Error Response:', error.response);
        console.log('Error Data:', error.response?.data);
        console.log('Status Code:', error.response?.status);
        const message = error.response?.data?.message || 'Failed add No Tag Part';
        showToast(message, 6000, true);
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






