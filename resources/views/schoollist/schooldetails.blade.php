@include('components.navbar')
@include('components.sidebar')
<style>
    .school-wrapper {
        background: #f4f6f9;
        min-height: 100vh;
        padding: 30px;
    }

    .school-header-main {
        background: linear-gradient(135deg, #153058, #1f3c72);
        color: #fff;
        border-radius: 14px;
        padding: 30px;
        margin-bottom: 25px;
    }

    .school-header-main h2 {
        margin: 0;
        font-weight: 700;
    }

    .info-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: 0.25s;
        cursor: pointer;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
    }

    .info-title {
        font-size: 14px;
        color: #6c757d;
    }

    .info-value {
        font-size: 18px;
        font-weight: 700;
        margin-top: 6px;
    }

    .action-row {
        margin-top: 30px;
    }

    .action-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        background: #ffffff;
        border-radius: 12px;
        margin-bottom: 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        transition: 0.2s;
        cursor: pointer;
    }

    .action-item:hover {
        background: #eef3ff;
        transform: scale(1.01);
    }

    .action-item span:last-child {
        font-weight: 600;
        color: #153058;
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
                            <h1 class="m-0 text-dark">{{ $school->scm_name }}</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">{{ $school->scm_name }}</a></li>
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
                        <div class="max-w-8xl mx-auto  space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <!-- Title -->
                                    {{-- <h2 class="text-2xl font-semibold text-center mb-6"></i>Coordinator List</h2>
                                    --}}
                                    <h1 class="text-3xl font-bold text-center mb-3">
                                        School Details
                                    </h1>

                                    <div class="">

                                        <!-- HEADER -->
                                        <div class="school-header-main">
                                            <h2>{{ $school->scm_name }}</h2>
                                            <p class="mb-0">
                                                UDISE: {{ $school->scm_udise_code }} |
                                                District: {{ $district->DSM_DSNM }}
                                            </p>
                                        </div>

                                        <!-- INFO SUMMARY -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-title">Training Status</div>
                                                    <div class="info-value">
                                                        @if($isTrainingCompleted)
                                                            <span class="text-success">● Completed</span>
                                                        @else
                                                            <span class="text-danger">● Not Completed</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-title">Training Date</div>
                                                    <div class="info-value text-primary">
                                                        {{ $school->training_date
    ? \Carbon\Carbon::parse($school->training_date)->format('d-m-Y')
    : 'N/A'
                                                        }}
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-3">
                                                <div class="info-card">
                                                    <div class="info-title">Total Students</div>
                                                    <div class="info-value">{{ $school->students_count }}</div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="info-card">
                                                    <div class="info-title">Total Trainers</div>
                                                    <div class="info-value">{{ $school->trainers_count }}</div>
                                                </div>
                                            </div> --}}
                                        </div>

                                        <!-- ACTIONS -->
                                        <div class="action-row">

                                            <div class="action-item" onclick="showSchoolDetails({{ $school->scm_id }})">
                                                <span>School Details</span>
                                                <span>View</span>
                                            </div>

                                            <a href="{{ route('school.data.show', $school->scm_id) }}"
                                                class="text-decoration-none">
                                                <div class="action-item">
                                                    <span>School Data Uploads</span>
                                                    <span>View</span>
                                                </div>
                                            </a>

                                            <div class="action-item" onclick="showCoordinators({{ $school->scm_id }})">
                                                <span>Coordinators</span>
                                                <span>{{ $school->coordinators_count }}</span>
                                            </div>

                                            <div class="action-item" onclick="showTrainers({{ $school->scm_id }})">
                                                <span>Trainers</span>
                                                <span>{{ $school->trainers_count }}</span>
                                            </div>

                                            <div class="action-item" onclick="showStaffs({{ $school->scm_id }})">
                                                <span>Supporting Staff</span>
                                                <span>{{ $school->staffs_count }}</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div id="schoolList"
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"></div>


                                    <!-- School Details Modal -->
                                    <div class="modal fade" id="schoolDetailsModal" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title w-100 text-center" id="schoolDetailsTitle">
                                                        School Details</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div id="modalContentSchool" class="p-3">
                                                        <div class="text-center">Loading...</div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary px-4"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- coordinator Modal -->
                                    <div class="modal fade" id="coordinatorModal" tabindex="-1"
                                        aria-labelledby="schoolModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title w-100 text-center" id="schoolModalLabel">
                                                        COORDINATOR</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="modalContentCo" class="table-responsive"
                                                        style="max-height: 400px; overflow-y: auto;">
                                                        <div class="text-center">Loading...</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary px-4"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- trainer Modal -->
                                    <div class="modal fade" id="trainerModal" tabindex="-1"
                                        aria-labelledby="schoolModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title w-100 text-center" id="schoolModalLabel">
                                                        TRAINER </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="modalContentTr" class="table-responsive"
                                                        style="max-height: 400px; overflow-y: auto;">
                                                        <div class="text-center">Loading...</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary px-4"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- staff Modal -->
                                    <div class="modal fade" id="staffModal" tabindex="-1"
                                        aria-labelledby="schoolModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title w-100 text-center" id="schoolModalLabel">
                                                        SUPPORTING STAFF</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="modalContentSt" class="table-responsive"
                                                        style="max-height: 400px; overflow-y: auto;">
                                                        <div class="text-center">Loading...</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary px-4"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="mb-3">
                                    <button onclick="goBackToCalendar()" class="btn btn-outline-dark">
                                        ← Back to Dashboard
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        function goBackToCalendar() {
            window.history.back();
        }
    </script>

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
    <script>
        const USER_ROLE_ID = {{ auth()->user()->role_id }};
    </script>

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

                    let isTrainer = (modalId === "trainerModal");
                    let isSupStaff = (modalId === "staffModal");

                    let html = `
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                    `;

                    if (isTrainer) {
                        html += `<th>Specialization</th>`;
                    }

                    @if(Auth::user()->role_id != 1)
                        html += `
                            <th>Phone</th>
                            <th>Email</th>
                        `;
                    @endif
                    html += `
                        <th>CV</th>
                    `;
                    if (isTrainer) {
                        html += `<th>Experience</th>`;
                    }
                    html += `
                            </tr>
                        </thead>
                        <tbody>
                    `;

                    data.forEach(item => {
                        html += `
                        <tr>
                            <td>${item.photo ? `<img src="/storage/${item.photo}" width="50" height="50" class="rounded-circle">` : '-'}</td>
                            <td>${item.name ?? item.coordinator_name ?? item.trainer_name ?? item.ss_name}</td>
                        `;

                        if (isTrainer) {
                            html += `<td>${item.specialization || '-'}</td>`;
                        }

                        @if(Auth::user()->role_id != 1)
                            html += `
                                <td>${item.phone || '-'}</td>
                                <td>${item.email || '-'}</td>
                            `;
                        @endif
                        html += `
                            <td>
                                ${item.cv
                                ? `<a href="/storage/${item.cv}" target="_blank" class="text-success">View</a>`
                                : '-'}
                            </td>
                        `;
                        if (isTrainer) {
                            html += `<td>
                                ${item.experience_certificate
                                    ? `<a href="/storage/${item.experience_certificate}" target="_blank" class="text-success">View</a>`
                                    : '-'}
                            </td>`;
                        }
                        html += `</tr>`;
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
        function showSchoolDetails(schoolId) {
            var modal = new bootstrap.Modal(document.getElementById("schoolDetailsModal"));
            modal.show();

            document.getElementById("modalContentSchool").innerHTML =
                '<div class="text-center p-3">Loading...</div>';

            fetch(`/school/${schoolId}/details-json`)
                .then(res => res.json())
                .then(data => {

                    let html = `
                        <table class="table table-bordered">
                            @php
                                $roleId = Auth::user()->role_id;
                            @endphp
                            @if($roleId == 2)
                                <!-- Event Date Row -->
                                <tr>
                                    <th>Date of Training</th>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="date" 
                                                id="training_date_${schoolId}" 
                                                class="form-control"
                                                style="max-width: 200px;"
                                                value="${data.training_date ?? ''}">

                                            <button class="btn btn-sm btn-primary mt-2"
                                                    onclick="saveTrainingDate(${schoolId})">
                                                Save
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr><th>School Name</th><td>${data.scm_name}</td></tr>
                            <tr><th>UDISE Code</th><td>${data.scm_udise_code}</td></tr>
                            <tr><th>Date of Training</th><td>${data.training_date ?? 'N/A'}</td></tr>
                            <tr><th>Address</th><td>${data.scm_address ?? 'N/A'}, ${data.scm_pin_code}</td></tr>
                            <tr><th>District</th><td>${data.scm_dist ?? 'N/A'}</td></tr>
                            <tr><th>HM Name</th><td>${data.scm_hm_name ?? 'N/A'}</td></tr>
                            <tr><th>HM Email</th><td><a href="mailto:${data.scm_hm_email}">${data.scm_hm_email ?? 'N/A'}</a></td></tr>
                            <tr><th>HM Phone</th><td><a href="tel:${data.scm_hm_phone}">${data.scm_hm_phone ?? 'N/A'}</a></td></tr>
                            <tr><th>HM Whatsapp</th><td>${data.scm_hm_wp ?? 'N/A'}</td></tr>
                            <tr><th>SPOC Name</th><td>${data.scm_spoc_name ?? 'N/A'}</td></tr>
                            <tr><th>SPOC Email</th><td><a href="mailto:${data.scm_spoc_email}">${data.scm_spoc_email ?? 'N/A'}</a></td></tr>
                            <tr><th>SPOC Phone</th><td><a href="tel:${data.scm_spoc_phone}">${data.scm_spoc_phone ?? 'N/A'}</a></td></tr>
                            <tr><th>SPOC Whatsapp</th><td>${data.scm_spoc_wp ?? 'N/A'}</td></tr>
                            <tr><th>No of Smart Classrooms</th><td>${data.scm_smartclass ?? 'N/A'}</td></tr>
                            <tr><th>Avalibility of three Classrooms</th><td>${data.scm_avail_3_class == 1 ? 'Yes' : 'No'}</td></tr>
                            <tr><th>Power Back-up in Classroom</th><td>${data.scm_powerbackup == 1 ? 'Yes' : 'No'} (${data.scm_powerbackup_type ?? 'N/A'})</td></tr>
                            <tr><th>Internet facility in Classrooms</th><td>${data.scm_internet == 1 ? 'Yes' : 'No'} (${data.scm_internet_type ?? 'N/A'})</td></tr>
                            @php
                                $roleId = Auth::user()->role_id;
                            @endphp
                            @if($roleId != 1)
                                <tr><th>Total Students</th><td>${data.students_count ?? 'N/A'}</td></tr>
                            @endif
                        </table>
                    `;

                    document.getElementById("modalContentSchool").innerHTML = html;
                })
                .catch(error => {
                    document.getElementById("modalContentSchool").innerHTML =
                        '<p class="text-danger text-center">Failed to load school details.</p>';
                });
        }
        //DATE of training
        function saveTrainingDate(schoolId) {
            const date = document.getElementById(`training_date_${schoolId}`).value;

            if (!date) {
                alert("Please select a training date.");
                return;
            }

            fetch(`/school/${schoolId}/save-training-date`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ training_date: date })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Training date saved successfully!');
                    } else {
                        alert('Saved failed.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error saving training date.');
                });
        }



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