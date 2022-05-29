<script>
    var options =
    {
        chart: {
            type: '{!! $chart->type() !!}',
            height: {!! $chart->height() !!}
        },
        plotOptions: {
            bar: {!! $chart->horizontal() !!}
        },
        colors: {!! $chart->colors() !!},
        series:
        {!! $chart->dataset() !!},
        dataLabels: {
            enabled: false
        },
        labels: [{!! $chart->labels() !!}],
        title: {
            text: "{!! $chart->title() !!}"
        },
        subtitle: {
            text: '{!! $chart->subtitle() !!}',
            align: '{!! $chart->subtitlePosition() !!}'
        },
        xaxis: {
            categories: {!! $chart->xAxis() !!}
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }                
            },
        },
        grid: {!! $chart->grid() !!},
    }

    var chart = new ApexCharts(document.querySelector("#{!! $chart->id() !!}"), options);
    chart.render();

</script>
