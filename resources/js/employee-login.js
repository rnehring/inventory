window.onload = () => {
    let companySelect = document.getElementById("company");
    companySelect.value = "logo-white.png";
    checkSelect();
    companySelect.onchange = checkSelect;
};

function checkSelect(){
    let companyChosen = document.getElementById("company");
    let companyValue = companyChosen.value;
    updateLogo(companyValue);


    let companyCode = document.getElementById("companyCode");

    const selectedOptionIndex = companyChosen.selectedIndex;
    const selectedOption = companyChosen.options[selectedOptionIndex];
    const chosenCompanyCode = selectedOption.dataset.cc;
    companyCode.value = chosenCompanyCode;


    if(companyValue == "logo-white.png"){
        companyChosen.setCustomValidity("Please choose a company");
    }
    else{
        companyChosen.setCustomValidity("");
    }
}

function updateLogo(logoPath){
    let logo = document.getElementById("company-logo");
    logo.src = "images/company-logos/" + logoPath;


}
