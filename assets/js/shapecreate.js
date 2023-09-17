/** Globals **/
let arrayTemp = []; // array for saving the all json's arrays
let arrayTempSub = []; // array for saving ONE JSON, is like  objJSON and objJSON2
let differentJsonsArrays = []; // array for saving jsons when they are different
let dataSummaryArray =  []; // array for save once I have the results
let noHayDatos = []; //This array is used when there is no data
let dataTable;

/** Variables DOM */
//Loading
const loading = document.querySelector('.content-spinner');
//Tables
let divTables = document.querySelector('#resultsTables');
let tableone = document.querySelector('#tableone');                        
let tabletwo = document.querySelector('#tabletwo');
let tabodysummary = document.querySelector('#tabodysummary');
//modals
let modal = document.getElementById("ventanaModal");
// let modalSummary = document.getElementById("modalSummary");
let closemodal = document.getElementById("closemodal");
let exclose = document.querySelectorAll("#close");
let closemodalsummary = document.getElementById("closemodalsummary");
//buttons
let btnsummary = document.querySelector('#btnsummary');

//object for summary:
let objInitialized = {
    line: '',
    partnumber: '',
    mogolden: '',
    dategolden: '',

    get getLine() {
        return this.line;
    },
    get getUpn() {
        return this.partnumber;
    },    
    get getMoGolden() {
        return this.mogolden;
    },
    get getDateGolden() {
        return this.dategolden;
    }
};

function compareDataObtained(jsonParam) {
    // The object principal is always the last one, because in PHP is ASC order:
    const objJSON = arrayTempSub[arrayTempSub.length-1]; 
    let objJSON2 = [];  
    let differentObjs = [];          
    
    // Verify if param 'objJSON2' is bigger than 'objJSON':
    objJSON2 = jsonParam;    
        if (objJSON2.length > objJSON.length) {    
            let found = null;   
            let cont = 0;    
            for (const obj2 of objJSON2) {
                found = false;
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
                    cont++;
                }
            } // endf first for   
            if (cont > 0) {                
                found = false;
            }         
            return found;
        } else {               
            let found = null;   
            let cont = 0;                 
            objJSON.forEach((obj1) => {
                const { cpn, description } = obj1;
                const keysToCompare = { cpn, description };                
                found = false;                
                
                objJSON2.forEach((obj2) => {
                    const { cpn, description } = obj2;
                    const keysToCompare2 = { cpn, description };
                    if (Object.values(keysToCompare).every((value, index) => value === Object.values(keysToCompare2)[index])) {
                        found = true;
                    }      
                    
                });
                    if (!found) {
                        differentObjs.push(obj1);  
                        cont++;              
                    }                    
            });
            if (cont > 0) {                                
                found = false;
            }                                       
            return found;
        } //end else             
} // end compareDataObtained

const shapeCreator = () => {        
    let jsonParam = [];
    let resultComparison = null;    
    let mobj = {};                      
    
        for (let index = 0; index <= arrayTempSub.length-1; index++) {            
            jsonParam[0] = arrayTempSub[index]; //The index of jsonParam[0] never changes, because their content changes on each iterance 
            resultComparison = compareDataObtained(jsonParam[0]);
            mobj = jsonParam[jsonParam.length-1];                                      

            let mosummary = "";
            let datesummary = "";
            if (resultComparison) {                                                   
                mosummary = mobj[0].mo;
                datesummary = mobj[0].createdate;
                dataSummaryArray[index] = { 
                                            'line': objInitialized.getLine, 
                                            'upn': objInitialized.getUpn, 
                                            'mog': objInitialized.getMoGolden, 
                                            'dateg': objInitialized.getDateGolden, 
                                            'moc': mosummary,  
                                            'datec': datesummary, 
                                            'result': 'green'
                                            };
            } else {                                         
                mosummary = mobj[0].mo;
                datesummary = mobj[0].createdate;
                dataSummaryArray[index] = { 
                                            'line': objInitialized.getLine, 
                                            'upn': objInitialized.getUpn, 
                                            'mog': objInitialized.getMoGolden, 
                                            'dateg': objInitialized.getDateGolden, 
                                            'moc': mosummary,  
                                            'datec': datesummary, 
                                            'result': 'red'
                                            };
            } //end if                          
        } // end for
        insertFunction();      
} //end shapeCreator

