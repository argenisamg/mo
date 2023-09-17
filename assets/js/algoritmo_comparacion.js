
document.addEventListener('DOMContentLoaded', () => {
    alert('jala bien');
    // objJSON = JSON.parse(data[0]);            
       // objJSON2 = JSON.parse(data[1]);   
       
       // let $urlMO = "json/mo_000010078976_04-APR-23.json";                     
       // let $urlMO2 = "json/mo_000010078976_04-FEB-23.json";                     
       // Promise.all([
       //     fetch($urlMO),
       //     fetch($urlMO2)
       //   ])
       //   .then(responses => {
       //     return Promise.all(responses.map(response => {
       //       return response.json();
       //     }));
       //   })
       //   .then(data => {
           
           // objJSON = data[0];
           // objJSON2 = data[1];

       const objJSON = [
           {"id": 3, "name": "Tom", "lastname": "Braddy", "age": 40},
           {"id": 1, "name": "Argenis", "lastname": "Munoz", "age": 30},
           {"id": 2, "name": "Marys", "lastname": "Jhonson", "age": 25},           
         ];
         
         const objJSON2 = [
             {"id": 2, "name": "Mary", "lastname": "Jhonson", "age": 25},              
             {"id": 3, "name": "Tom", "lastname": "Braddy", "age": 40},             
             {"id": 1, "name": "Argenis", "lastname": "Munoz", "age": 30},
           
         ];
                        
         let differentObjs = [];                
             objJSON.forEach((obj1) => {
               const { name, lastname } = obj1;                
               const keysToCompare = { name, lastname };
               let found = objJSON2.some((obj2) => {
                   const { name, lastname } = obj2;                    
                 const keysToCompare2 = { name, lastname };
                 //comparar solo por valores:                  
                 return Object.values(keysToCompare).every((value, index) => 
                                       value === Object.values(keysToCompare2)[index]);
                 //claves y valores iguales
               //   return JSON.stringify(keysToCompare) === JSON.stringify(keysToCompare2); 
               });

               if (!found) {
                 differentObjs.push(obj1);
               }

             });                       
             
             // verificar si el objeto objJSON2 es mas grande que objJSON
               if (objJSON2.length > objJSON.length) {
                   console.log('Objeto 2 es mayor')
                   for (const obj2 of objJSON2) {
                       let found = false;
                       const { name, lastname } = obj2;                
                       const keysToCompare2 = { name, lastname };
                       for (const obj1 of objJSON) {
                           const { name, lastname } = obj1;                
                           const keysToCompare = { name, lastname };
                           
                           valuesMatch = Object.values(keysToCompare2).every((value, index) => 
                                       value === Object.values(keysToCompare)[index]);

                           if (valuesMatch) {
                               found = true;
                               break;
                           }
                   }
                       if (!found) {
                           differentObjs.push(obj2);
                       }
                   }
               }

             if (differentObjs.length > 0) {    
                 console.log(false);
                 console.log(`Objetos diferentes: ${JSON.stringify(differentObjs)}`);      
               } else {
                   console.log(true);
                   console.log('Todo OK!');
               }           
                                 
                       
       //     })             
       //   .catch(error => console.error(error));
   });
 