$(document).ready(function () {
  $("#submit").on("click", function (event) {
    event.preventDefault(); // Empêche la soumission immédiate du formulaire
    $("#confirmModal").modal("show"); // Affiche la modal
    $("#confirmSubmit").on("click", function () {
        console.log("submit");
      // Si l'utilisateur clique sur Confirmer, soumettez le formulaire
      $("#submit").closest("form").submit();
    });
  });
});
