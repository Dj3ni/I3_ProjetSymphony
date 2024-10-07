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
    
    // 1. We get events Data from controller via div 
    // console.log (document.getElementById ('calendrier').dataset.calendrier);
    let eventsJSONJS = document.getElementById('calendrier').dataset.calendrier;
    // 2. On transforme le JSON en array d'objets JS
    let eventsJSONJSArray = JSON.parse(eventsJSONJS);
    // console.log(eventsJSONJS);
    // console.log(eventsJSONJSArray);

    // 3. Calendar Creation, settings and events on click
    let calendarEl = document.getElementById("calendrier");

    var calendar = new Calendar(calendarEl, {
        // example to see if plugin works :
        // events:[
            // {
            //   title: "coconut",
            //   start: "2024-10-07",
            //   end: "2024-10-09"

            // }
        // ],
        events: eventsJSONJSArray, //We sent array Json as a value
        // url: data-events-url, //use  when url property in event entity
        displayEventTime: true, // display hours or not
        initialView: "dayGridMonth",
        initialDate: new Date(), // aujourd'hui
        firstDay: 1, //week starts on Monday 
        dayMaxEventRows: 3,// max visible on a row
        // themeSystem: "bootstrap5",
        eventDidMount : function (info){
            // console.log(info.event);
            // console.log(info.event.extendedProps.eventType);
            let eventType = info.event.extendedProps.eventType;
            console.log(eventType);
            switch (eventType){
                case "Festival":
                    info.el.style.backgroundColor = "#fb8500";
                    info.el.style.borderColor = "#fb8500"
                break;
                case "Tournament":
                    info.el.style.backgroundColor = "#ffb703";
                    info.el.style.borderColor = "#ffb703"
                break;
                case "Boardgames_Demo":
                    info.el.style.backgroundColor = "#023047";
                    info.el.style.borderColor = "#023047";
                    info.el.style.color = "#fff";
                break;
                case "Role_Play":
                    info.el.style.backgroundColor = "#219ebc";
                    info.el.style.borderColor = "#219ebc";

                break;
                case "Gaming_Sales":
                    info.el.style.backgroundColor = "#8ecae6";
                    info.el.style.borderColor = "#y8ecae6";
                    // info.el.style.color = "blue";

                break;
                default:
                    info.el.style.backgroundColor = "blue";
                    info.el.style.borderColor = "blue"
                break;
            }
        },

        headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,dayGridWeek,timeGridDay,listWeek",
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
        plugins: [interactionPlugin, dayGridPlugin, listPlugin, timeGridPlugin],
    });

    // Affichage
    calendar.render();

})