(function($) {
    'use strict';
    $(document).ready(function() {
        var config = {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    //label: "Unfilled",
                    fill: false,
                    backgroundColor: '#000000',
                    borderColor: '#ff0000',
                    data: [
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor()
                    ]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Chart.js Line Chart'
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
                            labelString: 'Month'
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


        $('#instances').change(function() {
            var optionSelected = $(this).find("option:selected");
            var valueSelected = optionSelected.val();
            var textSelected = optionSelected.text();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    'instance_id': valueSelected,
                    'action': 'get_instance_questions'
                },
                success: function(data) {
                    $('#questions_display').html(data);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });


        });
        /*$("#questions_display").change(function() {
           const data = new FormData('questions_display');
           console.log(Array.from(data));
            var displayValues = $(this).serializeArray();
            console.log(displayValues);
            var options;
            var hiddenInstance = $('#instances').val();
            var instanceField = '<input type="hidden" name="instance_id" value="' + hiddenInstance + '"/>';
            displayValues.forEach(function(entry) {
                options += '<option  value="' + entry['value'] + '">' + entry['name'] + '</option>';
            });
            $('#populate_chart').html('<select class="question" name="question">' + options + '</select>' + instanceField);
        });*/
        $("#questions_display").submit(function() {

            var displayValues = $(this).serialize();
            //console.log(displayValues);
            /*var options;
            var hiddenInstance = $('#instances').val();
            var instanceField = '<input type="hidden" name="instance_id" value="' + hiddenInstance + '"/>';
             displayValues.forEach(function(entry) {
                 options += '<option  value="' + entry['value'] + '">' + entry['name'] + '</option>';
             });
             $('#populate_chart').html('<select class="question" name="question">' + options + '</select>' + instanceField);*/
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    'settings_data': displayValues,
                    'action': 'parse_chart_options'
                },
                success: function(data) {
                    $('#populate_chart').html(data);
                    //console.log(data);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
            return false;
        });

        $("#populate_chart").change(function() {
            var chartTitle = $(".question option:selected").text();
            var populateData = $("#populate_chart").serialize();
            //console.log(populateData);
            $.ajax({
                url: ajaxurl,
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
                    myChart.config = [];
                    var returned = data;
                    if (returned.chart_type == "pie") {
                        var sum = returned.values.reduce(add, 0);
                        returned.values = returned.values.map(function(x) {
                            return x / sum * 100;
                        });
                        var count = returned.values.length;
                        var colorArray = getRandomColors(count);

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
                    //ctx = $("#canvas");
                    myChart = new Chart(ctx, config);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        });


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
