import "./styles/map.css";
// Leaflet script + css
import "leaflet";
// import "leaflet/dist/leaflet.css"; /if no dist link
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import "leaflet.markercluster";


let limits = [];

// Initialize map
let map = L.map("map", {
    zoom: 13, 
})

L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
    minZoom: 1, // till you can zoom in
    maxZoom: 20, //till you can zoom out
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
})
    .addTo(map); // otherwise wont be in our map

fetch ("/gamingplaces/addresses") //route path to transform data in JSON
    .then(data => data.json())
    .then(cities =>{
        cities.forEach(city => {
            let coords = [city.lat, city.lon];
            let marker = L.marker(coords).addTo(map);
            
            marker.bindPopup(city.city);
            // markers.addLayer(marker);
            limits.push(coords);
            map.addLayer(marker);
        });
        map.fitBounds(limits);
    })


/*********************** Tuto *************************************/

// Tuto: https://www.youtube.com/watch?v=N3hTwPvn_Xk

// Declare Paris coordinates
// let lat = 48.852969;
// let lon = 2.349903;
// limits = [];

// // Initialize map
// map = L.map("map", {
//     zoom: 13, 
//     center: [lat,lon] // center to Paris
// })

// // Set a tile layer
// L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
//     minZoom: 1, // till you can zoom in
//     maxZoom: 20, //till you can zoom out
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
// })
//     .addTo(map); // otherwise wont be in our map

// // Add marker
// let marker = L.marker([lat,lon]).addTo(map);

// // Use a jsonlist
// fetch("/addressList.json")
//     .then(data => data.json())
//     .then(cities =>{
//         // Use clustering plugin of leaflet to avoid superposition of marker when a lot in the same area.
//         // Create a group
//         let markers = L.markerClusterGroup();
//         //  When you create a marker, you have to put it into the cluster

//         // Then add clusters to map, you then have to suppress .addTo(map) for the individual markers (otherwise theyre will be duplicates)

//         for (let [city, content] of Object.entries(cities)){
//             // console.log(city, content);
//             let coords = [content.lat, content.lon]
//             // Create marker for each city
//             let marker = L.marker(coords).addTo(map);

//             // create pop-up on marker
//             marker.bindPopup(content.description);

//             /** If you want to add an image: 
//              * let popup = ` <div class="popup">
//              *                  <img src="/images/${content.image}" alt ="${city}" width="50" height= "50">
//              *                  <div>
//              *                      <h2>${city}</h2>
//              *                      <p>${content.description}</p>
//              *                  </div>
//              *                 </div>`
//              * marker.bindPopup(popup)
//              * */ 
//             limits.push(coords); // add marker to limits so it autmoatcly unzoom
//             markers.addLayer(marker); //add it in cluster
//         };

//         map.fitBounds(limits) // automaticaly unzoom to see all markers

//         // If you want to change icone image
//         let icone = L.icon({
//             iconUrl: "/images/filename",
//             iconSize: [25,41],
//             iconAnchor: [12.5, 41],
//             popupAnchor: [0,-41]
//         })

        // Then add it to marker after coords
        /** let marker = L.marker(coords, {
         *      icon: icone
         * }).addTo(map);
         */
            // })
// let cities = {
//     "Paris": {
//         "lat": 48.852969,
//         "lon": 2.349903,
//         "description": "Make love not war"
//     },

//     "Brest" :{
//         "lat":48.383,
//         "lon": -4.5,
//         "description": "Il n'y a pas de mauvais temps, que des mauvais vêtements!"
//     },

//     "Bruxelles" : {
//         "lat":50.8466,
//         "lon": 4.3528,
//         "description": "Best capital in the world"
//     }
// }



