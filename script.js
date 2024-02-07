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

function printdiv(elem) {
  var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById(elem).innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();
  document.body.innerHTML = old_str;
  return false;
}

document.getElementById('formZuteilung').addEventListener('submit', function(event) {
    var dataIdElement = document.getElementById('data-btn'); // Replace with your actual element ID
    var dataIdValue = dataIdElement.getAttribute('data-id'); // Get the data-id value
    document.getElementById('dataIdField').value = dataIdValue; // Set the value to the hidden input field
});
