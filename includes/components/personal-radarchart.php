<?php
include ('includes/a_config.php');

// Menentukan label berdasarkan role
$labels_array = ['MSK', 'KT', 'PSSP', 'PNG', '5JQ'];
if (in_array($member['role'], $roles_with_kao)) {
    $labels_array[] = 'KAO';
}
$labels = json_encode($labels_array);

// Menentukan data skor berdasarkan label
$data_array = [
    $member['msk'],
    $member['kt'],
    $member['pssp'],
    $member['png'],
    $member['fivejq']
];
if (in_array($member['role'], $roles_with_kao)) {
    $data_array[] = $member['kao'];
}
$data = json_encode($data_array);

$chart_id = "radar_" . $member['npk'];
?>

<div class="chart-container">
    <canvas id="<?php echo $chart_id; ?>"></canvas>
</div>

<script>
    var ctx_<?php echo $chart_id; ?> = document.getElementById('<?php echo $chart_id; ?>').getContext('2d');
    var style = getComputedStyle(document.body);
    var primCol = style.getPropertyValue('--secondary-color');
    var lightCol = style.getPropertyValue('--main-color');

    new Chart(ctx_<?php echo $chart_id; ?>, {
        type: 'radar',
        data: {
            labels: <?php echo $labels; ?>,
            datasets: [{
                label: 'score',
                data: <?php echo $data; ?>,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                line: {
                    borderWidth: 2
                }
            },
            scales: {
                r: {
                    grid: {
                        color: lightCol
                    },
                    angleLines: {
                        color: 'rgba(255,255,255,0)'
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
                    display: false
                }
            }
        }
    });
</script>

<style>
    .chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 170px; /* Sesuaikan agar tidak terlalu kecil */
        max-width: 300px; /* Batas ukuran maksimum */
        margin: auto;
        padding: 10px;
    }
    canvas {
        max-width: 100%;
        max-height: 100%;
    }
</style>
