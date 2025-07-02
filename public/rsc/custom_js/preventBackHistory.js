// preventBackHistory.js 
console.log('preventBackHistory.js loaded');

window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        location.reload();
    }
});
