/**
*    File        : frontend/js/controllers/subjectsController.js
*    Project     : CRUD PHP
*    Author      : Agustin, Salemme Alonso
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Octubre 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

//3.0
export function showAlert(message, type = 'success', time = 5000) {
  const div = document.createElement('div');      //crea el elemento a agregar
  div.className = `w3-panel w3-animate-opacity alert ${   //estilos en css
    type === 'error' ? 'w3-red' :
    type === 'warning' ? 'w3-yellow' : 
    'w3-green'
  }`;
  div.textContent = message;    //inserta el texto en el elemento

  document.body.append(div);    //agrega al body, no importa donde, css lo pone arriba
  setTimeout(() => div.remove(), time);   //desaparece despues de 5seg
}