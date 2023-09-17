const compareData = () => {
    let $urlMO = "json/mo_000010078977_04-APR-23.json";                     
    let $urlMO2 = "json/mo_000010078976_04-FEB-23.json";  
        
    Promise.all([
            fetch($urlMO),
            fetch($urlMO2)           
          ])
          .then(responses => {
            return Promise.all(responses.map(response => {
              return response.json();
            }));
          })
            .then(data => {            
                // let objJSON = JSON.parse(data[0]);            
                // let objJSON2 = JSON.parse(data[1]);                         

                let objJSON = data[0];
                let objJSON2 = data[1];                                                                   
                let differentObjs = [];                                             
                let differentObjs_2 = [];  
                let keyDifferent = [];
                // let keyDifferent_ = [];                               
                // verificar si el objeto objJSON2 es mas grande que objJSON
                if (objJSON2.length > objJSON.length) {
                    /**
                     * Pensar aqui si voy a indicar que una tabla es mas grande que la otra o no
                     */
                    // console.log('More records.')

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
                    }
                        if (!found) {
                            differentObjs.push(obj2);
                        }
                    }
                } else {         
                    /*Object ONE from differents*/
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

                    /*Object TWO from differents*/
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
                        document.querySelector('#tableOne').innerHTML = innerTable;                        
                        document.querySelector('#tableTwo').innerHTML = innerTable2;                       
    
                        document.querySelector('#circleResult').classList.replace('circle-6', 'circle-1');
                        document.querySelector('#spanRes').innerHTML = "Bad";
                    } else {                    
                        document.querySelector('#circleResult').classList.replace('circle-6', 'circle-3');
                        document.querySelector('#spanRes').innerHTML = "Good";
                    }          
                } //end else                                                                                   
            }) //end then data             
          .catch(error => console.error(error));

} //end compareData()

// function functionCompare(param1, param2) {
//     let respuesta = false;    
//     if (param1 !== param2) {
//         respuesta = true;
//     }
//    return respuesta;    
// }

document.addEventListener('DOMContentLoaded', () => {    
    let formmo = document.querySelector('#formulario-mo');           
    // Second form
    formmo.onsubmit = function (params) {                
        params.preventDefault();
        compareData();        
    }    
});  //end addEventListener