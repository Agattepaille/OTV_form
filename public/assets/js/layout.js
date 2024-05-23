$(document).ready(function () {
  const sections = $(".form-section");
  let currentSectionIndex = 0;

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

  // Ajoutez un gestionnaire d'événements pour les clics sur les champs d'entrée
  $("input, select, textarea").focus(function () {
    $(this).removeClass("is-invalid");
    $(this).closest(".invalid-feedback").hide();
  });

  function validateSection(index) {
    const inputs = sections.eq(index).find("input, select, textarea");
    let valid = true;
    inputs.each(function () {
      const input = $(this);
      if (!this.checkValidity()) {
        valid = false;
        input.addClass("is-invalid");
        const errorMessage = input.next(".invalid-feedback");
        if (errorMessage.length > 0) {
          errorMessage.show().text(this.validationMessage);
        } else {
          input.after(
            `<div class="invalid-feedback">${this.validationMessage}</div>`
          );
        }
        console.log("Invalid input:", this);
      } else {
        input.removeClass("is-invalid");
        input.next(".invalid-feedback").hide();
      }

      // Custom regex validation
      const value = input.val();
      let regex, message;

      switch (this.name) {
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
    });
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
        const groupTitle = inputElement
          .closest(".radio-group")
          .next("label")
          .text()
          .trim();
        const selectedRadio = $('input[name="' + groupName + '"]:checked');
        value = selectedRadio.next("label").text().trim();
        label = groupTitle;
      }

      if (field.name === "district" && value === "default_district_value") {
        return;
      }

      recapHtml += `<li><strong>${label} :</strong> ${value}</li>`;
    });

    recapHtml += "</ul>";
    recapContent.append(recapHtml);
  }

  $("#addEmergencyContactBtn").click(function () {
    if ($(".emergency-contact1").is(":hidden")) {
      $(".emergency-contact1").show();
    } else if ($(".additional-contact2").is(":hidden")) {
      $(".additional-contact2").show();
      $(this).hide();
    }
  });
});
