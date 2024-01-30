function validateDate() {
    var dateInput = document.getElementById('auftragsDatum');
    var dateError = document.getElementById('dateError');
    var pattern = /^\d{1,2}\/\d{1,2}\/\d{4}$/;

    if (!pattern.test(dateInput.value)) {
        dateError.textContent = 'Bitte geben Sie das Datum im Format TT/MM/JJJJ ein.';
    } else {
        dateError.textContent = '';
    }
}
