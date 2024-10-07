// Components
import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import axios from "axios";


// Css
import "./styles/calendar.css";

// Script Calendar
document.addEventListener("DOMContentLoaded", () =>{
    
    // 1. on obtient les événements du controller, on les stocke dans le data-calendrier du div  
    // console.log (document.getElementById ('calendrier').dataset.calendrier);
    let eventsJSONJS = document.getElementById('calendrier').dataset.calendrier;
    // 2. On transforme le JSON en array d'objets JS
    let eventsJSONJSArray = JSON.parse(eventsJSONJS);
    // console.log(eventsJSONJS);
    // console.log(eventsJSONJSArray);

    // 3. On crée le calendrier, associé au div
    let calendarEl = document.getElementById("calendrier");

    // initilialisation du calendrier
    // et définition du comportement du click
    var calendar = new Calendar(calendarEl, {
        // example to see if plugin works :
        // events:[
            // {
            //   title: "coconut",
            //   start: "2024-10-07",
            //   end: "2024-10-09"

            // }
        // ],
        // nous avons notre array déjà en format js (on la crée plus haut)
        events: eventsJSONJSArray,
        // url: data-events-url, // when url property in event entity
        displayEventTime: false, // cacher l'heure
        initialView: "dayGridMonth",
        initialDate: new Date(), // aujourd'hui
        firstDay: 1,
        eventRender : function (info){
            switch (info.event.extendedProps.type){
                case EventType.FESTIVAL:
                    info.el.style.backgroundColor = "red";
                    info.el.style.borderColor = "red"
                break;
            }
        },

        headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,dayGridWeek,dayGridDay",
        },

        // other events for our listener:
        dateClick: function () {
            // When click on date, redirect to create form
            window.location.href = "/create_event";

            // OPTIONNEL: éviter doublons: Obtenir tous les Evenements du calendrier et chercher 
            // un événement ayant 
            // le même title et
            // le même start (ce critére est à vous de le choisir)
            var allEvents = calendar.getEvents();
            var existe = false;
            allEvents.forEach(function(value) 
            {
                
                if (value.title === newEvent.title && 
                new Date(value.start).toDateString() === new Date(newEvent.start).toDateString())
                { 
                existe = true;
                }
            });
            // console.log (existe);

            // on ne rajout pas si l'Evenement existe
            if (!existe){
                axios.post("/create_event", 
                    newEvent) // axios encode le nouvelElement en json automatiquement et l'envoie dans le corps de la Request
                    .then (function (response){
                        // si success dans l'insertion dans la BD
                        console.log (response);
                        // rajouter à calendrier (interface)
                        // d'abord obtenir l'id fourni par Doctrine
                        // et l'incruster dans le nouvel Evenement 
                        newEvent.id = response.data.id;           
                        calendar.addEvent (newEvent);
                    });  
            }
            else {
                console.log ("This event already exist");
            } 
        },

        // Detect click on the event
        eventClick: function (info){
        // console.log (info.event.id);
        let idEvent = info.event.id;
        let eventUrl = "/event/" + idEvent;
        // Redirect to event page
        window.location.href = eventUrl;
        },

        // plugin list
        plugins: [interactionPlugin, dayGridPlugin],
    });

    // Affichage
    calendar.render();

})