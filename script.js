function printDocument(){
    window.print();
}

// Auftraege.php
// Get all detail buttons
var btns = document.getElementsByClassName("detail-btn");

// Loop through the buttons and add click event listener
for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener('click', function() {
        // Get the orderID from the clicked button
        var orderID = this.getAttribute('data-id');

        // Get the modal with the corresponding orderID
        var modal = document.getElementById("modal-" + orderID);

        // Show the modal
        modal.style.display = "block";
    });
}

document.getElementById('formZuteilung').addEventListener('submit', function(event) {
    var dataIdElement = document.getElementById('data-btn'); 
    var dataIdValue = dataIdElement.getAttribute('data-id'); // Get the data-id value
    document.getElementById('dataIdField').value = dataIdValue; // Set the value to the hidden input field
});
