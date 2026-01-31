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
                        <div class="max-w-8xl mx-auto  space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
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
                                                        <h4 class="fw-bold mb-1">{{ $school->scm_name }}
                                                            ({{ $school->scm_udise_code }})</h4>
                                                    </div>

                                                    <!-- Bottom White Section -->
                                                    <div class="school-body p-3">

                                                        <div class="d-flex justify-content-between py-2 border-bottom">
                                                            <span>Training Date</span>
                                                            <span class="fw-bold"
                                                                style="color: green;">{{ \Carbon\Carbon::parse($school->training_date)->format('d-m-Y') ?? 'N/A'}}</span>
                                                        </div>

                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showSchoolDetails({{ $school->scm_id }})">
                                                            <span>School Details</span>
                                                            <span class="fw-bold text-primary">click here</span>
                                                        </div>

                                                        <a href="{{ route('school.data.show', $school->scm_id) }}"
                                                            class="d-flex justify-content-between py-2 border-bottom cursor-pointer text-decoration-none
                                                                    transform transition duration-200 hover:text-blue-700 hover:scale-105">
                                                            <span>School Data Uploads</span>
                                                            <span class="fw-bold text-primary">click here</span>
                                                        </a>

                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showCoordinators({{ $school->scm_id }})">
                                                            <span>Coordinators</span>
                                                            <span
                                                                class="fw-bold text-primary">{{ $school->coordinators_count }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showTrainers({{ $school->scm_id }})">
                                                            <span>Trainers</span>
                                                            <span
                                                                class="fw-bold text-primary">{{ $school->trainers_count }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105"
                                                            onclick="showStaffs({{ $school->scm_id }})">
                                                            <span>Supporting Staff</span>
                                                            <span
                                                                class="fw-bold text-primary">{{ $school->staffs_count }}</span>
                                                        </div>
                                                        @if (auth()->user()->role->id != '1')
                                                            <a href="/school-{{ $school->scm_id }}-students">
                                                                <div
                                                                    class="d-flex justify-content-between py-2 border-bottom cursor-pointer transform transition duration-200 hover:text-blue-700 hover:scale-105">
                                                                    <span>Total Students</span>
                                                                    <span
                                                                        class="fw-bold text-primary">{{ $school->students_count }}</span>
                                                                </div>
                                                            </a>
                                                        @endif

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