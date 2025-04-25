<?php
echo "
<div>
    <canvas id='$chart_id'></canvas>
</div>
<script>
    ctx_list['$chart_id'] = document.getElementById('$chart_id');
    var style = getComputedStyle(document.body);
    var primCol = style.getPropertyValue('--main-color');
    var lightCol = style.getPropertyValue('--font-color-light');
    new Chart(ctx_list['$chart_id'], {
        type: 'radar',
        data: {
        labels: $labels,
        datasets: [{
            label: 'average',
            data: $datas,
            borderWidth: 3
        }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    grid: {
                        color: lightCol
                    },
                    angleLines: {
                        color: 'rgba(255,255,255,0)',
                    },
                    ticks: {
                        stepSize: 1,
                        backdropColor: primCol,
                        color: lightCol
                    },
                    pointLabels: {
                        color: lightCol
                    },
                    min: 0,
                    max: 5
                }
            },
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        color:lightCol
                    }
                }
            }
        }
    });
</script>
";
?>
