(function($) {
    'use strict';
    $(document).ready(function() {

        var config = {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "Unfilled",
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
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
        $("#questions_display").change(function() {
            var displayValues = $("#questions_display").serializeArray();
            var options;
            var hiddenInstance = $('#instances').val();
            var instanceField = '<input type="hidden" name="instance_id" value="' + hiddenInstance + '"/>';
            displayValues.forEach(function(entry) {
                options += '<option  value="' + entry['value'] + '">' + entry['name'] + '</option>';
            });
            $('#populate_chart').html('<select class="question" name="question">' + options + '</select>' + instanceField);

        });

        $("#populate_chart").change(function() {
           var chartTitle = $(".question option:selected"). text();
            var populateData = $("#populate_chart").serialize();
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
                    var returned = data;
                    myChart.options.title.text = chartTitle;
                    myChart.data.datasets[0].data = returned.values;
                    myChart.data.labels = returned.keys;
                    myChart.update();
                    console.log(returned);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        });


    });


})(jQuery);