//The comparison algorithm
const showTableResultDifferents = (arrMoGolden, arrMoCompare) => {        
    let objJSON= arrMoGolden;  
    let objJSON2 = arrMoCompare;      
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
                                    <td>${objectProperty.line}</td>
                                    <td>${objectProperty.upn}</td>
                                    <td>${objectProperty.mo}</td>
                                    <td>${objectProperty.position}</td>
                                    <td class="${cpnResult}">${objectProperty.cpn}</td>
                                    <td>${objectProperty.createdate}</td>
                                    <td>${objectProperty.category}</td>
                                    <td class="${descResult}">${objectProperty.description}</td>                                                                                    
                                </tr> `;
                innerTable2 += `<tr>
                                    <td>${objeto2.line}</td>
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
            modal.style.display = "block";                        
        } else {                  
            alert('Oops, something went wrong with MO data!');
            //moclickled.innerHTML = 'Oops, something went wrong !';                                        
        }          
    } //end else                                                                                             
} //end showTableResultDifferents
    
    // Si el usuario hace click en la x, la ventana se cierra
    closemodal.addEventListener("click", () => {
        modal.style.display = "none";
    });      

function selectSummary() {
        loading.style.display = 'none'; //loading action    
        dataTable = $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#tabledata')) {
                $('#tabledata').DataTable({
                    ajax: {
                        url: 'php/selectfromsummary.php',
                        type: 'POST',
                        dataType: 'json', // Especificamos el tipo de datos esperado como JSON
                        dataSrc: 'data' // Indicamos la propiedad del objeto de respuesta que contiene los datos
                    },
                    columns: [
                        { data: 'line' },
                        { data: 'upn' },
                        { data: 'mog' },
                        { data: 'dateg' },
                        { data: 'moc' },
                        { data: 'datec' },
                        { data: 'actions' },
                    ],
                    "resonsieve":"true",
                    "bDestroy": true,
                    "iDisplayLength": 10                    
                });
            }
        });
                     
    // const xhr = new XMLHttpRequest();
    // xhr.open("POST", "php/selectfromsummary.php", true, xhr.responseType = "json");
    // xhr.send();
    // xhr.onreadystatechange = function() {
    //     if (xhr.readyState === XMLHttpRequest.DONE) {
    //         if (xhr.readyState === 4 && xhr.status === 200) {                         
    //             if (xhr.response.boolean) {
    //                 let arrDataSelect = [];                       
    //                 arrDataSelect = xhr.response.data;                        
    //                 let classColor = "";
    //                 let innerSummary = "";

    //                 arrDataSelect.forEach((element, index) => {                                                    
    //                     classColor = (element.result == 'red') ? 'fill-danger-sf' : 'fill-success';
                        
    //                     if (classColor === 'fill-danger-sf') {
    //                         innerSummary += `<tr id="trred" idsummary="${element.id}" class="${classColor}" ind="${index}" trmog="${element.mog}" trmoc="${element.moc}" onclick="clickEvent(this);">`;                                                    
    //                     } else {                            
    //                         innerSummary += `<tr class="${classColor}" ind="${index}">`;
    //                     }                                                                                
    //                     innerSummary +=  `<td>${element.line}</td>
    //                                         <td>${element.upn}</td>
    //                                         <td>${element.mog}</td>
    //                                         <td>${element.dateg}</td>
    //                                         <td>${element.moc}</td>
    //                                         <td>${element.datec}</td>`;
    //                         innerSummary += `<tr>`;
    //                 });
    //                 tabodysummary.innerHTML = innerSummary;
    //             } else {
    //                 alert(`Error: [${xhr.response.msg}]`);                
    //             }                      
    //         } else {          
    //         alert(`Server error: ${xhr.statusText}!`);          
    //         }
    //     }
    // }; // end onreadystatechange   

}; //selectSummary
    
/**This will be the funcion to fill the table results*/
const insertFunction = () => {    
        /** In this place the table summary is fulling **/        
        let jsontosendsummary = JSON.stringify(Object.fromEntries(dataSummaryArray.entries()), null, 2);                 
        let formDataSummary = new FormData();
        formDataSummary.append('jsonsummary', jsontosendsummary);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/inserttosummary.php", true, xhr.responseType = "json");
        xhr.send(formDataSummary);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.readyState === 4 && xhr.status === 200) {                         
                    if (xhr.response.boolean) {
                    //    console.log(xhr.response.msg);
                        selectSummary();
                    } else {
                        alert(`Error: [${xhr.response.msg}]`);                
                    }                      
                } else {          
                alert(`Server error: ${xhr.statusText}!`);          
                }
            }
        }; // end onreadystatechange                    
}; // end insertFunction

// Close modal when the user makes click outside it:
window.addEventListener("click", (event) => {
    if (event.target == modal) {
        modal.style.display = "none";
    }
});    

// Close modal when the user makes click on 'x':
exclose.forEach((elemento) => {
    elemento.addEventListener("click", () => {          
        if (modal.style.display === "block") {                
            modal.style.display = "none";
        } 
    });           
});


