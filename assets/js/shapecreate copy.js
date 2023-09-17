/** Globals **/
let dataArray = []; // array for saving the all json's arrays
let differentJsonsArrays = []; // array for saving jsons when they are different
let dataSummaryArray =  []; // array for summary

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
let modalSummary = document.getElementById("modalSummary");
let closemodal = document.getElementById("closemodal");
let exclose = document.querySelectorAll("#close");
let closemodalsummary = document.getElementById("closemodalsummary");
//buttons
let btnsummary = document.querySelector('#btnsummary');

//object for summary:
let objInitialized = {
    partnumber: '',
    mogolden: '',
    dategolden: '',

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
    const objJSON = dataArray[dataArray.length-1]; 
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
                if (!foundTwo) {
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
    let shape = ""; 
    let mobj = {};
    let groupinter = document.querySelector('#group-inter');               
    let counterParam = 0;    

        for (let index = 0; index <= dataArray.length-2; index++) {
            jsonParam[counterParam] = dataArray[index]; 
            resultComparison = compareDataObtained(jsonParam[counterParam]);
            mobj = jsonParam[jsonParam.length-1];         
            let mosummary = "";
            let datesummary = "";
            if (resultComparison) {                        
                shape += `<div id="ok" ind="${index}" class="rectangle circle-3"><p id="green">MO: ${mobj[0].mo}</p><p id="green">CREATE DATE:  ${mobj[0].createdate}</p></div>`;                
                mosummary = mobj[0].mo;
                datesummary = mobj[0].createdate;
                dataSummaryArray[index] = { 'upn': objInitialized.getUpn, 
                                            'mog': objInitialized.getMoGolden, 
                                            'dateg': objInitialized.getDateGolden, 
                                            'moc': mosummary,  
                                            'datec': datesummary, 
                                            'result': 'green'
                                            };
            } else {                
                shape += `<div id="nok" moshape="${mobj[0].mo}" ind="${index}" class="rectangle circle-1" onclick="clicEvent(this);"><p id="red">MO: ${mobj[0].mo}</p><p id="red">CREATE DATE:  ${mobj[0].createdate}</p></div>`;                                
                mosummary = mobj[0].mo;
                datesummary = mobj[0].createdate;
                dataSummaryArray[index] = { 'upn': objInitialized.getUpn, 
                                            'mog': objInitialized.getMoGolden, 
                                            'dateg': objInitialized.getDateGolden, 
                                            'moc': mosummary,  
                                            'datec': datesummary, 
                                            'result': 'red'
                                            };
            } //end if  
            // console.log(dataSummaryArray[index]);
        } // end for
        counterParam++;        
        groupinter.innerHTML = shape;
} //end shapeCreator

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
        } else {                  
            moclickled.innerHTML = 'Oops, something went wrong !';          
        }          
    } //end else                                                                                             
} //end showTableResultDifferents
    
    // Si el usuario hace click en la x, la ventana se cierra
    closemodal.addEventListener("click", () => {
        modal.style.display = "none";
    });      
    // Si el usuario hace click en la x, la ventana se cierra
    closemodalsummary.addEventListener("click", () => {
        modalSummary.style.display = "none";
    }); 


    const selectSummary = () => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/selectfromsummary.php", true, xhr.responseType = "json");
        xhr.send();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.readyState === 4 && xhr.status === 200) {                         
                    if (xhr.response.boolean) {
                        let arrDataSelect = [];                       
                        arrDataSelect = xhr.response.data;                        
                        let classColor = "";
                        let innerSummary = "";

                        arrDataSelect.forEach((element, _) => {                                                    
                            classColor = (element.result == 'red') ? 'fill-danger-sf' : 'fill-success';
                            
                                innerSummary += `<tr class="${classColor}">`;
                                innerSummary +=  `<td>${element.upn}</td>
                                                    <td>${element.mog}</td>
                                                    <td>${element.dateg}</td>
                                                    <td>${element.moc}</td>
                                                    <td>${element.datec}</td>`;                                                    
                                innerSummary += `<tr>`;
                        });
                        tabodysummary.innerHTML = innerSummary;
                    } else {
                        alert(`Error: [${xhr.response.msg}]`);                
                    }                      
                } else {          
                alert(`Server error: ${xhr.statusText}!`);          
                }
            }
        }; // end onreadystatechange   

    }; //selectSummary
    
    //Btn summary
    btnsummary.addEventListener('click', () => {
        /** In this place the table summary is fulling **/
        // let jsontosendsummary = JSON.stringify(dataSummaryArray, null, 2); 
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


        modalSummary.style.display = "block";
    });

    // Si el usuario hace click fuera de la ventana, se cierra.
    window.addEventListener("click", (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        } else if (event.target == modalSummary) {
            modalSummary.style.display = "none";
        }
    });    

    exclose.forEach((elemento) => {
        elemento.addEventListener("click", () => {          
            if (modalSummary.style.display === "block") {                 
                modalSummary.style.display = "none";
            } else if (modal.style.display === "block") {                
                modal.style.display = "none";
            } 
        });           
    });
        
const clicEvent = (algo) => {        
    let arrOne;
    let arrTwo;           
    let ind = algo.getAttribute('ind');     
     
    // window modal            
    modal.style.display = "block";                     
    arrOne = dataArray[dataArray.length-1]; //the last index of the general Array its the golden MO      
    arrTwo = dataArray[ind]; // the JSON index of the general Array is established as a parameter
    showTableResultDifferents(arrOne, arrTwo);      
} //end clicEvent

// Get information in JSON and compare them:
const getMoJsons = () => {  
    console.log('Llamada automatica');             
    //let formData2 = new FormData();    
    // formData2.append('modataone', moone);
    // formData2.append('modatatwo', motwo);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/getnmo.php", true, xhr.responseType = "json");
    xhr.send();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {                                  
                if (xhr.response.boolean) {
                    loading.style.display = 'none'; //loading action                               
                    dataArray = xhr.response.data;
                    // console.log(dataArray);

                    let upnactual = document.querySelector('#upnactual');
                    let moactual = document.querySelector('#moactual');
                    let datemoactual = document.querySelector('#datemoactual');
                    let horaLabel = document.querySelector('#hora');
                                           
                    objInitialized.partnumber = xhr.response.data[dataArray.length-1][0].upn;
                    objInitialized.mogolden = xhr.response.data[dataArray.length-1][0].mo;
                    objInitialized.dategolden = xhr.response.data[dataArray.length-1][indice].createdate;

                    upnactual.innerHTML = xhr.response.data[dataArray.length-1][0].upn;
                    moactual.innerHTML = xhr.response.data[dataArray.length-1][0].mo;
                    datemoactual.innerHTML = xhr.response.data[dataArray.length-1][0].createdate;                             
                                
                                               
                    

                    
                    horaLabel.innerHTML = xhr.response.hora;
                    
                    shapeCreator();
                } else {
                    alert(`Error: [${xhr.response.msg}]`);                
                }                      
            } else {          
            alert(`Server error: ${xhr.statusText}!`);          
            }
        }
    }; // end onreadystatechange           
} // end getMoJsons

document.addEventListener('DOMContentLoaded', () => {    
    modalSummary.style.display = "none";        
    getMoJsons();    
    //let oneHour = 60 * 60 * 1000;
    // setInterval(getMoJsons, oneHour);           
}); //end DOMContentLoaded