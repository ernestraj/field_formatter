/**
 * @file
 * Placeholder file for custom behaviors.
 *
 */
(function ($, Drupal) {
  Drupal.behaviors.custom_field_formatter = {
    attach: function (context, settings) {
      $(".custom-qtip", context).qtip({
        content: {
          text: "My common piece of text here",
        },
      });
    },
  };
})(jQuery, Drupal);
