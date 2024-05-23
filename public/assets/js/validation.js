// validation.js
export function validateStartDate(startDateInput, twoDaysLater) {
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

export function validateEndDateAfterStartDate(startDateInput, endDateInput) {
  const startDate = new Date(startDateInput.val());
  const endDate = new Date(endDateInput.val());

  if (startDate > endDate) {
    endDateInput.addClass("is-invalid");
    const errorMessage = endDateInput.next(".invalid-feedback");
    const message = "La date de fin doit être postérieure à la date de début.";

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

export function validateMaxInterval(startDateInput, endDateInput, maxInterval) {
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

export function validateInput(input) {
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
  return valid;
}

export function validateSection(index) {
  const sections = $(".form-section");
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
