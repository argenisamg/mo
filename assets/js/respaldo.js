let cadenaJSON = "";
let cadenaJSON2 = "";
document.addEventListener('DOMContentLoaded', () => {
                                                                                                                            
        let $urlCrd = "json/mo_000010078976_04-APR-23.json";                     
        let $urlCrd2 = "json/mo_000010078976_04-FEB-23.json";                     
        Promise.all([
            fetch($urlCrd),
            fetch($urlCrd2)
          ])
          .then(responses => {
            return Promise.all(responses.map(response => {
              return response.json();
            }));
          })
          .then(data => {
            //convertir en stringyfy el json:
            cadenaJSON = JSON.stringify(data[0]);
            cadenaJSON2 = JSON.stringify(data[1]);
            //console.log(cadenaJSON); // Muestra el objeto JSON del primer archivo por consola
            //console.log(cadenaJSON2); // Muestra el objeto JSON del segundo archivo por consola
            const comparation = (cadenaJSON === cadenaJSON2) ? true : false;

                if (comparation === false) {
                    let differentObjs = [];
                    objJSON = JSON.parse(JSON.stringify(data[0]));
                    objJSON2 = JSON.parse(JSON.stringify(data[1]));

                    objJSON.forEach((obj1, index) => {
                        const obj2 = objJSON2[index];
                      
                        /**Aqui comparar solo lo que nos importa que es:
                         * cpn y description.
                         * Tomar el primer objeto del json1 y obtener cpn y description para
                         * concatenarlos y compararlos de la misma forma contra todos los objetos y mismas
                         * propiedades del 2nd json:                
                         */

                        if (JSON.stringify(obj1) !== JSON.stringify(obj2)) {
                        //   differentObjs.push(index);
                          differentObjs.push(JSON.stringify(obj1));
                          differentObjs.push(JSON.stringify(obj2));
                        }
                      });
                      
                      if (differentObjs.length === 0) {
                        console.log("Todos los objetos en los JSON son iguales.");
                      } else {
                        console.log("Los objetos diferentes están en las posiciones: " + differentObjs.join(", "));
                      }
                } else {
                    console.log(comparation);              
                }
                // data.forEach(datos => console.log(datos));
                // data.forEach(datos => console.log(datos.description));
                
                //esta variable la voy a obtener automaticamente tomando en cuenta la key: description
                // const nombreBuscado = "Add-on Card";
                // const coincidencias = [];

                // for (const key of Object.keys(data)) {
                // if (data[key].description === nombreBuscado) {
                //     coincidencias.push(data[key]);
                //     //aqui falta la validacion para cuando 'coincidencias' > 1
                // }
                // }
                // coincidencias.forEach(valores => {                    
                //     console.log(valores.cpn);                                    
                // });
            })             
          .catch(error => console.error(error));
});

/**
 * Solucion uno
 * const objJSON = [
    {"id": 1, "name": "Arge", "lastname": "Munoz", "age": 30},
    {"id": 3, "name": "Tom", "lastname": "Braddy", "age": 40},
    {"id": 2, "name": "Mary", "lastname": "Jhonson", "age": 25}
  ];
  
  const objJSON2 = [
    {"id": 2, "name": "Mary", "lastname": "Jhonson", "age": 25},
    {"id": 3, "name": "Tom", "lastname": "Braddy", "age": 40},
    {"id": 1, "name": "Argenis", "lastname": "Munoz", "age": 30}
  ];
  
  var differentObjs = [];
  
  objJSON.forEach((obj1, index1) => {
    const found = objJSON2.find((obj2, index2) => {
      const keys1 = Object.keys(obj1);            
      const keys2 = Object.keys(obj2);
      
      if (keys1.length !== keys2.length) {
        return false;
      }
      
      return keys1.every(key => obj1[key] === obj2[key]);
    });
  
    if (!found) {
      differentObjs.push(obj1);
    }
  });
  
  if (differentObjs.lenth > 0) {    
      console.log(`Objetos diferentes: ${JSON.stringify(differentObjs)}`);      
    } else {
        console.log('Los objetos son iguales :) !');
    }
  
 */


// const consultPartNumberDataBase = function () {
//   var xhttp;    
//   let str = document.getElementById("search").value;          
  
//     if (str == "") {
//         document.getElementById("content").innerHTML = "";
//         document.getElementById("_content").innerHTML = "";
//         return;
//     }
//     xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//           //document.getElementById("content").innerHTML = this.responseText;
//           //console.log(this.responseText);
//           refreshTable(); 
//           refreshTabla_2();
//         } 
//     };
//     xhttp.open("GET", "consulta_db_json_amg.php?q="+str, true);
//     xhttp.send();                 
// }                         