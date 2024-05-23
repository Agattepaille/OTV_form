export default function setupEmergencyContacts() {
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
  }
  