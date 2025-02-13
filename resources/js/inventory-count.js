const submitButton = document.getElementById("get-part");
submitButton.addEventListener("click", getPart);

function getPart(event) {
    event.preventDefault();

    axios.post('/inventory-search', {
        part: document.getElementById('part').value,
        bin: document.getElementById('bin').value
    })
        .then(function (response) {
            console.log(response);
            let parts = response.data;
            parts.forEach((part) => {
                if(part['date_counted'] != '0000-00-00'){
                    let classes = "bg-green-300";
                }
                else{
                    let classes = '';
                }
                let thisRow = `<tr id='row${part['id']}'
                                        class='${part['date_counted'] != '0000-00-00' ? 'bg-green-300' : null }'
                                        data-lot_number='${part['lot_number']}'
                                        data-serial_number='${part['serial_number']}'>
                                        <td>${part['tag']}</td>
                                        <td>${part['part']}</td>
                                        <td>${part['bin']}</td>


                `;
                console.log(thisRow);
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

