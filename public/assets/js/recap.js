export default function generateRecap() {
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
        console.log(label);
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
  