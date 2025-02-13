const submitButton = document.getElementById("get-part");
submitButton.addEventListener("click", getPart);


function makeCell(value){
    let cell = document.createElement('td');
    cell.innerHTML = value;
    return cell;
}

function getPart(event) {
    event.preventDefault();

    axios.post('/inventory-search', {
        part: document.getElementById('part').value,
        bin: document.getElementById('bin').value
    })
        .then(function (response) {
            console.log(response);
            const table = document.getElementById('partData');
            let parts = response.data;
            parts.forEach((part) => {
                if(part['date_counted'] != '0000-00-00'){
                    let classes = "bg-green-300";
                }
                else{
                    let classes = '';
                }
                let thisRow = document.createElement('tr');
                thisRow.id = `row${part['id']}`;
                thisRow.classList = `${part['date_counted'] != '0000-00-00' ? 'bg-green-300' : null }`;
                thisRow.dataset.lot_number = `${part['lot_number']}`;
                thisRow.dataset.serial_number = `${part['serial_number']}`;

                let tag = makeCell(`${part['tag']}`);
                thisRow.appendChild(tag);
                let part_number = makeCell(`${part['part']}`);
                thisRow.appendChild(part_number);
                let uom = makeCell(`${part['uom']}`);
                thisRow.appendChild(uom);
                let count = makeCell(`<input type='text' name='count' id='count' value='${part['count']}' />`);
                thisRow.appendChild(count);
                let by_weight = makeCell(`<input type='checkbox' id='by_weight' ${part['by_weight'] === 1 ? 'checked />' : '/>'}`);
                thisRow.appendChild(by_weight);
                let lot_number = makeCell(`${part['lot_number']}`);
                thisRow.appendChild(lot_number);
                let serial_number = makeCell(`${part['serial_number']}`);
                thisRow.appendChild(serial_number);
                let expected_qty = makeCell(`${part['expected_qty']}`);
                thisRow.appendChild(expected_qty);
                let standard_cost = makeCell(`${part['standard_cost']}`);
                thisRow.appendChild(standard_cost);
                let cost_counted = makeCell(`${part['cost_counted']}`);
                thisRow.appendChild(cost_counted);
                let cost_expected = makeCell(`${part['cost_expected']}`);
                thisRow.appendChild(cost_expected);
                let plus_minus = makeCell(`${part['plus_minus']}`);
                thisRow.appendChild(plus_minus);
                let save_button = makeCell(`<button data-partid = '${part['id']}' id='saveCount' name='saveCount'>Save</button>`);
                thisRow.appendChild(save_button);

                table.appendChild(thisRow);
                // console.log(thisRow);
            });






            // if(value['date_counted'] != '0000-00-00'){
            //     var classes = "countedrow";
            // }
            // let thisRow = "<tr id='row" + value['id'] + "' class='" + classes + "' data-lot_number='" + value['lot_number'] + "' data-serial_number='" + value['serial_number'] + "'><td>" + value['tag'] + "</td><td>" + value['part'] + "</td><td style='text-align:center;'>" + value['bin'] + "</td><td style='text-align:center;'>" + value['uom'] + "</td><td style='text-align:center;'><input type='text' class='counted' name='counted' id='counted" + value['id'] + "' value='" + value['count'] + "'/></td>";
            //
            // thisRow += "<td><input type='checkbox' id='byweight" + value['id'] + "' /></td>";
            //
            // if(value['lot_number'] == ''){
            //     thisRow += "<td>"+ value['lot_number'] + "</td>";
            // }
            // else if(value['lot_number'] !== '' && value['date_counted'] !== '0000-00-00'){
            //     thisRow += "<td style='text-align:center;'><input type='text' class='counted' name='lot_number" + value['id'] + "' id='lot_number" + value['id'] + "' value='" + value['lot_number'] + "'></td>";
            // }
            // else{
            //     thisRow += "<td style='text-align:center;'><input type='text' class='counted' name='lot_number" + value['id'] + "' id='lot_number" + value['id'] + "' value='" + value['lot_number'] + "'></td>";
            // }
            //
            // if(value['serial_number'] == ''){
            //     thisRow += "<td>"+ value['serial_number'] + "</td>";
            // }
            // else{
            //     thisRow += "<td style='text-align:center;'><input type='text' class='counted' name='serial_number" + value['id'] + "'' id='serial_number" + value['id'] + "' value='" + value['serial_number'] + "'></td>";
            // }
            //
            //
            // thisRow += "<td><button data-prodid='" + value['id'] + "' class='saveCount' id='saveCount" + value['id'] + "' name='saveCount'>Save</td></tr>";



        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
}

