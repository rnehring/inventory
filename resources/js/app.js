import './bootstrap';
import 'flowbite';

export function setCells(){
    window.cells = [];
}

export function epicorCodeToCompanyName($code){
    switch($code){
        case "00":
            return "Andronaco Industries";
        case "10":
            return "PureFlex";
        case "20":
            return "Nil-Cor";
        case "30":
            return "Ethylene";
        case "40":
            return "Hills-McCanna";
        case "50":
            return "Ramparts Pumps";
        case "CC0":
            return "Conley Composites";
        case "FC0":
            return "FlowCor";
        case "GS0":
            return "Endurance Composites";
        case "GWS":
            return "Great Western Supply";
        case "PV0":
            return "PolyValve";
    }
}

export function updateTextColors(){
    document.querySelectorAll('tr:not([class=""])').forEach(tr => {
        tr.querySelectorAll('td').forEach(td => {
            td.style.color = 'rgb(31 41 55)'; // e.g., 'red', '#000', 'rgb(255,0,0)'
        });
    });
}

export function formatToTwoDigits(value){
    let num = parseInt(value);
    return num.toFixed(2);
}

export function makeCell(value, classes){
    let cell = document.createElement('td');
    cell.innerHTML = value;
    cell.classList = 'text-gray-400 px-4 border-b ';
    if(classes){
        cell.classList.add(classes);
    }
    cells.push(cell);
    return cell;
}

export function makeRow(part){
    console.log(part);
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

export function showToast(message, duration = 3000) {
    const toast = document.getElementById('toast-success');
    const messageElement = document.getElementById('toast-message');

    // Set message
    messageElement.textContent = message;


    toast.classList.remove('hidden');
    toast.classList.add('flex');

    // Auto hide after duration
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');

        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex', 'opacity-0');
        }, 500);
    }, duration);
}


export const formatterUSD = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});


