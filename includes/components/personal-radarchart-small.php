<?php
include('includes/a_config.php');

// Jika tidak ada dalam peran dengan KAO, maka gunakan label standar.
if (!in_array($member['role'], $roles_with_kao)) {
    $labels = "['Process', 'EHS', 'Quality']";
} else {
    $labels = "['Process', 'EHS', 'Quality']"; // Memastikan semua label dalam tanda kutip dan dipisahkan dengan koma
}

// Data untuk radar chart
$data = "[" . $member['process'] . "," . $member['ehs'] . "," . $member['quality'] . "]";

// Membuat ID unik untuk canvas berdasarkan NPK karyawan
$chart_id = "radar_" . $member['npk'];
?>

<div>
    <canvas id="<?php echo $chart_id; ?>"></canvas> <!-- Menyesuaikan ukuran -->
</div>

<script>
    var ctx = document.getElementById('<?php echo $chart_id; ?>').getContext('2d');

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: <?php echo $labels; ?>,
            datasets: [{
                label: '', // Menghilangkan label score
                data: <?php echo $data; ?>,
                borderWidth: 1,
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Warna biru dengan transparansi
                borderColor: 'rgba(54, 162, 235, 1)', // Warna biru
                pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Warna biru
                pointBorderColor: '#fff',
                pointRadius: 4 // Membesarkan radius titik
            }]
        },
        options: {
            scales: {
                r: {
                    angleLines: {
                        display: false
                    },
                    min: 0,
                    max: 4,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            elements: {
                point: {
                    radius: 2 // Memperbesar radius titik
                }
            },
            plugins: {
                legend: {
                    display: false // Menyembunyikan legend
                }
            },
            maintainAspectRatio: false // Menjaga aspek rasio saat mengubah ukuran
        }
    });
</script>
