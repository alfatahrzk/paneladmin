<?php

// Function to fetch document counts by jenisDokumen for a given bidang
function getDocumentCountsByBidang($conn, $bidang)
{
  $sql = "SELECT jenisDokumen, COUNT(*) as count FROM dokumen WHERE bidangDokumen = ? GROUP BY jenisDokumen";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $bidang);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $labels = [];
  $counts = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['jenisDokumen'];
    $counts[] = (int)$row['count'];
  }

  mysqli_stmt_close($stmt);
  return ['labels' => $labels, 'counts' => $counts];
}

// Fetch data for each bidang
$bidangs = [
  'Sub Bagian Perencanaan Evaluasi dan Keuangan',
  'Sub Bagian Umum dan Kepegawaian',
  'Seksi Pelayanan Publik', // Changed from Sub Bagian Pelayanan Publik
  'Seksi Pemerintahan',
  'Seksi Pembangunan dan Pemberdayaan Masyarakat',
  'Seksi Ketentraman dan Ketertiban Umum'
];
$chartData = [];

foreach ($bidangs as $bidang) {
  $chartData[$bidang] = getDocumentCountsByBidang($conn, $bidang);
}

// Define colors for charts (consistent across bidangs)
$colors = [
  'backgroundColor' => [
    'rgba(23, 162, 184, 0.7)',
    'rgba(255, 193, 7, 0.7)',
    'rgba(40, 167, 69, 0.7)',
    'rgba(220, 53, 69, 0.7)',
    'rgba(108, 117, 125, 0.7)',
    'rgba(52, 58, 64, 0.7)'
  ],
  'borderColor' => [
    'rgba(23, 162, 184, 1)',
    'rgba(255, 193, 7, 1)',
    'rgba(40, 167, 69, 1)',
    'rgba(220, 53, 69, 1)',
    'rgba(108, 117, 125, 1)',
    'rgba(52, 58, 64, 1)'
  ]
];

// Encode data for JavaScript
$chartDataJson = json_encode($chartData);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Dokumen</title>
  <!-- Include Bootstrap or other CSS if needed -->
  <link rel="stylesheet" href="path/to/bootstrap.css">
</head>

