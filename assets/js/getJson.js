/*Global settings*/

// Spinner loading
const submitBtn = document.querySelector('#btnSearchPn');
const loading = document.querySelector('#loading');
const spinner = document.querySelector('.loadingio-spinner-ellipsis-oslofo8v1d');
//Tables
let divTables = document.querySelector('#resultsTables');
let tableone = document.querySelector('#tableOne');                        
let tabletwo = document.querySelector('#tableTwo');
//Both dropdown list
let moselect = document.querySelector('#mo-select');  
let dateselect = document.querySelector('#date-select');
let labelResult = document.querySelector('#spanRes');
//class variable for circle
let circuloResult = document.querySelector('#circleResult');
var corlorCircleOrigen = "";
var corlorCircleDestino = "";
//box result
let boxResult = document.querySelector('#circles-container');

// Object for initialize the JSON's to be compared
let SetterOfJsonResult = {
    jsonOneInitialized: "",
    jsonTwoInitialized: "",
    
    get getJsonOne() {
        return this.jsonOneInitialized;
    },

    get getJsonTwo() {
        return this.jsonTwoInitialized;
    }
};

const compareData = () => {
       // Get Json from initialized objects ... its only an example:
    const objJSON = SetterOfJsonResult.getJsonOne;                
    const objJSON2 = SetterOfJsonResult.getJsonTwo;
    let differentObjs = [];                                             
    let differentObjs_2 = []; 

    // verificar si el objeto objJSON2 es mas grande que objJSON
    if (objJSON2.length > objJSON.length) {
        /**
         * Pensar aqui si voy a indicar que una tabla es mas grande que la otra o no
         */
        for (const obj2 of objJSON2) {
            let found = false;
            const { cpn, description } = obj2;                
            const keysToCompare2 = { cpn, description };
            for (const obj1 of objJSON) {
                const { cpn, description } = obj1;                
                const keysToCompare = { cpn, description };
                
                let valuesMatch = Object.values(keysToCompare2).every((value, index) => value === Object.values(keysToCompare)[index]);

                if (valuesMatch) {
                    found = true;
                    break;
                }
        } // end second for
            if (!found) {
                differentObjs.push(obj2);
            }
        } // endf first for
    } else {                                   
        objJSON.forEach((obj1) => {
            const { cpn, description } = obj1;
            const keysToCompare = { cpn, description };
            let found = false;

            objJSON2.forEach((obj2) => {
                const { cpn, description } = obj2;
                const keysToCompare2 = { cpn, description };
                if (Object.values(keysToCompare).every((value, index) => value === Object.values(keysToCompare2)[index])) {
                    found = true;
                }      
            });

                if (!found) {
                    differentObjs.push(obj1);
                }
        });

        objJSON2.forEach((obj2) => {
            const { cpn, description } = obj2;
            const keysToCompare2 = { cpn, description };
            let found = false;

            objJSON.forEach((obj1) => {
                const { cpn, description } = obj1;
                const keysToCompare = { cpn, description };
                
                if (Object.values(keysToCompare).every((value, index) => value === Object.values(keysToCompare2)[index])) {
                    found = true;                    
                }
            });

                if (!found) {
                    differentObjs_2.push(obj2);                    
                }
        });      

       if (differentObjs.length > 0 && differentObjs_2.length > 0) {                                                 
            // let jsonresult = JSON.stringify(differentObjs, null, 2);                        
            let innerTable = "";
            let innerTable2 = "";
            differentObjs.forEach((objectProperty, index) => {
                let objeto2 =  differentObjs_2[index]                       
                let cpnResult = (objectProperty.cpn !== objeto2.cpn) ?  'different' : '';
                let descResult = (objectProperty.description !== objeto2.description) ?  'different' : '';
                innerTable += `<tr>
                                    <td>${objectProperty.upn}</td>
                                    <td>${objectProperty.mo}</td>
                                    <td>${objectProperty.position}</td>
                                    <td class="${cpnResult}">${objectProperty.cpn}</td>
                                    <td>${objectProperty.createdate}</td>
                                    <td>${objectProperty.category}</td>
                                    <td class="${descResult}">${objectProperty.description}</td>                                                                                    
                                </tr> `;

                innerTable2 += `<tr>
                                    <td>${objeto2.upn}</td>
                                    <td>${objeto2.mo}</td>
                                    <td>${objeto2.position}</td>
                                    <td class="${cpnResult}">${objeto2.cpn}</td>
                                    <td>${objeto2.createdate}</td>
                                    <td>${objeto2.category}</td>
                                    <td class="${descResult}">${objeto2.description}</td>                                                                                    
                                </tr> `;

            });         

            tableone.innerHTML = innerTable;                        
            tabletwo.innerHTML = innerTable2;             
            boxResult.style.display = "none";
            divTables.style.display = "block";
        } else {                  
            corlorCircleOrigen = "circle-5";
            corlorCircleDestino = "circle-3"; 
            circuloResult.classList.replace(corlorCircleOrigen, corlorCircleDestino);
            document.querySelector('#spanRes').innerHTML = "Good";
            // boxResult.style.display = "block";
            boxResult.style.visibility = "visible";
        }          
    } //end else                                                                                             
} //end compareData

// Función para detener la animación del spinner
function detenerSpinner() {
    spinner.style.animation = 'none';
}

