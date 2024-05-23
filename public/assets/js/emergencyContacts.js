document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.getElementById('addEmergencyContactBtn');
    const additionalContacts1 = document.querySelectorAll('.additional-contact1');
    const additionalContacts2 = document.querySelectorAll('.additional-contact2');

    let clickCount = 0; // Compteur de clics sur le bouton "Ajouter"

    // Cacher initialement les champs supplémentaires
    additionalContacts1.forEach(contact => {
        contact.style.display = 'none';
    });
    additionalContacts2.forEach(contact => {
        contact.style.display = 'none';
    });

    // Écouter l'événement click sur le bouton "Ajouter"
    addButton.addEventListener('click', function() {
        clickCount++; // Incrémenter le compteur de clics

        if (clickCount === 1) {
            // Afficher les champs de la personne 2 au premier clic
            additionalContacts1.forEach(contact => {
                if (contact.style.display === 'none') {
                    contact.style.display = 'block';
                }
            });
        } else if (clickCount === 2) {
            // Afficher les champs de la personne 3 au second clic
            additionalContacts2.forEach(contact => {
                if (contact.style.display === 'none') {
                    contact.style.display = 'block';
                }
            });

            // Masquer le bouton "Ajouter" après l'affichage de la personne 3
            addButton.style.display = 'none';
        }
    });
});