//First, before to show the modal, set the id for approving the MO by user:
let idis = 0;      
const clickEvent = (algo) => {                 
    let arrayAttributesTr = algo.attributes;             
    let moone = arrayAttributesTr.trmog.value;
    let motwo = arrayAttributesTr.trmoc.value;                
    idis = arrayAttributesTr.idsummary.value;            
    
    //For every clcick from the user, will do the search about the data selected on row:
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
                let arrOne = xhr.response.dataone;      
                let arrTwo = xhr.response.datatwo;                                  
                showTableResultDifferents(arrOne, arrTwo);                             
            } else {
                alert(`Error: [${xhr.response.msg}]`);
                //console.log(`Error: [${xhr.response.msg}]`);              
            }                      
        } else {          
          alert(`Server error: ${xhr.statusText}!`);          
        }
      }
    }; // end onreadystatechange             
} //end clicEvent

// To get MO's information by each UPN:
const getMoJsons = (partNumber) => {       
    let sendData = new FormData();  
    sendData.append('upnsend', partNumber);         
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/getnmo.php", true, xhr.responseType = "json");
    xhr.send(sendData);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {                                  
                if (xhr.response.boolean) {
                    loading.style.display = 'none'; //loading action                               
                    let dataArray = xhr.response.data;                          
                    let iteraciones = dataArray.length;                                  
                    let upnactual = document.querySelector('#upnactual');
                    let moactual = document.querySelector('#moactual');
                    let datemoactual = document.querySelector('#datemoactual');                    
                    let countArrays = 0;
                    
                    for (let index = 0; index < iteraciones; index++) { // iterar el array Perrote                        
                        arrayTemp[index] = dataArray[index];// ahora 'arrayTemp' es el que representaba al 'data' que contiene todas las MOs                        
                        if (arrayTemp[index].length != 0) {                            
                            arrayTempSub = arrayTemp[index];                            
                            objInitialized.line = arrayTempSub[arrayTempSub.length-1][0].line;                            
                            objInitialized.partnumber = arrayTempSub[arrayTempSub.length-1][0].upn;                            
                            objInitialized.mogolden = arrayTempSub[arrayTempSub.length-1][0].mo;
                            objInitialized.dategolden = arrayTempSub[arrayTempSub.length-1][0].createdate;
    
                            upnactual.innerHTML = arrayTempSub[arrayTempSub.length-1][0].upn;
                            moactual.innerHTML = arrayTempSub[arrayTempSub.length-1][0].mo;
                            datemoactual.innerHTML = arrayTempSub[arrayTempSub.length-1][0].createdate;                                                                                                        
                            
                            //llamar al comparador simple:
                            shapeCreator();
                        } else {
                            countArrays++;                           
                        }
                       
                    } // end for
                    if (countArrays > 0) {                        
                        tabodysummary.innerHTML = "";                        
                        selectSummary();
                    }                                                         
                } else {
                    alert(`Error: [${xhr.response.msg}]`);                
                }                      
            } else {          
            alert(`Server error: ${xhr.statusText}!`);          
            }
        }
    }; // end onreadystatechange           
} // end getMoJsons

let arrayAllUPNs = [];
const getAllUPN = () => {          
    let horaLabel = document.querySelector('#hora');
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/consult_all_upn.php", true, xhr.responseType = "json");
    xhr.send();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {                                  
                if (xhr.response.boolean) {            
                    horaLabel.innerHTML = xhr.response.hora;
                    arrayAllUPNs = xhr.response.allupn;
                    arrayAllUPNs.forEach((objteto, indiz) => {      
                        // console.log(indiz);                                          
                        getMoJsons(objteto.upn);
                    }); // end forEach                                                                                                           
                } else {
                    alert(`Error: [${xhr.response.msg}]`);                
                }                      
            } else {          
            alert(`Server error: ${xhr.statusText}!`);          
            }
        }
    }; // end onreadystatechange           
} // end getAllUPN

//Approve Button Modal
const approveButton = () => {
    let approve = document.querySelector('#approvemo');    
    approve.addEventListener('click', () => {               
            let formDataSummary = new FormData();
            formDataSummary.append('idsummary', idis);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "php/inserttosummary.php", true, xhr.responseType = "json");
            xhr.send(formDataSummary);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.readyState === 4 && xhr.status === 200) {                         
                        if (xhr.response.boolean) {                           
                            location.reload();                                          
                        } else {
                            alert(`Error: [${xhr.response.msg}]`);                
                        }                      
                    } else {          
                    alert(`Server error: ${xhr.statusText}!`);          
                    }
                }
            }; // end onreadystatechange   
    }); // end addEventListener
} // end approveButton

window.addEventListener('load', () => {
    approveButton();
});

document.addEventListener('DOMContentLoaded', () => {              
    getAllUPN();   
    //ejemplo    
    // let arrsuposicion = ['M1198369-001$LP03', 'M1198370-001$GV01'];   
    // arrsuposicion.forEach((item) => {
    //     getMoJsons(item);            
    // });      


    /**Timer here */
    // let oneHour = 60 * 60 * 1000;
    // setInterval(getMoJsons, oneHour);           
}); //end DOMContentLoaded