// Get information MO by Part number L10:
const getMoByPn = (txtMoSearch, selectLineList) => {                 
    let formData = new FormData();    
    formData.append('pndata', txtMoSearch);
    formData.append('linedata', selectLineList);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/getmo_bypn.php", true, xhr.responseType = "json");
    xhr.send(formData);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) {             
            if ((xhr.response.status) && (xhr.response.data != "")) {
                alert('Data obtained successfully.');
                let dataObtained = xhr.response.data;                
                let listInner = "<option value=''>-</option>";
                dataObtained.forEach(element => {                    
                    listInner += `<option value="${element.mo}">${element.mo}</option>`;                                              
                });                
                dataObtained = xhr.response.date;                
                let listDateInner = "<option value=''>-</option>";
                dataObtained.forEach(element => {                    
                    listDateInner += `<option value="${element.mo}">${element.dates}</option>`;                                              
                });
                
                moselect.innerHTML = listInner;
                dateselect.innerHTML = listDateInner;
                
                 // Hide animation and show button Consult
                 loading.style.display = 'none';
                 submitBtn.style.display = 'block'; 
                 // Stop spinner
                 detenerSpinner();   

            } else {
                alert(`Atention: [${xhr.response.msg}]`);                
                loading.style.display = 'none';
                submitBtn.style.display = 'block'; 
                moselect.innerHTML = "<option value=''>-</option>";
                dateselect.innerHTML = "<option value=''>-</option>";                
                detenerSpinner();               
            }                      
        } else {          
          alert(`Server error: ${xhr.statusText}!`);          
        }
      }
    }; // end onreadystatechange     
  } // end SendJsonToPhp

// Get information in JSON and compare them:
const getMoJsons = (moone, motwo) => {               
    let formData2 = new FormData();    
    formData2.append('modataone', moone);
    formData2.append('modatatwo', motwo);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/getmo_data.php", true, xhr.responseType = "json");
    xhr.send(formData2);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) {                         
            if (xhr.response.status) {                               
                // Initialize the Json Objects with results:
                SetterOfJsonResult.jsonOneInitialized = xhr.response.dataone;
                SetterOfJsonResult.jsonTwoInitialized = xhr.response.datatwo;
                compareData();                             
            } else {
                alert(`Error: [${xhr.response.msg}]`);
                //console.log(`Error: [${xhr.response.msg}]`);              
            }                      
        } else {          
          alert(`Server error: ${xhr.statusText}!`);          
        }
      }
    }; // end onreadystatechange             
  } // end getMoJsons

const functionResetOnChange = () => {       
    let elemento = document.getElementById('circleResult');    
    let estiloComputado = window.getComputedStyle(elemento);    
    let actuallyColor = estiloComputado.backgroundColor;
    
    //red rgb(255, 0, 0)
    //green rgb(65, 245, 80)
    if (actuallyColor == 'rgb(255, 0, 0)') {        
        // Restablecer el color del círculo a naranja        
        circuloResult.classList.remove('circle-1');        
        circuloResult.classList.add('circle-6');   
        
        // circuloResult.classList.replace('circle-1', 'circle-6');     
    } else if (actuallyColor == 'rgb(65, 245, 80)') {        
        circuloResult.classList.remove('circle-3');
        circuloResult.classList.add('circle-6');   
    }

    labelResult.innerHTML = "Result";
    boxResult.style.display = "block";
    boxResult.style.visibility = "hidden";
    divTables.style.display = "none"; 
    tableone.textContent = "";                        
    tabletwo.textContent = "";
} // end functionResetOnChange

//Initialize values
let selected = null;
const setSelection = (optionParam) => {
    selected = optionParam.value;
};

let moSelected = null;
const setSelectionMo = (optionMo) => {    
    moSelected = optionMo.value;
    functionResetOnChange();        
};

let dateSelected = null;
const setSelectionDate = (optionDate) => {
    dateSelected = optionDate.value;
    functionResetOnChange();    
};

document.addEventListener('DOMContentLoaded', () => {
    let formpn = document.querySelector('#formulario-pn');
    let formmo = document.querySelector('#formulario-mo');
    let info = document.querySelector('#info');
    divTables.style.display = "none";
    boxResult.style.visibility = "hidden";
    
    //First form
    formpn.onsubmit = function(param) {
        param.preventDefault();
        let txtMoSearch = document.querySelector('#txtSearchMo').value;                      
        if ((txtMoSearch !== "" || txtMoSearch !== null) && (selected !== "" || selected !== null)) { 
            // Oculta el botón "Enviar" y muestra la animación de carga
            submitBtn.style.display = 'none';
            loading.style.display = 'block';                 
            getMoByPn(txtMoSearch, selected);            
      }
                                
    };

    // Second form
    formmo.onsubmit = function (params) {                
        params.preventDefault();        
        if ((moSelected !== "" || moSelected !== null) && (dateSelected !== "" || dateSelected !== null)) {
            getMoJsons(moSelected, dateSelected);
        } 
        
    }
    formmo.onreset = () => {
        functionResetOnChange();
        moselect.innerHTML = "<option value=''>-</option>";
        dateselect.innerHTML = "<option value=''>-</option>";             
    }

    info.addEventListener('click',  () => {        
       alert(`This page is a tool to obtain and compare information of MO by NP of L10. The main goal is to prevent L10 tests from being performed with erroneous data.`);
    });

});  //end addEventListener