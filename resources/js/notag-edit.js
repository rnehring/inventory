window.onload = function () {

    const byWeight = document.getElementById("by_weight");
    byWeight.addEventListener("click", function(event){
        console.log(byWeight.value);
        if(byWeight.value == 0){
            byWeight.value = 1;
        } else {
            byWeight.value = 0;
        }
    });

}



