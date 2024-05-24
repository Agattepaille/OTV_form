$(document).ready(function () {
  const sections = $(".form-section");
  let currentSectionIndex = 0;
  const maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes

  sections.hide();
  sections.eq(currentSectionIndex).show();

  $("#navTabs a").click(function (e) {
    e.preventDefault();
    const targetSection = $(this).attr("data-section");
    const targetIndex = sections.index($("#" + targetSection));
    if (targetIndex !== -1) {
      if (
        targetIndex > currentSectionIndex &&
        !validateSection(currentSectionIndex)
      ) {
        return;
      }
      sections.hide();
      sections.eq(targetIndex).show();
      $("#navTabs a").removeClass("active");
      $(this).addClass("active");
      currentSectionIndex = targetIndex;
      if (targetSection === "summary") {
        generateRecap();
      }
    }
  });

  $(".btn_next").click(function () {
    if (validateSection(currentSectionIndex)) {
      if (currentSectionIndex < sections.length - 1) {
        sections.eq(currentSectionIndex).hide();
        currentSectionIndex++;
        sections.eq(currentSectionIndex).show();
        $("#navTabs a").removeClass("active");
        $(
          '#navTabs a[data-section="' +
            sections.eq(currentSectionIndex).attr("id") +
            '"]'
        ).addClass("active");
        if (sections.eq(currentSectionIndex).attr("id") === "summary") {
          generateRecap();
        }
      }
    }
  });

  $(".btn_previous").click(function () {
    if (currentSectionIndex > 0) {
      sections.eq(currentSectionIndex).hide();
      currentSectionIndex--;
      sections.eq(currentSectionIndex).show();
      $("#navTabs a").removeClass("active");
      $(
        '#navTabs a[data-section="' +
          sections.eq(currentSectionIndex).attr("id") +
          '"]'
      ).addClass("active");
    }
  });

  $("input, select, textarea").focus(function () {
    $(this).removeClass("is-invalid");
    $(this).next(".invalid-feedback").hide();
  });

  function validateStartDate(startDateInput, twoDaysLater) {
    const startDate = new Date(startDateInput.val());
    if (startDate < twoDaysLater) {
      startDateInput.addClass("is-invalid");
      const errorMessage = startDateInput.next(".invalid-feedback");
      const message =
        "La demande doit être effectuée au moins 48h avant la date de départ.";

      if (errorMessage.length > 0) {
        errorMessage.show().text(message);
      } else {
        startDateInput.after(`<div class="invalid-feedback">${message}</div>`);
      }
      return false;
    } else {
      startDateInput.removeClass("is-invalid");
      startDateInput.next(".invalid-feedback").hide();
      return true;
    }
  }

  function validateEndDateAfterStartDate(startDateInput, endDateInput) {
    const startDate = new Date(startDateInput.val());
    const endDate = new Date(endDateInput.val());
    console.log("Start Date:", startDate); // Log pour vérifier la date de début
    console.log("End Date:", endDate); // Log pour vérifier la date de fin

    if (startDate > endDate) {
      endDateInput.addClass("is-invalid");
      const errorMessage = endDateInput.next(".invalid-feedback");
      const message =
        "La date de fin doit être postérieure à la date de début.";

      if (errorMessage.length > 0) {
        errorMessage.show().text(message);
      } else {
        endDateInput.after(`<div class="invalid-feedback">${message}</div>`);
      }
      return false;
    } else {
      endDateInput.removeClass("is-invalid");
      endDateInput.next(".invalid-feedback").hide();
      return true;
    }
  }

  function validateMaxInterval(startDateInput, endDateInput, maxInterval) {
    const startDate = new Date(startDateInput.val());
    const endDate = new Date(endDateInput.val());

    if (endDate - startDate > maxInterval) {
      endDateInput.addClass("is-invalid");
      const errorMessage = endDateInput.next(".invalid-feedback");
      const message = "La durée maximale de 3 semaines est dépassée.";

      if (errorMessage.length > 0) {
        errorMessage.show().text(message);
      } else {
        endDateInput.after(`<div class="invalid-feedback">${message}</div>`);
      }
      return false;
    } else {
      endDateInput.removeClass("is-invalid");
      endDateInput.next(".invalid-feedback").hide();
      return true;
    }
  }

  function validateInput(input) {
    let valid = true;
    const isRequired = input.prop("required");
    const value = input.val();

    if (!input[0].checkValidity()) {
      valid = false;
      input.addClass("is-invalid");
      const errorMessage = input.next(".invalid-feedback");
      const message = input[0].validationMessage;

      if (errorMessage.length > 0) {
        errorMessage.show().text(message);
      } else {
        input.after(`<div class="invalid-feedback">${message}</div>`);
      }
    } else {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
    }

    if (isRequired && !value) {
      valid = false;
      input.addClass("is-invalid");
      const errorMessage = input.next(".invalid-feedback");
      const message = "Ce champ est requis.";

      if (errorMessage.length > 0) {
        errorMessage.show().text(message);
      } else {
        input.after(`<div class="invalid-feedback">${message}</div>`);
      }
    }

    if (value) {
      let regex, message;

      switch (input.attr("name")) {
        case "mobilePhone":
          regex =
            /^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/;
          message = "Veuillez entrer un numéro de téléphone mobile valide.";
          break;
        case "email":
          regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          message = "Veuillez entrer une adresse email valide.";
          break;
        case "file":
          regex = /\.(pdf|jpeg|png|gif|svg)$/i;
          message =
            "Veuillez sélectionner un fichier PDF, JPEG, PNG, GIF ou SVG valide.";
          break;
        default:
          regex = null;
          message = "";
      }

      if (regex && !regex.test(value)) {
        valid = false;
        input.addClass("is-invalid");
        const errorMessage = input.next(".invalid-feedback");
        if (errorMessage.length > 0) {
          errorMessage.show().text(message);
        } else {
          input.after(`<div class="invalid-feedback">${message}</div>`);
        }
      }
    }

    // Additional file size validation
    if (input.attr("type") === "file" && input[0].files.length > 0) {
      const file = input[0].files[0];
      if (file.size > maxFileSize) {
        valid = false;
        input.addClass("is-invalid");
        const errorMessage = input.next(".invalid-feedback");
        const message =
          "Le fichier dépasse la taille maximale autorisée de 5 Mo.";

        if (errorMessage.length > 0) {
          errorMessage.show().text(message);
        } else {
          input.after(`<div class="invalid-feedback">${message}</div>`);
        }
      }
    }

    return valid;
  }

  function validateSection(index) {
    const inputs = sections.eq(index).find("input, select, textarea");
    let valid = true;
    const now = new Date();
    const twoDaysLater = new Date(now.getTime() + 48 * 60 * 60 * 1000);
    const maxInterval = 21 * 24 * 60 * 60 * 1000; // 3 weeks in milliseconds

    inputs.each(function () {
      if (!validateInput($(this))) {
        valid = false;
      }
    });

    const startDateInput = sections.eq(index).find('input[name="start_Date"]');
    const endDateInput = sections.eq(index).find('input[name="end_Date"]');

    if (startDateInput.length > 0 && endDateInput.length > 0) {
      if (!validateStartDate(startDateInput, twoDaysLater)) valid = false;
      else if (!validateEndDateAfterStartDate(startDateInput, endDateInput))
        valid = false;
      else if (!validateMaxInterval(startDateInput, endDateInput, maxInterval))
        valid = false;
    }

    return valid;
  }

  function generateRecap() {
    const recapContent = $("#recapContent");
    recapContent.empty();

    const formData = $("#otvForm").serializeArray();
    let recapHtml = "<ul>";

    formData.forEach(function (field) {
      const inputElement = $('[name="' + field.name + '"]');
      let label = $('label[for="' + field.name + '"]')
        .text()
        .trim();
      let value = field.value;

      if (field.name === "file") {
        value = inputElement.prop("files")[0].name;
      }

      if (value === "" || value === "false" || field.name === "_token") {
        return;
      }

      if (inputElement.is(":checkbox")) {
        value = inputElement.prop("checked") ? "Oui" : "Non";
      } else if (inputElement.is(":radio")) {
        const groupName = inputElement.attr("name");
        const selectedRadio = $('input[name="' + groupName + '"]:checked');
        const groupLegend = selectedRadio
          .closest(".radio-group")
          .find("label")
          .text()
          .trim();
        const selectedRadioLabel = selectedRadio.next("label").text().trim();
        label = groupLegend; // Ajoutez un point d'interrogation à la fin du libellé du groupe
        value = selectedRadioLabel; // Utilisez la valeur du bouton radio sélectionné
      }

      if (field.name === "district" && value === "default_district_value") {
        return;
      }

      // Convert date to d-m-Y format
      if (inputElement.attr("type") === "date") {
        const date = new Date(value);
        value =
          ("0" + date.getDate()).slice(-2) +
          "-" +
          ("0" + (date.getMonth() + 1)).slice(-2) +
          "-" +
          date.getFullYear();
      }

      recapHtml += `<li><strong>${label} :</strong> ${value}</li>`;
    });

    recapHtml += "</ul>";
    recapContent.append(recapHtml);
  }

  $("#addEmergencyContactBtn").click(function () {
    if ($(".emergency-contact1").is(":hidden")) {
      $(".emergency-contact1").show();
    } else if ($(".emergency-contact2").is(":hidden")) {
      $(".emergency-contact2").show();
    } else if ($(".emergency-contact3").is(":hidden")) {
      $(".emergency-contact3").show();
      $(this).hide();
    }
  });
});
