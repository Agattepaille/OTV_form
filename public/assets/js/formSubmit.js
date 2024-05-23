document.getElementById('otvForm').addEventListener('submit', function(event) {
    event.preventDefault();
    sendData();
});

function sendData() {
    var form = document.getElementById('otvForm');
        const formData = new FormData(form);

/*     const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });
    console.log(jsonData);
     */
    fetch('https://localhost:8001/otv/new', {
        method: 'POST',
     
        body: formData,
     
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Network response was not ok: ' + response.status + ' - ' + text);
            });
        }
    
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        alert('Les données ont été envoyées avec succès.');
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Une erreur s\'est produite lors de l\'envoi du fichier : ' + error.message);
    });
}