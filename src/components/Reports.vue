<template>
  <div class="container">
    <div id="survey"></div>
    <div class="row">
      <div id="survey"></div>
    </div>
    <div class="row">
      <!-- <div class="col-8">
        <figure class="highcharts-figure">
          <div id="parent-container">
            <div id="play-controls" class="play-controls-btn">
              <button id="play-pause-button" class="fa fa-play play-pause-button-btn" title="play"></button>
              <input id="play-range" class="play-range-btn" type="range" value="1960" min="1960" max="2018" />
            </div>
            <div id="container" class="container-section"></div>
          </div>
          <p class="highcharts-description">
            Bar chart showing the world population by countries from 1960 to 2018.
          </p>
        </figure>
      </div> -->
    </div>
    <div class="row" v-for="(qn,key) in qnResponses" :key="`${key}__question`">
      <div :class="`col-8 text-left ${key}`">
        <h1 v-if="key !== 0">
          <span v-if="qnResponses[key-1].name != qnResponses[key].name"> {{qnResponses[key].name}}</span>
        </h1>
        <h1 v-else>
          <span>{{qnResponses[0].name}}</span>
        </h1>
        <h4>{{qn.question}}</h4>
        <p>
          <!-- {{qn}} -->
          <ul class="list-group" v-for="(resItem,k) in qn.response" :key="`${k}__response`">
            <li class="list-group-item">{{resItem.val}} - {{resItem._count}}</li>
          </ul>
        </p>
        <hr>
        <!-- <div id="play-controls" class="play-controls-btn">
          <button :id="`play-pause-button-${key}`" class="fa fa-play  play-pause-button-btn" title="play"></button>
          <input :id="`play-range-${key}`" class="play-range-btn" type="range" value="1960" min="1960" max="2018" />
        </div>
        <figure class="highcharts-figure">
          <div :id="`parent-container-${key}`">
            <div :id="`container-${key}`" class="container-section"></div>
          </div>
        </figure> -->
      </div>
    </div>
  </div>
</template>

