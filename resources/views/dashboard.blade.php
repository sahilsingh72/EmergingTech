<head>
  <title>EmergingTech | Dashboard</title>
  <style>
    #gallery img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border: 1px solid #3a3030;
      border-radius: 2px;
      opacity: 0;
      transform: scale(1.05);
      transition: opacity 1s ease-in-out, transform 1s ease-in-out;
    }

    #gallery img.show {
      opacity: 1;
      transform: scale(1);
    }

    .aspect-square {
      aspect-ratio: 1 / 1;
      overflow: hidden;
      position: relative;
    }

    #gallery {
      transition: all 0.5s ease-in-out;
      border-collapse: collapse;
      line-height: 0;
      gap: 0;
      width: 100%;
      height: 33%;
    }

    #gallery>div {
      padding: 0;
      margin: 0;
    }

    /* img {
      display: block;
      width: 100%;
      height: 100%;
    } */

    .slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      transform: scale(1.1);
      transition: opacity 1.5s ease, transform 1.5s ease;
    }

    .slide.show {
      opacity: 1;
      transform: scale(1);
    }

    /* Random animation directions */
    .from-top {
      transform: translateY(-100%);
    }

    .from-bottom {
      transform: translateY(100%);
    }

    .from-left {
      transform: translateX(-100%);
    }

    .from-right {
      transform: translateX(100%);
    }

    @media (max-width: 768px) {
      #gallery {
        width: 60%;
        margin-left: calc(-50vw + 50%);
      }
    }

    .modal-backdrop {
      z-index: 1040 !important;
    }

    .modal {
      z-index: 1050 !important;
    }

    #floatingCamera::after {
      content: "Upload Training Camp Photo";
      position: absolute;
      bottom: 80px;
      right: 0;
      background: #007bff;
      color: #000000;
      font-size: 16px;
      padding: 6px 10px;
      border-radius: 6px;
      white-space: nowrap;
      opacity: 0;
      transform: translateY(10px);
      pointer-events: none;
      transition: all 0.25s ease;
    }

    #floatingCamera:hover::after {
      opacity: 1;
      transform: translateY(0);
    }

    /* Optional small arrow */
    #floatingCamera::before {
      content: "";
      position: absolute;
      bottom: 70px;
      right: 25px;
      border-width: 6px;
      border-style: solid;
      border-color: #007bff transparent transparent transparent;
      opacity: 0;
      transition: opacity 0.25s ease;
    }

    #floatingCamera:hover::before {
      opacity: 1;
    }
  </style>
  <script src="https://cdn.tailwindcss.com"></script>
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

                <a @php
                  $roleId = Auth::user()->role_id;
                @endphp @if($roleId == 1 || $roleId == 2 || $roleId == 8)
                  href="{{ route('select.district') }}" @else href="#" @endif class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i>
                </a>
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

                <a @php
                  $roleId = Auth::user()->role_id;
                @endphp @if($roleId == 1 || $roleId == 2 || $roleId == 8)
                href="{{ route('student.school') }}" @else href="{{ route('studentlist') }}" @endif
                  class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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

          </div>
          <!-- /.row -->
          <button id="floatingCamera"
            class="btn btn-primary rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center"
            style="bottom: 20px; right: 20px; width: 65px; height: 65px; z-index: 1050;" data-toggle="tooltip"
            data-placement="left" {{-- title="Upload Training Camp Photo" --}}>
            <i class="fas fa-camera fa-lg text-white"></i>
          </button>
          <!-- Upload Modal -->
          <div class="modal fade" id="photoUploadModal" tabindex="-1" aria-labelledby="photoUploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="photoUploadModalLabel">
                    <i class="fas fa-upload me-2"></i>Upload Training Photo
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <form id="photoUploadForm" enctype="multipart/form-data">
                    @csrf
                    <!-- 📸 Image Preview -->
                    <div class="text-center mb-3">
                      <img id="photoPreview" src="https://cdn-icons-png.flaticon.com/128/15691/15691622.png"
                        alt="Preview" class="rounded shadow-sm border"
                        style="width: 150px; height: 150px; object-fit: cover; margin:auto">
                    </div>
                    <div class="mb-3">
                      <label for="schoolSelect" class="form-label fw-semibold">Select School</label>
                      <select class="form-control" id="schoolSelect" name="school_id" required>
                        <option value="">-- Select a School --</option>
                        @foreach ($schools as $school)
                          <option value="{{ $school->scm_id }}" data-name="{{ $school->scm_name }}">
                            {{ $school->scm_name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="photoInput" class="form-label fw-semibold">Choose or Capture Photo</label>
                      <input class="form-control" type="file" id="photoInput" name="file" accept="image/*"
                        capture="environment">
                    </div>

                    <div class="progress mb-3 d-none" id="uploadProgressContainer">
                      <div id="uploadProgress"
                        class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                        style="width: 0%"></div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Upload
                      </button>
                      <button type="button" data-dismiss="modal"
                        class="btn btn-secondary px-4 py-2 border rounded-lg ">Cancel</button>
                    </div>
                  </form>

                  <div class="text-center mt-3" id="uploadResult" style="display: none;">
                    <i class="fas fa-check-circle text-success fa-2x"></i>
                    <p class="fw-semibold mt-2">Photo uploaded successfully!</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
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



              @if($user->role_id == 1 || $user->role_id == 2 || $user->role_id == 8)
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
                    <div id="odishaMap" style="height: 500px; width:100%;"></div>
                  </div>
                </div>
              @endif

              {{-- @if($user->role_id == 1 || $user->role_id == 2)
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
              @endif --}}




            </section>
            <!-- /.Left col -->

            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

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

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-image mr-1"></i>
                    Training Gallery
                  </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content p-0">
                    <div class="relative flex items-center justify-center py-2">
                      <div id="gallery" class="w-full max-w-3xl mx-auto grid grid-cols-3">
                        <div class="relative aspect-square overflow-hidden"></div>
                        <div class="relative aspect-square overflow-hidden"></div>
                        <div class="relative aspect-square overflow-hidden"></div>
                        <div class="relative aspect-square overflow-hidden"></div>
                        <div class="relative aspect-square overflow-hidden"></div>
                        <div class="relative aspect-square overflow-hidden"></div>
                      </div>
                    </div>

                  </div>
                </div>
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
    document.addEventListener("DOMContentLoaded", async function () {
      const gallery = document.getElementById("gallery");
      const slots = gallery.querySelectorAll(".aspect-square");
      let allImages = [];
      let usedImages = new Set();

      // Fetch latest 30 images from your DB
      async function fetchImages() {
        try {
          const response = await fetch("{{ route('fetch.gallery') }}");
          const data = await response.json();
          if (data.success && data.images.length > 0) {
            allImages = data.images;
          } else {
            gallery.innerHTML = "<p class='text-gray-500 text-center col-span-full py-4'>No images found.</p>";
          }
        } catch (err) {
          console.error("Error fetching images:", err);
        }
      }

      // Get random unused image
      function getRandomImage() {
        if (usedImages.size >= allImages.length) usedImages.clear();
        let img;
        do {
          img = allImages[Math.floor(Math.random() * allImages.length)];
        } while (usedImages.has(img));
        usedImages.add(img);
        return img;
      }

      // Replace image in a slot
      function changeImage(slot) {
        if (!allImages.length) return;
        const imgData = getRandomImage();
        const newImg = document.createElement("img");
        newImg.className = "slide";
        newImg.src = "{{ route('preview.image') }}?path=" + encodeURIComponent(imgData.onedrive_path);
        // newImg.alt = imgData.file_name;

        slot.appendChild(newImg);
        setTimeout(() => newImg.classList.add("show"), 100); // fade in

        // Remove old image smoothly
        const oldImg = slot.querySelector(".slide.show");
        if (oldImg) {
          oldImg.classList.remove("show");
          setTimeout(() => oldImg.remove(), 1500);
        }
      }

      // Initialize gallery
      async function initGallery() {
        await fetchImages();

        if (!allImages.length) return;

        // Fill initial 6 images
        slots.forEach((slot, index) => {
          const imgData = getRandomImage();
          const img = document.createElement("img");
          img.src = "{{ route('preview.image') }}?path=" + encodeURIComponent(imgData.onedrive_path);
          // img.alt = imgData.file_name;
          img.className = "slide show";
          slot.appendChild(img);
        });

        // Change one image at random every 3 seconds
        setInterval(() => {
          const randomSlot = slots[Math.floor(Math.random() * slots.length)];
          changeImage(randomSlot);
        }, 3000);
      }

      initGallery();
    });
  </script>

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
    var districtIdMapping = @json(
      \App\Models\District::pluck('DSM_DSCD', 'DSM_DSNM')
    );

    // Map GeoJSON names to DB names
    const geoToDbDistrictMapping = {
      "Kendujhar": "Keonjhar",
      "Subarnapur": "Sonepur",
      "Nabarangapur": "Nabarangpur",
    };
    var map = L.map('odishaMap', {
      zoomControl: false,
      attributionControl: false,
      // dragging: false,          // disable drag
      // scrollWheelZoom: false,   // disable scroll zoom
      doubleClickZoom: false,   // disable double-click zoom
      // boxZoom: false,           // disable box zoom
      // keyboard: false,          // disable keyboard navigation
      // tap: false,               // disable touch on mobile
      // touchZoom: false           // disable pinch zoom
    }).setView([20.3, 84.7], 6);

    map.scrollWheelZoom.disable();

    // Base map layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      minZoom: 7,
      maxZoom: 8,
      opacity: 0.3,
    }).addTo(map);

    //  Each district with fixed distinct color
    const districtColors = {
      "Angul": "#ff7f50", "Balangir": "#6a5acd", "Balasore": "#20b2aa", "Bargarh": "#ff6347",
      "Bhadrak": "#9370db", "Boudh": "#3cb371", "Cuttack": "#ffa500", "Deogarh": "#4682b4",
      "Dhenkanal": "#8fbc8f", "Gajapati": "#ff69b4", "Ganjam": "#cd5c5c", "Jagatsinghpur": "#40e0d0",
      "Jajpur": "#9acd32", "Jharsuguda": "#ba55d3", "Kalahandi": "#7b68ee", "Kandhamal": "#d2691e",
      "Kendrapara": "#6495ed", "Kendujhar": "#daa520", "Khordha": "#00bfff", "Koraput": "#ff1493",
      "Malkangiri": "#adff2f", "Mayurbhanj": "#ff4500", "Nabarangapur": "#1e90ff", "Nayagarh": "#ffb6c1",
      "Nuapada": "#a0522d", "Puri": "#00fa9a", "Rayagada": "#ff8c00", "Sambalpur": "#4682b4",
      "Subarnapur": "#dda0dd", "Sundargarh": "#228b22"
    };

    //  backend-provided district progress (for popup only)
    fetch("{{ route('district.progress') }}")
      .then(res => res.json())
      .then(progressData => {

        // 🗺️ Load Odisha GeoJSON
        fetch("{{ asset('geojson/odisha.geojson') }}")
          .then(res => res.json())
          .then(data => {
            var geoLayer = L.geoJSON(data, {
              style: function (feature) {
                let district = feature.properties.district;
                let fillColor = districtColors[district] || "#cccccc";
                return {
                  fillColor: fillColor,
                  color: "#333",
                  weight: 1,
                  fillOpacity: 0.75
                };
              },

              onEachFeature: function (feature, layer) {
                let district = feature.properties.district;
                let completed = progressData.completed[district] ?? 0;
                let totalSchools = progressData.total_schools[district] ?? 0;
                let students = progressData.students[district] ?? 0;

                // Permanent label (district name)
                layer.bindTooltip(
                  district,
                  {
                    permanent: true,
                    direction: "center",
                    className: "district-label",
                  }
                );

                layer.bindPopup(`
                <div style="min-width:140px">
                  <b>${district}</b><br>
                  Progress: ${completed} / ${totalSchools}<br>
                  Total Students:${students}<br>
                  <small style="color:#326ee6">Click to open district</small>
                </div>
              `, {
                  closeButton: false,
                  offset: L.point(0, 0),
                  autoPan: false
                });

                layer.on('mouseover', function (e) {
                  layer.openPopup(e.latlng);
                  // optionally highlight
                  layer.setStyle && layer.setStyle({ weight: 2, color: "#000" });
                });
                layer.on('mouseout', function () {
                  layer.closePopup();
                  // reset style (if you changed it)
                  geoLayer.resetStyle && geoLayer.resetStyle(layer);
                });

                // CLICK EVENT → REDIRECT TO DISTRICT PAGE
                layer.on('click', function () {

                  let dbDistrictName = geoToDbDistrictMapping[district] ?? district;

                  let distId = districtIdMapping[dbDistrictName];

                  if (!distId) {
                    alert("District not found in DB: " + district);
                    return;
                  }

                  // Redirect using your existing route
                  window.location.href = `/district-${distId}-list`;
                });
              }
            }).addTo(map);

            map.fitBounds(geoLayer.getBounds());
          });
      });
  </script>

  <style>
    .district-label {
      background: rgba(255, 255, 255, 0.7);
      border: none;
      border-radius: 4px;
      padding: 2px 6px;
      font-size: 11px;
      color: #000;
      font-weight: 600;
      text-shadow: 1px 1px 2px #fff;
    }

    .hover-tooltip {
      /* not used now but okay to keep */
      background: rgba(0, 0, 0, 0.85);
      color: #fff !important;
      padding: 6px 10px;
      border-radius: 5px;
      font-weight: 600;
    }
  </style>

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

  //camera button
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>

  <script>
    const button = document.getElementById('floatingCamera');
    let isDragging = false;
    let offsetX, offsetY;
    let dragMoved = false;

    button.addEventListener('mousedown', startDrag);
    button.addEventListener('touchstart', startDrag);

    function startDrag(e) {
      isDragging = true;
      dragMoved = false; // reset movement flag
      const rect = button.getBoundingClientRect();
      offsetX = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
      offsetY = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top;

      document.addEventListener('mousemove', drag);
      document.addEventListener('touchmove', drag);
      document.addEventListener('mouseup', stopDrag);
      document.addEventListener('touchend', stopDrag);
    }

    function drag(e) {
      if (!isDragging) return;
      e.preventDefault();

      dragMoved = true; // mark that user actually moved

      const x = e.touches ? e.touches[0].clientX - offsetX : e.clientX - offsetX;
      const y = e.touches ? e.touches[0].clientY - offsetY : e.clientY - offsetY;

      const maxX = window.innerWidth - button.offsetWidth - 10;
      const maxY = window.innerHeight - button.offsetHeight - 10;
      const clampedX = Math.min(Math.max(10, x), maxX);
      const clampedY = Math.min(Math.max(10, y), maxY);

      button.style.left = clampedX + 'px';
      button.style.top = clampedY + 'px';
      button.style.right = 'auto';
      button.style.bottom = 'auto';
      button.style.transition = 'none';
    }

    function stopDrag(e) {
      if (!isDragging) return;
      isDragging = false;

      document.removeEventListener('mousemove', drag);
      document.removeEventListener('touchmove', drag);
      document.removeEventListener('mouseup', stopDrag);
      document.removeEventListener('touchend', stopDrag);

      if (!dragMoved) {
        $('#photoUploadModal').modal('show');
      }
    }

    /* ---------- 🖼️ Live Image Preview ---------- */
    document.getElementById('photoInput').addEventListener('change', function (event) {
      const file = event.target.files[0];
      const preview = document.getElementById('photoPreview');

      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        // Reset to placeholder if no file selected
        preview.src = "https://cdn-icons-png.flaticon.com/128/15691/15691622.png";
      }
    });


    document.getElementById('photoUploadForm').addEventListener('submit', async function (e) {
      e.preventDefault();

      const form = e.target;
      const fileInput = form.querySelector('#photoInput');
      const progressContainer = document.getElementById('uploadProgressContainer');
      const progressBar = document.getElementById('uploadProgress');
      const resultDiv = document.getElementById('uploadResult');
      const preview = document.getElementById('photoPreview');

      const school = document.getElementById('schoolSelect').value;
      if (!school) {
        e.preventDefault();
        alert('Please select a school before uploading.');
      }

      if (!fileInput.files.length) {
        alert('Please select a photo.');
        return;
      }

      const formData = new FormData(form);
      progressContainer.classList.remove('d-none');
      resultDiv.style.display = 'none';
      progressBar.style.width = '0%';

      try {
        const response = await fetch('{{ route("uploadgallery") }}', {
          method: 'POST',
          body: formData,
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        const result = await response.json();

        if (result.success) {
          progressBar.style.width = '100%';
          resultDiv.style.display = 'block';
          fileInput.value = '';
          preview.src = "https://cdn-icons-png.flaticon.com/128/15691/15691622.png";
        } else {
          alert(result.message || 'Upload failed.');
        }
      } catch (error) {
        alert('An error occurred: ' + error.message);
      }
    });
  </script>
  <script>
    document.getElementById('cancelEditModal').addEventListener('click', function () {
      $('#photoUploadModal').modal('hide'); // jQuery version
    });
  </script>
</body>

</html>