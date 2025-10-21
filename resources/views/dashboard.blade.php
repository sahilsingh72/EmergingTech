{{-- <x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Statewide Training & Awareness Camps on Emerging Technologies') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          {{ __("You're logged in!") }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout> --}}


<head>
  <title>EmergingTech | Dashboard</title>
  
</head>
<!-- Navbar -->
@include('components.navbar')
<!-- /.navbar -->

<!-- Main Sidebar Container -->
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        @php
          use Illuminate\Support\Facades\Auth;
          $user = Auth::user();
        @endphp
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$completedSchools }} / {{$totalSchools }}</h3>

                  <p>Institutes Completed Training</p>
                </div>
                <div class="icon">
                  <i class="nav-icon fas fa-university "></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{ $students }}<sup style="font-size: 20px"></sup></h3>

                  <p>Total Students</p>
                </div>
                <div class="icon">
                  <i class="nav-icon fas fa-user-graduate"></i>
                </div>

                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{ $totalCoordinators }}</h3>

                  <p>Total Co-ordinator</p>
                </div>
                <div class="icon">
                  <i class="nav-icon fas fa-map "></i>
                </div>
                <a href="{{route('coordinators.index')}}" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{$totalTrainers}}</h3>

                  <p>Total Trainer</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users  nav-icon"></i>
                </div>
                <a href="{{route('trainers.index')}}" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              {{-- <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-home mr-1"></i>
                    Camp Completion Progress
                  </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content p-0">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                    </div>
                  </div>
                </div><!-- /.card-body -->
              </div> --}}

              {{-- <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-image mr-1"></i>
                    Training Gallery
                  </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content p-0">
                    <div class="" style="position: relative; height: 300px;">
  

                    </div>
                  </div>
                </div><!-- /.card-body -->
              </div> --}}
              <!-- /.card -->


              @if($user->role_id == 1 || $user->role_id == 2)
                <!-- solid Upload graph -->
                <div class="card bg-gradient-info">
                  <div class="card-header border-0 justify-content-between align-items-center">
                    <h3 class="card-title">
                      <i class="fas fa-th mr-1"></i>
                      Upload Statistics
                    </h3>

                    <div class="d-flex align-items-center float-right">
                      <select id="timeFilter" class="form-control form-control-sm mr-2">
                        <option value="day">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="custom">Custom Range</option>
                      </select>

                      <input type="date" id="startDate" class="form-control form-control-sm mr-1" style="display:none;">
                      <input type="date" id="endDate" class="form-control form-control-sm mr-2" style="display:none;">

                      <select id="chartType" class="form-control form-control-sm">
                        <option value="bar">Bar</option>
                        <option value="line">Line</option>
                        <option value="pie">Pie</option>
                      </select>
                    </div>

                  </div>
                  <div class="card-body">
                    <div class="card mt-4">
                      <div class="card-body">
                        <canvas id="uploadChart" height="120"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

              @endif




            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

              @if($user->role_id == 1 || $user->role_id == 2)
                <div class="card bg-gradient-primary">
                  <div class="card-header border-0">
                    <h3 class="card-title">
                      <i class="fas fa-map-marker-alt mr-1"></i>
                      District View
                    </h3>
                    <!-- card tools -->
                    <div class="card-tools">
                      <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse"
                        data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                    <!-- /.card-tools -->
                  </div>
                  <div class="card-body">
                    <div id="odishaMap" style="height: 300px; width:100%;"></div>


                  </div>
                </div>


              @endif
              <!-- Calendar -->
              <div class="card bg-gradient-success">
                <div class="card-header border-0">

                  <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendar
                  </h3>
                  <!-- tools card -->
                  <div class="card-tools">
                    <!-- button with a dropdown -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                        data-offset="-52">
                        <i class="fas fa-bars"></i></button>
                      <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item">Add new event</a>
                        <a href="#" class="dropdown-item">Clear events</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">View calendar</a>
                      </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <!-- /. tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body pt-0">
                  <!--The calendar -->
                  <div id="calendar" style="width: 100%"></div>
                </div>
                <!-- /.card-body -->
              </div>






              <!-- Map card -->
              <div class="card bg-gradient-primary" style="display:none">
                <div class="card-header border-0">
                  <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    District View
                  </h3>
                  <!-- card tools -->
                  <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm daterange" data-toggle="tooltip"
                      title="Date range">
                      <i class="far fa-calendar-alt"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse"
                      data-toggle="tooltip" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <div class="card-body">
                  <div id="world-map" style="height: 250px; width: 100%;"></div>
                </div>
                <!-- /.card-body-->
                <div class="card-footer bg-transparent">
                  <div class="row">
                    <div class="col-4 text-center">
                      <div id="sparkline-1"></div>
                      <div class="text-white">Visitors</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-4 text-center">
                      <div id="sparkline-2"></div>
                      <div class="text-white">Online</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-4 text-center">
                      <div id="sparkline-3"></div>
                      <div class="text-white">Sales</div>
                    </div>
                    <!-- ./col -->
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.card -->



              <!-- /.card -->
            </section>
            <!-- right col -->
          </div>
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('components.footer')
  </div>

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    const zoneLabels = @json($zoneWise->pluck('scm_zone_id'));
    const zoneData = @json($zoneWise->pluck('completed'));

    const districtLabels = @json($districtWise->pluck('scm_dist_id'));
    const districtData = @json($districtWise->pluck('completed'));

    new Chart(document.getElementById('zoneChart'), {
      type: 'bar',
      data: {
        labels: zoneLabels,
        datasets: [{
          label: 'Completed Trainings',
          data: zoneData,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });

    new Chart(document.getElementById('districtChart'), {
      type: 'bar',
      data: {
        labels: districtLabels,
        datasets: [{
          label: 'Completed Trainings',
          data: districtData,
          backgroundColor: 'rgba(255, 99, 132, 0.6)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
  <script>
    var map = L.map('odishaMap').setView([20.3, 84.7], 6);

    // Base map layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      minZoom: 6,
      maxZoom: 8
    }).addTo(map);

    // ✅ Static progress values for each Odisha district
    var progressData = {
      "Angul": 55,
      "Balangir": 62,
      "Balasore": 70,
      "Bargarh": 45,
      "Bhadrak": 80,
      "Boudh": 50,
      "Cuttack": 65,
      "Deogarh": 40,
      "Dhenkanal": 58,
      "Gajapati": 60,
      "Ganjam": 75,
      "Jagatsinghpur": 68,
      "Jajpur": 72,
      "Jharsuguda": 48,
      "Kalahandi": 52,
      "Kandhamal": 47,
      "Kendrapara": 66,
      "Kendujhar": 53,
      "Khordha": 90,
      "Koraput": 30,
      "Malkangiri": 35,
      "Mayurbhanj": 63,
      "Nabarangapur": 42,
      "Nayagarh": 56,
      "Nuapada": 39,
      "Puri": 82,
      "Rayagada": 40,
      "Sambalpur": 67,
      "Subarnapur": 44,
      "Sundargarh": 61
    };
    var colors = [
      "#1a9641", "#e6194B", "#3cb44b", "#ffe119", "#4363d8",
      "#f58231", "#911eb4", "#46f0f0", "#f032e6", "#bcf60c",
      "#fabebe", "#008080", "#e6beff", "#9a6324", "#fffac8",
      "#800000", "#aaffc3", "#808000", "#ffd8b1", "#000075",
      "#808080", "#a9a9a9", "#ff4500", "#6a5acd", "#20b2aa",
      "#dc143c", "#228b22", "#00ced1", "#daa520", "#ba55d3",
      "#2e8b57"
    ];
    // ✅ Function to pick random color
    function getRandomColor() {
      return colors[Math.floor(Math.random() * colors.length)];
    }

    // Load Odisha GeoJSON
    fetch("{{ asset('geojson/odisha.geojson') }}")
      .then(res => res.json())
      .then(data => {
        var geoLayer = L.geoJSON(data, {
          style: function (feature) {
            let district = feature.properties.district; // ⚠️ check this matches your geojson property
            let value = progressData[district] || 0;

            // Dynamic color scale
            let fillColor = getRandomColor();


            return {
              fillColor: fillColor,
              weight: 1,
              color: "#333",
              fillOpacity: 0.7
            };
          },
          onEachFeature: function (feature, layer) {
            let district = feature.properties.district;
            let value = progressData[district] || "N/A";

            layer.bindPopup(`<b>${district}</b><br>Progress: ${value}%`);
          }
        }).addTo(map);

        // Auto zoom to Odisha boundaries
        map.fitBounds(geoLayer.getBounds());
      });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    let chart;

    async function fetchChartData(filter = 'day', start = null, end = null) {
      const url = new URL("{{ route('chart.data') }}");
      url.searchParams.append('filter', filter);
      if (filter === 'custom' && start && end) {
        url.searchParams.append('start', start);
        url.searchParams.append('end', end);
      }

      const response = await fetch(url);
      return await response.json();
    }

    async function renderChart(filter = 'day', chartType = 'bar', start = null, end = null) {
      const data = await fetchChartData(filter, start, end);
      const ctx = document.getElementById('uploadChart').getContext('2d');

      if (chart) chart.destroy();

      chart = new Chart(ctx, {
        type: chartType,
        data: {
          labels: data.labels,
          datasets: data.datasets
        },
        options: {
          responsive: true,
          plugins: {
            tooltip: {
              callbacks: {
                title: (items) => `📅 ${items[0].label}`,
                label: (context) => `${context.dataset.label}: ${context.formattedValue} uploads`
              }
            },
            legend: {
              position: 'bottom'
            },
            title: {
              display: true,
              text: `Uploads (${filter})`
            }
          },
          scales: chartType !== 'pie' ? {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
          } : {}
        }
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const filterSelect = document.getElementById('timeFilter');
      const chartTypeSelect = document.getElementById('chartType');
      const startDate = document.getElementById('startDate');
      const endDate = document.getElementById('endDate');

      renderChart();

      filterSelect.addEventListener('change', () => {
        const filter = filterSelect.value;
        if (filter === 'custom') {
          startDate.style.display = 'inline-block';
          endDate.style.display = 'inline-block';
        } else {
          startDate.style.display = 'none';
          endDate.style.display = 'none';
          renderChart(filter, chartTypeSelect.value);
        }
      });

      chartTypeSelect.addEventListener('change', () => {
        renderChart(filterSelect.value, chartTypeSelect.value);
      });

      endDate.addEventListener('change', () => {
        const start = startDate.value;
        const end = endDate.value;
        if (start && end) renderChart('custom', chartTypeSelect.value, start, end);
      });
    });
  </script>
</body>

</html>