<script>
// import $ from "query";
import Axios from 'axios';
import { async } from 'q';
export default {
  name: 'Report',
  data() {

    return {
      qnResponses:[],
      title: 'How long do you estimate it will take to migrate your entire organization to Windows 10?',
      takeaway: 'Most companies expect to complete their migrations in the next 12 months.',
      excerpt: 'Forty percent of respondents suggested their company will complete their Windows 10 migration in six months or less. Another 21% expect it to take a full year. Over a third (39%) plan to take more than a year to move all their systems to the new OS.',
      responses: 420,
      data: {
        opt1: {
          answer: '1 month or less',
          count: 45,
        },
        opt2: {
          answer: '2-3 months',
          count: 55,
        },
        opt3: {
          answer: '4-6 months',
          count: 65,
        },
        opt4: {
          answer: '7-12 months',
          count: 90,
        },
        opt5: {
          answer: 'More than 1 year',
          count: 165,
        }
      }
    };
  },
  async mounted() {
    let app = this;
    app.items = [1, 2, 3],
      await app.getDataQuestions();
    // title, takeaway, excerpt, total, data, n
    // app.graphData(
    //   app.title,
    //   app.takeaway,
    //   app.excerpt,
    //   app.total,
    //   app.data,
    //   1
    // );

  //  await app.drawBarChart();
  },
  methods: {
    async getDataQuestions() {
      let app = this;
      const url = 'http://localhost:2016/api/get_survey_response';
      Axios.get(url).then(async response => {
        console.log({ results: response.data.responses });
        app.qnResponses = response.data.responses;
        await  app.renderResponses()
      }).catch((err) => {
        console.error({ err });
      });
    },
    async renderResponses(){
      let app = this;
      app.qnResponses.forEach(async (element,index) => {
        console.log({element,index});
        await app.drawBarChart(element,index)
      });
    },
    async drawBarChart(item,index){
      console.log({item,index});
      //chart start

    const startYear = 1960,
      endYear = 2018,
      btn = document.getElementById(`play-pause-button-${index}`),
      input = document.getElementById(`play-range-${index}`),
      nbr = 20;

    let dataset, chart;


    /*
     * Animate dataLabels functionality
     */
    (function (H) {
      const FLOAT = /^-?\d+\.?\d*$/;

      // Add animated textSetter, just like fill/strokeSetters
      H.Fx.prototype.textSetter = function () {
        let startValue = this.start.replace(/ /g, ""),
          endValue = this.end.replace(/ /g, ""),
          currentValue = this.end.replace(/ /g, "");

        if ((startValue || "").match(FLOAT)) {
          startValue = parseInt(startValue, 10);
          endValue = parseInt(endValue, 10);

          // No support for float
          currentValue = Highcharts.numberFormat(
            Math.round(startValue + (endValue - startValue) * this.pos),
            0
          );
        }

        this.elem.endText = this.end;

        this.elem.attr(this.prop, currentValue, null, true);
      };

      // Add textGetter, not supported at all at this moment:
      H.SVGElement.prototype.textGetter = function () {
        const ct = this.text.element.textContent || "";
        return this.endText ? this.endText : ct.substring(0, ct.length / 2);
      };

      // Temporary change label.attr() with label.animate():
      // In core it's simple change attr(...) => animate(...) for text prop
      H.wrap(H.Series.prototype, "drawDataLabels", function (proceed) {
        const attr = H.SVGElement.prototype.attr,
          chart = this.chart;

        if (chart.sequenceTimer) {
          this.points.forEach(point =>
            (point.dataLabels || []).forEach(
              label =>
              (label.attr = function (hash) {
                if (hash && hash.text !== undefined) {
                  const text = hash.text;

                  delete hash.text;

                  return this
                    .attr(hash)
                    .animate({
                      text
                    });
                }
                return attr.apply(this, arguments);

              })
            )
          );
        }

        const ret = proceed.apply(
          this,
          Array.prototype.slice.call(arguments, 1)
        );

        this.points.forEach(p =>
          (p.dataLabels || []).forEach(d => (d.attr = attr))
        );

        return ret;
      });
    }(Highcharts));


    function getData(itemVariable) {
      console.log({itemVariable})
      const output = Object.entries(item.response[0])
        .map(country => {
          return country;
          // const [countryName, countryData] = country;
          // return [countryName, Number(countryData[year])];
        })
        .sort((a, b) => b[1] - a[1]);
      return [output[0], output.slice(1, nbr)];
    }

    function getSubtitle(itemValue,itemTotal) {
      const population = ( itemValue._count/itemTotal ).toFixed(2);
      return `<span style="font-size: 80px">${itemValue.val}</span>
        <br>
        <span style="font-size: 22px">
            Total: <b>: ${population}</b> billion
        </span>`;
    }

    (async () => {

      // dataset = await fetch(
      //   'https://demo-live-data.highcharts.com/population.json'
      // ).then(response => response.json());


      chart = Highcharts.chart(`container-${index}`, {
        chart: {
          animation: {
            duration: 500
          },
          marginRight: 50
        },
        title: {
          text: 'World population by country',
          align: 'left'
        },
        subtitle: {
          useHTML: true,
          // text: getSubtitle(item,item.total),
          floating: true,
          align: 'right',
          verticalAlign: 'middle',
          y: -20,
          x: -100
        },

        legend: {
          enabled: false
        },
        xAxis: {
          type: "category"
        },
        yAxis: {
          opposite: true,
          tickPixelInterval: 150,
          title: {
            text: null
          }
        },
        plotOptions: {
          series: {
            animation: false,
            groupPadding: 0,
            pointPadding: 0.1,
            borderWidth: 0,
            colorByPoint: true,
            dataSorting: {
              enabled: true,
              matchByName: true
            },
            type: "bar",
            dataLabels: {
              enabled: true
            }
          }
        },
        series: [{
          type: 'bar',
          name: startYear,
          data: item.response
        }],
        responsive: {
          rules: [{
            condition: {
              maxWidth: 550
            },
            chartOptions: {
              xAxis: {
                visible: false
              },
              subtitle: {
                x: 0
              },
              plotOptions: {
                series: {
                  dataLabels: [{
                    enabled: true,
                    y: 8
                  }, {
                    enabled: true,
                    format: '{point.name}',
                    y: -8,
                    style: {
                      fontWeight: 'normal',
                      opacity: 0.7
                    }
                  }]
                }
              }
            }
          }]
        }
      });
    })();

    /*
     * Pause the timeline, either when the range is ended, or when clicking the pause button.
     * Pausing stops the timer and resets the button to play mode.
     */
    function pause(button) {
      button.title = "play";
      button.className = "fa fa-play";
      clearTimeout(chart.sequenceTimer);
      chart.sequenceTimer = undefined;
    }

    /*
     * Update the chart. This happens either on updating (moving) the range input,
     * or from a timer when the timeline is playing.
     */
    function update(increment) {
      if (increment) {
        input.value = parseInt(input.value, 10) + increment;
      }
      if (input.value >= endYear) {
        // Auto-pause
        pause(btn);
      }

      chart.update({
        subtitle: {
          // text: getSubtitle()
          text: ""
        }
      },
        false,
        false,
        false
      );

      chart.series[0].update({
        name: input.value,
        data:[],
        // data: getData(input.value)[1]
      });
    }

    /*
     * Play the timeline.
     */
    function play(button) {
      button.title = "pause";
      button.className = "fa fa-pause";
      chart.sequenceTimer = setInterval(function () {
        update(1);
      }, 500);
    }

    btn.addEventListener("click", function () {
      if (chart.sequenceTimer) {
        pause(this);
      } else {
        play(this);
      }
    });
    /*
     * Trigger the update on the range bar click.
     */
    input.addEventListener("click", function () {
      update();
    });

    //chart end
    }
    //   async graphData(title, takeaway, excerpt, total, data, n) {
    //     let app = this;
    //     let question = $('#survey');
    //     let questions = [];
    //     let graph = $('<div></div>');
    //     let table = $('<div></div>');
    //     let totalResp = $('<div></div>');
    //     // app.title = title;
    //     // app.takeaway = takeaway;
    //     // app.excerpt = excerpt;
    //     // app.total = total;
    //     // app.data = data;



    //     // added question


    //     let over50 = false;
    //     graph.addClass('question-graph');

    //     table
    //       .addClass('question-table')
    //       .html('<div class="j-row question-table-header">' + '<div class="j-col j-col-8"><h4 class="white">Answer Choices</h4></div>' + '<div class="j-col j-col-4"><h4 class="white">Responses</h4></div>' + '</div>' + '<div class="j-row question-table-content"></div>');

    //     question.attr({
    //       'class': 'question collapsible-section js-scroll',
    //       'id': 'question-' + (n + 1),
    //     })
    //       .html('<h2 class="question-title blue collapsible-title is-expanded">' + title + '</h2>' + '<div class="collapsible-section--content is-expanded">' + '<div class="question-takeaway"><strong>Takeaway: ' + takeaway + '</strong></div>' + '<p class="question-excerpt">' + excerpt + '</p>' + '</div>')
    //       .appendTo('.survey-container')
    //       .find('.question-excerpt')
    //       .before(graph)
    //       .after(table);
    //   },
  },
};
</script>

<style lang="css" scoped>
@import "https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css";

.highcharts-figure {
  margin: 0;
}

.play-controls-btn {
  max-width: 1000px;
  margin: 1em auto;
}

.container-section {
  height: 900px;
  max-width: 1000px;
  margin: 0 auto;
}

.play-pause-button-btn {
  margin-left: 10px;
  width: 30px;
  height: 30px;
  cursor: pointer;
  border: 1px solid silver;
  border-radius: 3px;
  background: #f8f8f8;
}

.play-range-btn {
  transform: translateY(2.5px);
  width: calc(100% - 50px);
  background: #f8f8f8;
}
</style>
