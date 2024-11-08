
const gamingPlaceChoices = document.querySelectorAll('[name="create_event_form[eventPlaces][0][gamingPlaceChoice]"]');
const existingGamingPlace = document.querySelector('.gaming-place-existing');
const newGamingPlace = document.querySelector('.gaming-place-new');
// console.log(gamingPlaceChoices);
console.log(existingGamingPlace);
console.log(newGamingPlace);



function toggleGamingPlacefields(){
    const selectedChoiceElement = document.querySelector('[name="create_event_form[eventPlaces][0][gamingPlaceChoice]"]:checked');
    // if (!selectedChoiceElement) return;
    // console.log(selectedChoiceElement);
    
    const choice = selectedChoiceElement.value;
    // console.log(choice);
    if (choice === "existing"){
        existingGamingPlace.style.display = "block";
        newGamingPlace.style.display = "none";
    }
    else if(choice === "new"){
        existingGamingPlace.style.display = 'none';
        newGamingPlace.style.display = 'block';
    }
    else{
        existingGamingPlace.style.display = 'none';
        newGamingPlace.style.display = 'none';
    }
}

// I initialize a first time
toggleGamingPlacefields();

// I put a listener to the change choice
gamingPlaceChoices.forEach(gamingPlaceChoice => {
    console.log(gamingPlaceChoice.value);
        gamingPlaceChoice.addEventListener('change', toggleGamingPlacefields);
});