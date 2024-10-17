
function changeEvent(eventName, startDate, endDate){
    const DETAILS = `Event name: ${eventName}, Date: ${startDate} - ${endDate}`;
    document.getElementById("eventDetails").innerText = DETAILS;
}