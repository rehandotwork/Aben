(function ($) {
  "use strict";

  $(document).ready(function () {
    var mediaUploader;

    // Handler for the media uploader button click
    $(document).on("click", ".aben-media-upload-button", function (e) {
      e.preventDefault();

      var $button = $(this);
      var $input = $button.siblings('input[type="hidden"]');
      var $preview = $button.siblings("img");
      var $removeButton = $button.siblings(".aben-media-remove-button");

      if (typeof wp === "undefined" || typeof wp.media === "undefined") {
        console.error("wp.media is not defined.");
        return;
      }

      if (mediaUploader) {
        mediaUploader.open();
        return;
      }

      mediaUploader = wp.media({
        title: "Select or Upload Media",
        button: {
          text: "Use this media",
        },
        multiple: false,
      });

      mediaUploader.on("select", function () {
        var attachment = mediaUploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        $input.val(attachment.url);
        $preview.attr("src", attachment.url).show();
        $removeButton.show(); // Show the Remove Image button
      });

      mediaUploader.open();
    });

    // Handler for the remove image button click
    $(document).on("click", ".aben-media-remove-button", function (e) {
      e.preventDefault();

      var $button = $(this);
      var $input = $button.siblings('input[type="hidden"]');
      var $preview = $button.siblings("img");

      $input.val(""); // Clear the input value
      $preview.attr("src", "").hide(); // Hide the image preview
      $button.hide(); // Hide the Remove Image button
    });
  });
})(jQuery);
