jQuery(document).ready(function ($) {
  // Initialize Select2 for all dropdowns
  $("#event-filters select").select2({
    placeholder: "Select an option",
    allowClear: true,
  });

  // AJAX Event Filtering
  $("#event-filters select").on("change", function () {
    const filters = {
      industry: $("#filter-industry").val(),
      specialty: $("#filter-specialty").val(),
      country: $("#filter-country").val(),
    };
    $.post(
      em_ajax.url,
      {
        action: "em_filter_events",
        filters: filters,
      },
      function (response) {
        $("#events-list").html(response);
      }
    );
  });
});