<body>
  <div class="row">
    <div class="col-sm-12">
      <div class="home-tab">
        <div class="tab-content tab-content-basic">
          <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            <div class="row mb-1">
              <div class="col-12">
                <h3 class="page-title text-primary">Ringkasan Dokumen Berdasarkan Jenis Tiap Bidang</h3>
                <p class="card-description">Berikut adalah visualisasi jumlah dokumen yang dikelompokkan berdasarkan jenis untuk setiap bidang terkait.</p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="card-title card-title-dash text-info">Sub Bagian Perencanaan Evaluasi dan Keuangan</h4>
                    </div>
                    <div class="chart-container" style="position: relative; height:220px; width:100%">
                      <canvas id="chartBidangSubBagianPerencanaanEvaluasidanKeuangan"></canvas>
                    </div>
                    <div id="chartBidangSubBagianPerencanaanEvaluasidanKeuangan-legend" class="mt-4 text-center"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="card-title card-title-dash text-success">Sub Bagian Umum dan Kepegawaian</h4>
                    </div>
                    <div class="chart-container" style="position: relative; height:220px; width:100%">
                      <canvas id="chartBidangSubBagianUmumdanKepegawaian"></canvas>
                    </div>
                    <div id="chartBidangSubBagianUmumdanKepegawaian-legend" class="mt-4 text-center"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="card-title card-title-dash text-warning">Seksi Pelayanan Publik</h4>
                    </div>
                    <div class="chart-container" style="position: relative; height:220px; width:100%">
                      <canvas id="chartBidangSeksiPelayananPublik"></canvas>
                    </div>
                    <div id="chartBidangSeksiPelayananPublik-legend" class="mt-4 text-center"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="card-title card-title-dash text-danger">Seksi Pemerintahan</h4>
                    </div>
                    <div class="chart-container" style="position: relative; height:220px; width:100%">
                      <canvas id="chartBidangSeksiPemerintahan"></canvas>
                    </div>
                    <div id="chartBidangSeksiPemerintahan-legend" class="mt-4 text-center"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="card-title card-title-dash text-dark">Seksi Pembangunan dan Pemberdayaan Masyarakat</h4>
                    </div>
                    <div class="chart-container" style="position: relative; height:220px; width:100%">
                      <canvas id="chartBidangSeksiPembangunandanPemberdayaanMasyarakat"></canvas>
                    </div>
                    <div id="chartBidangSeksiPembangunandanPemberdayaanMasyarakat-legend" class="mt-4 text-center"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="card-title card-title-dash text-secondary">Seksi Ketentraman dan Ketertiban Umum</h4>
                    </div>
                    <div class="chart-container" style="position: relative; height:220px; width:100%">
                      <canvas id="chartBidangSeksiKetentramandanKetertibanUmum"></canvas>
                    </div>
                    <div id="chartBidangSeksiKetentramandanKetertibanUmum-legend" class="mt-4 text-center"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Chart options
      const optionsDoughnut = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          animateScale: true,
          animateRotate: true
        },
        plugins: {
          legend: {
            display: false // Use custom HTML legend
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed !== null) {
                  label += context.parsed;
                }
                return label;
              }
            }
          }
        }
      };

      // Function to create custom legend
      function createCustomLegend(chartInstance, legendContainerId) {
        const legendContainer = document.getElementById(legendContainerId);
        if (!legendContainer) {
          console.error('Legend container not found:', legendContainerId);
          return;
        }

        const legendItems = chartInstance.data.labels.map((label, index) => {
          const backgroundColor = chartInstance.data.datasets[0].backgroundColor[index % chartInstance.data.datasets[0].backgroundColor.length];
          return `
                <span class="me-3 d-inline-flex align-items-center" style="cursor: pointer;" onclick="toggleDatasetVisibility(this, '${chartInstance.canvas.id}', ${index})">
                    <span style="background-color:${backgroundColor}; width:12px; height:12px; border-radius:50%; display:inline-block; margin-right:8px;"></span>
                    ${label}
                </span>
            `;
        });
        legendContainer.innerHTML = legendItems.join('');
      }

      // Function to toggle dataset visibility
      window.toggleDatasetVisibility = function(legendItem, chartCanvasId, index) {
        const chart = Chart.getChart(chartCanvasId);
        if (chart) {
          chart.toggleDataVisibility(index);
          chart.update();
          legendItem.classList.toggle('legend-item-hidden');
        } else {
          console.error('Chart not found for toggle:', chartCanvasId);
        }
      };

      // CSS for hidden legend items
      const style = document.createElement('style');
      style.innerHTML = `.legend-item-hidden { text-decoration: line-through; opacity: 0.5; }`;
      document.head.appendChild(style);

      // Dynamic data from PHP
      const chartData = <?php echo $chartDataJson; ?>;
      const colors = <?php echo json_encode($colors); ?>;

      // Initialize charts
      const bidangs = [
        'Sub Bagian Perencanaan Evaluasi dan Keuangan',
        'Sub Bagian Umum dan Kepegawaian',
        'Seksi Pelayanan Publik', // Changed from Sub Bagian Pelayanan Publik
        'Seksi Pemerintahan',
        'Seksi Pembangunan dan Pemberdayaan Masyarakat',
        'Seksi Ketentraman dan Ketertiban Umum'
      ];
      bidangs.forEach(bidang => {
        const canvasId = `chartBidang${bidang.replace(/ /g, '')}`;
        const legendId = `${canvasId}-legend`;
        const ctx = document.getElementById(canvasId);

        if (ctx) {
          const data = {
            labels: chartData[bidang].labels,
            datasets: [{
              label: 'Jumlah Dokumen',
              data: chartData[bidang].counts,
              backgroundColor: colors.backgroundColor.slice(0, chartData[bidang].labels.length),
              borderColor: colors.borderColor.slice(0, chartData[bidang].labels.length),
              borderWidth: 1
            }]
          };

          const chart = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: data,
            options: optionsDoughnut
          });
          createCustomLegend(chart, legendId);
        }
      });
    });
  </script>
</body>

</html>