@include('components.navbar')
@include('components.sidebar')
<style>
    .school-card {
        border-radius: 14px;
        background: #e3effa;
        transition: all 0.25s ease-in-out;
    }

    .school-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
    }

    .school-card {
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        transition: 0.3s;
    }

    .school-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Top Header */
    .school-header {
        background: #153058;
        /* Blue */
    }

    /* Student Count Badge */
    .student-badge {
        background: #e6f0ff;
        color: #153058;
        padding: 6px 14px;
        border-radius: 25px;
        display: inline-block;
        font-weight: 600;
        margin-top: 8px;
    }

    /* White Section */
    .school-body {
        background: #e3effa;
    }
    a.btn {
           
            background: #153058;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">

    {{--
    <script src="https://cdn.tailwindcss.com"></script> --}}
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">District - School</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('student.school') }}">Districts</a></li>
                                <li class="breadcrumb-item active">{{ $district->DSM_DSNM }}</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="py-1">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-0 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    {{-- <h2 class="text-2xl font-semibold text-center mb-6"></i>Coordinator List</h2>
                                    --}}
                                    <h1 class="text-3xl font-bold text-center mb-3">
                                        Schools in {{ $district->DSM_DSNM }}
                                    </h1>

                                    <div class="row">

                                        @forelse($schools as $school)
                                            <div class="col-md-4 mb-4">
                                                <div class="school-card shadow-sm border-0">

                                                    <!-- Top Header (Blue Section) -->
                                                    <div class="school-header text-center text-white py-3">
                                                        <h4 class="fw-bold mb-1">{{ $school->scm_name }}</h4>
                                                    </div>

                                                    <!-- Bottom White Section -->
                                                    <div class="school-body p-3">

                                                        <div class="d-flex justify-content-between py-2">
                                                            <span>UDISE Code</span>
                                                            <span class="fw-bold text-primary">{{ $school->scm_udise_code }}</span>
                                                        </div>

                                                        <div class="d-flex justify-content-between py-2 border-bottom">
                                                            <span>Training Status</span>
                                                            @if($school->training_completed == 1)
                                                                <span class="fw-bold" style="color: green;">● Completed</span>
                                                            @else
                                                                <span class="fw-bold" style="color: red;">● Not Completed</span>
                                                            @endif
                                                        </div>

                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showCoordinators({{ $school->scm_id }})">
                                                            <span>Coordinators</span>
                                                            <span class="fw-bold text-primary">{{ $school->coordinators_count }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showTrainers({{ $school->scm_id }})">
                                                            <span>Trainers</span>
                                                            <span class="fw-bold text-primary">{{ $school->trainers_count }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showStaffs({{ $school->scm_id }})">
                                                            <span>Supporting Staff</span>
                                                            <span class="fw-bold text-primary">{{ $school->staffs_count }}</span>
                                                        </div>
                                                        <a href="/school-{{ $school->scm_id }}-students">
                                                            <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105">
                                                                <span>Total Students</span>
                                                                <span
                                                                    class="fw-bold text-primary">{{ $school->students_count }}</span>
                                                            </div>
                                                        </a>

                                                        {{-- <a href="/school-{{ $school->scm_id }}-students"
                                                            class="btn btn-primary w-100 mt-3 fw-semibold">
                                                            View Students
                                                        </a> --}}
                                                    </div>

                                                </div>
                                            </div>

                                        @empty
                                            <p class="text-center">No schools found.</p>
                                        @endforelse

                                    </div>
                                    <div id="schoolList"
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"></div>


                                        <!-- coordinator Modal -->
<div class="modal fade" id="coordinatorModal" tabindex="-1" aria-labelledby="schoolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title w-100 text-center" id="schoolModalLabel">COORDINATOR OF {{ $school->scm_name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="modalContentCo" class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center">Loading...</div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                                        <!-- trainer Modal -->
<div class="modal fade" id="trainerModal" tabindex="-1" aria-labelledby="schoolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title w-100 text-center" id="schoolModalLabel">TRAINER OF {{ $school->scm_name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="modalContentTr" class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center">Loading...</div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                                        <!-- staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="schoolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title w-100 text-center" id="schoolModalLabel">SUPPORTING STAFF OF {{ $school->scm_name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="modalContentSt" class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center">Loading...</div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                                        

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadSchools(distId) {
            $.ajax({
                url: "/district-" + distId + "-schools",
                type: "GET",
                success: function (schools) {
                    let html = "";

                    schools.forEach(school => {
                        html += `
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <h3 class="text-lg font-bold mb-2">${school.scm_name}</h3>

                    <ul class="list-disc pl-5 mb-3">
                        <li>Total Students: <b>${school.student_count}</b></li>
                    </ul>

                    <a href="/school-${school.scm_id}-students" 
                       class="inline-block bg-blue-600 text-white px-3 py-2 rounded">
                       View Details
                    </a>
                </div>
                `;
                    });

                    document.getElementById("schoolList").innerHTML = html;
                }
            });
        }
    </script>

{{-- <script>
function showCoordinators(schoolId) {
    // Show modal
    var myModal = new bootstrap.Modal(document.getElementById('coordinatorModal'));
    myModal.show();

    // Set loading text
    document.getElementById('modalContent').innerHTML = '<div class="text-center">Loading...</div>';

    // Fetch coordinators via AJAX
    fetch(`/school/${schoolId}/coordinators-json`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                document.getElementById('modalContent').innerHTML = '<p>No coordinators found.</p>';
                return;
            }

            let html = `<table class="table table-bordered table-striped overflow-x-auto">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>`;
            data.forEach(c => {
                html += `<tr>
                            <td>${c.photo ? `<img src="/storage/${c.photo}" alt="photo" width="50" height="50" class="rounded-circle">` : '-'}</td>
                            <td>${c.coordinator_name}</td>
                            <td>${c.phone || '-'}</td>
                            <td>${c.email || '-'}</td>
                         </tr>`;
            });
            html += '</tbody></table>';

            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('modalContent').innerHTML = '<p class="text-danger">Failed to load data.</p>';
            console.error(err);
        });
}
</script> --}}
<script>
function loadModalData(url, modalId, contentId) {
    var modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();

    document.getElementById(contentId).innerHTML = '<div class="text-center py-4">Loading...</div>';

    fetch(url)
        .then(response => response.json())
        .then(data => {

            if (data.length === 0) {
                document.getElementById(contentId).innerHTML = '<p class="text-center">No records found.</p>';
                return;
            }

            let html = `
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(item => {
                html += `
                    <tr>
                        <td>${item.photo ? `<img src="/storage/${item.photo}" width="50" height="50" class="rounded-circle">` : '-'}</td>
                        <td>${item.name ?? item.coordinator_name ?? item.trainer_name ?? item.staff_name}</td>
                        <td>${item.phone || '-'}</td>
                        <td>${item.email || '-'}</td>
                    </tr>
                `;
            });

            html += "</tbody></table>";

            document.getElementById(contentId).innerHTML = html;
        })
        .catch(error => {
            document.getElementById(contentId).innerHTML = '<p class="text-danger text-center">Failed to load data.</p>';
            console.error(error);
        });
}

/* Individual Functions */
function showCoordinators(schoolId) {
    loadModalData(`/school/${schoolId}/coordinators-json`, "coordinatorModal", "modalContentCo");
}

function showTrainers(schoolId) {
    loadModalData(`/school/${schoolId}/trainers-json`, "trainerModal", "modalContentTr");
}

function showStaffs(schoolId) {
    loadModalData(`/school/${schoolId}/staffs-json`, "staffModal", "modalContentSt");
}
</script>

</body>
@include('components.footer')