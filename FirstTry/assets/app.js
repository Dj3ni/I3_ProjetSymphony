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

// Listens to each modif on form's inputs, Ajax Search
SEARCH_FORM.addEventListener("input", function(){
    console.log("Modify form");

    let formData = new FormData (SEARCH_FORM);

    axios.post("/events/search", formData)
        .then( response => {
            // What to do with response
            console.log(response);
            // Clear previous response
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

// // Handling the submit event, prevent reload and manage search
// SEARCH_FORM.addEventListener("submit", function(event){
//     event.preventDefault(); 
//     const SEARCH_INPUT = document.getElementById("SearchInput");
//     let query = SEARCH_INPUT.value;

//     if(query.lenght > 0){
//         // Redirect to page with results
//         window.location.href = "/event/events_show.html.twig" + JSON.encode(query);
//     }

// })
