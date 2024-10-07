/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

const $ = require ("jquery");
window.jQuery = $;
window.$ = $

import "bootstrap"
import "bootstrap/dist/css/bootstrap.css"
import axios from "axios";


/***************** Hidden submenu nav *****************/ 

const SUB_MENU = document.getElementById("subMenu");
const AVATAR = document.querySelector(".avatar");
// console.log(SUB_MENU);
// console.log(AVATAR);

AVATAR.addEventListener("click", function(){
    SUB_MENU.classList.toggle("open-menu");
})


/********************* Ajax Search Form ****************/ 

const SEARCH_FORM = document.getElementById("SearchForm");
const DIV_RESULT = document.getElementById("SearchResult");
console.log(SEARCH_FORM);
console.log(DIV_RESULT);

// Listens to each modif on form's inputs
SEARCH_FORM.addEventListener("input", function(){
    console.log("Modify form");

    let formData = new FormData (SEARCH_FORM);

    axios.post("/events/search", formData)
        .then( response => {
            // What to do with response
            console.log(response);
            // We will modify the Dom to display the response
            DIV_RESULT.innerHTML = "";
     // We don't need to parse the response, axios does it

        let arrayEvents = response.data; //It's already an array
        let ul = document.createElement("ul");
        
        for(let element of arrayEvents){
            
            // console.log(element);
            let li = document.createElement("li");
            li.innerHTML = element.title;
            ul.appendChild(li);
        }
        DIV_RESULT.appendChild(ul);
    })
})


/****************** */
// // Components
// import { Calendar } from '@fullcalendar/core';
// import interactionPlugin from '@fullcalendar/interaction';
// import dayGridPlugin from '@fullcalendar/daygrid';
// import timeGridPlugin from '@fullcalendar/timegrid';
// import listPlugin from '@fullcalendar/list';

// // Css
// import "./styles/calendar.css";