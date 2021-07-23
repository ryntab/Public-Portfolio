(function ($) {
  "use strict";
  $(window).load(function () {
    tippy(".public-ticker", {
      position: "bottom",
      content: "Loading Data",
      interactive: true,
      allowHTML: true,
      onShow(instance) {
        let ticker = instance.reference.dataset.symbol;
        let interval = 'DAY';
        let url = location.protocol + '//' + location.host + '/wp-json/public/stock/' + ticker + '/' + interval
        fetch(url).then(response => response.json())
        .then(data => {
          console.log(data);
          instance.setContent(data.symbol);
        });
      },
    });
    console.log(auth);
  });

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
})(jQuery);
