(function ($) {
  "use strict";
  $(window).load(function () {
    tippy(".public-ticker", {
      trigger: "click",
      position: "bottom",
      content: "Loading Data",
      followCursor: true,
      followCursor: 'horizontal',
      interactive: true,
      allowHTML: true,
      onShow(instance) {
        let ticker = instance.reference.dataset.symbol;
        let interval = 'DAY';
        let url = location.protocol + '//' + location.host + '/wp-json/public/stock/' + ticker + '/' + interval
        fetch(url).then(response => response.json())
        .then(data => {
          let thisTicker = {}
          thisTicker.dateTime = [];
          thisTicker.price = [];
          thisTicker.volume = [];

          let skip = 0;
          let skipInterval = 3;
          data.ticker_data.bars.forEach(element => {
            if (skip == skipInterval){
              thisTicker.dateTime.push(element.timestamp);
              thisTicker.price.push(element.value);
              thisTicker.volume.push(element.volume);
              skip = 0;
              return;
            }
            skip += 1;
          });

          console.log(thisTicker);
          var options = {
            colors: ["#01ffca"],
            chart: {
              toolbar: {
                show: false,
              },
              selection: {
                enabled: false,
              },
              type: 'area',
              curve: 'smooth',
            },
            dataLabels: {
              enabled: false
            },
            markers: {
              size: 0,
            },
            floating: true,
            axisTicks: {
              show: false
            },
            axisBorder: {
              show: false
            },
            labels: {
              show: false
            },
            stroke: {
              show: true,
              curve: 'smooth',
              lineCap: 'butt',
              colors: undefined,
              width: 2,
              dashArray: 0,  
            },
            fill: {
              colors: ['#01ffca'],
              type: "gradient",
              gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 70, 100]
              }
            },
            series: [{
              name: 'sales',
              data: thisTicker.price,
            }],
            yaxis: {
              labels: {
                show: true,
              },
              axisTicks: {
                show: false,
            },
              categories: thisTicker.value,
            },
            xaxis: {
              axisTicks: {
                show: false,
            },
              labels: {
                show: false,
              },
              categories: thisTicker.timestamp,
            },
            annotations: {
              yaxis: [
                {
                  y: 8600,
                  y2: 9000,
                  borderColor: '#000',
                  fillColor: '#FEB019',
                  label: {
                    text: 'Y-axis range'
                  }
                }
              ]
            },
            tooltip: {
              enabled: false,
            }
            
          }

          let tickerChart = '<div id="chart"></div><div class="chart-2"></div>';
          let tickerColor = (data.ticker_data.totalGainPercentage < 0) ? 'red': 'green';
          let tickerElement = `<article data-symbol="${data.ticker_symbol}" class="ticker-box"><a aria-label="$${data.ticker_symbol}"><div data-logo="true" class="public-popup-image"><img alt="CLNE logo" loading="lazy" src="https://universal.hellopublic.com/companyLogos/${data.ticker_symbol}@1x.png" class="css-3j1uxc"></div></a><div class="css-jifwjk"><span class="css-uahqw4"> $${data.ticker_symbol}</span><span class="css-uahqw4 tickerCurrentPrice">${data.ticker_data.lastPrice}</span></div><button class="css-gpmf5p" style="color: var(--color-${tickerColor}); background-color: var(--color-light-${tickerColor});"><span>${data.ticker_data.totalGainPercentage}%</span></button></article>${tickerChart}`;
          
          instance.setContent(tickerElement);

          // new Chartist.Line('.chart-2', {
          //   labels: thisTicker.dateTime,
          //   series: [thisTicker.price],
          // }, {
          //   showArea: true,
          //   showLine: true,
          //   showPoint: false,
          //   axisX: {
          //     showLabel: false,
          //     showGrid: false
          //   },
         
          //   lineSmooth: Chartist.Interpolation.cardinal({
          //     tension: 10
          //   })
          // });

          var chart = new ApexCharts(document.querySelector("#chart"), options);

          console.log(chart);

          chart.render();
        });
      },
    });
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
