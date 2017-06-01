(function($) {
    'use strict';
    $(document).ready(function() {
      if ($('#canvas').length > 0) {
        var config = {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    //label: "Unfilled",
                    fill: false,
                    backgroundColor: '#000000',
                    borderColor: '#ff0000',
                    data: []
                }]
            },
            options: {
                legend: {
                    display: false
                },
                responsive: true,
                title: {
                    display: true,
                    text: ''
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: false,
                            labelString: ''
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Number of Respondents'
                        },
                        ticks: {
                            suggestedMin: 0, // minimum will be 0, unless there is a lower value.

                        }
                    }]
                }
            }
        };

        var ctx = $("#canvas");
        var myChart = new Chart(ctx, config);

        $("#populate_chart").change(function() {
            var chartTitle = $(".question option:selected").text();
            var populateData = $("#populate_chart").serialize();
				$(".fadeMe").show();
            //console.log(populateData);
            $.ajax({
                url: MyAjax.ajaxurl,
                type: 'POST',
                data: {
                    'chart_data': populateData,
                    'action': 'populate_chart'
                },
                dataType: 'json',
                success: function(data) {
                    //alert(data);
                    //$('#questions_display').html(data);

                    myChart.destroy();
                    var returned = data;
                    //console.log(returned);
                    if (returned != null) {
                        if (returned.chart_type == "pie") {
                           if(returned.values != null)
                           {
                               var sum = returned.values.reduce(add, 0);
                               returned.values = returned.values.map(function(x) {
                                   return x / sum * 100;
                               });
                               var count = returned.values.length;
                               var colorArray = getRandomColors(count);
                           }



                           config = {
                              type: returned.chart_type,
                              data: {
                                 datasets: [{
                                    data: returned.values,
                                    backgroundColor: colorArray,
                                    borderColor: colorArray,
                                    label: 'Dataset 1'
                                 }],
                                 labels: returned.keys
                              },
                              options: {
                                 responsive: true,
                                 title: {
                                    display: true,
                                    text: chartTitle
                                 },
                                 tooltips: {
                                    callbacks: {
                                       label: function(tooltipItem, data) {
                                          var allData = data.datasets[tooltipItem.datasetIndex].data;
                                          var tooltipLabel = data.labels[tooltipItem.index];
                                          var tooltipData = allData[tooltipItem.index];
                                          var total = 0;
                                          for (var i in allData) {
                                             total += allData[i];
                                          }
                                          var tooltipPercentage = Math.round((tooltipData / total) * 100);
                                          return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
                                       }
                                    }
                                 }
                              }
                           };
                            //console.log(config);
                        } else {

                            config = {
                                type: returned.chart_type,
                                data: {
                                    labels: returned.keys,
                                    datasets: [{
                                        //label: "Unfilled",
                                        fill: false,
                                        backgroundColor: '#ff0000',
                                        borderColor: '#000000',
                                        data: returned.values
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    responsive: true,
                                    title: {
                                        display: true,
                                        text: chartTitle
                                    },
                                    tooltips: {
                                        mode: 'index',
                                        intersect: false,
                                    },
                                    hover: {
                                        mode: 'nearest',
                                        intersect: true
                                    },
                                    scales: {
                                        xAxes: [{
                                            display: true,
                                            scaleLabel: {
                                                display: false,
                                            }
                                        }],
                                        yAxes: [{
                                            display: true,
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'Number of Respondents'
                                            },
                                            ticks: {
                                                suggestedMin: 0,
                                            }
                                        }]
                                    }
                                }
                            };

                        }
                        var info = '<p class="info">Chart Info </p>';

                        if (returned.count != null) {
                            info += '<p class="info">Number of Responses: ' + returned.count + '</p>';
                        }
                        if (returned.mean != null) {
                            info += '<p class="info">Mean: ' + returned.mean + '</p>';
                        }
                        if (returned.median != null) {
                            info += '<p class="info">Median: ' + returned.median + '</p>';
                        }
                        if (returned.mode != null) {
                            info += '<p class="info">Mode: ' + returned.mode + '</p>';
                        }
                        if (returned.st_dev != null) {
                            info += '<p class="info">Standard Deviation: ' + returned.st_dev.toFixed(2) + '</p>';
                        }
                        $('#chart_info').html(info);
                        //ctx = $("#canvas");
                        myChart = new Chart(ctx, config);
                    } else {
							  config = {
					            type: 'bar',
					            data: {
					                labels: [],
					                datasets: [{
					                    //label: "Unfilled",
					                    fill: false,
					                    backgroundColor: '#000000',
					                    borderColor: '#ff0000',
					                    data: []
					                }]
					            },
					            options: {
					                legend: {
					                    display: false
					                },
					                responsive: true,
										 maintainAspectRatio: false,
					                title: {
					                    display: true,
					                    text: ''
					                },
					                tooltips: {
					                    mode: 'index',
					                    intersect: false,
					                },
					                hover: {
					                    mode: 'nearest',
					                    intersect: true
					                },
					                scales: {
					                    xAxes: [{
					                        display: true,
					                        scaleLabel: {
					                            display: false,
					                            labelString: ''
					                        }
					                    }],
					                    yAxes: [{
					                        display: true,
					                        scaleLabel: {
					                            display: true,
					                            labelString: 'Number of Respondents'
					                        },
					                        ticks: {
					                            suggestedMin: 0, // minimum will be 0, unless there is a lower value.

					                        }
					                    }]
					                }
					            }
					        };
                        var info = '<p class="info">Chart Info </p>';
                        info += '<p class="info">Number of Responses: ' + 0 + '</p>';
                        info += '<p class="info">Mean: ' + 0 + '</p>';
                        info += '<p class="info">Median: ' + 0 + '</p>';
                        info += '<p class="info">Mode: ' + 0 + '</p>';
                        info += '<p class="info">Standard Deviation: ' + 0 + '</p>';
                        $('#chart_info').html(info);
                    }
						  $(".fadeMe").hide();
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        });

        $(".question").change(function() {
            $('.segment').val('');
            var populateData = $("#populate_chart").serialize();
            //console.log(populateData);
            $.ajax({
                url: MyAjax.ajaxurl,
                type: 'POST',
                data: {
                    'chart_data': populateData,
                    'action': 'populate_segments'
                },
                dataType: 'html',
                success: function(segment_data) {
                    $('#segments').html(segment_data);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        });

        if ($('#populate_chart').length > 0) {
           $(".question").trigger( "change");
           $("#populate_chart").trigger( "change");
        }

}
    });



})(jQuery);

function add(a, b) {
    return a + b;
}

function getPercentage(x, sum) {
    return x / sum * 100 + "%";
}

function getRandomColors(count) {
    var r;
    var b;
    var g;
    var c;
    var colorArray = [];
    for (var i = 0; i < count; i++) {
        r = Math.floor(Math.random() * 200);
        g = Math.floor(Math.random() * 200);
        b = Math.floor(Math.random() * 200);
        c = 'rgba(' + r + ', ' + g + ', ' + b + ", 1" + ')';
        colorArray.push(c.toString(16));
    }
    return colorArray;
}
