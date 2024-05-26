import { validateSection } from './validation.js';

export default function setupNavigation() {
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
}
