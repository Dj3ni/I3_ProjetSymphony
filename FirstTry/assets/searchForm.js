



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
