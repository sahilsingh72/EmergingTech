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
                                                        <h4 class="fw-bold mb-1">{{ $school->scm_name }}</h4>

                                                        <div class="student-badge">
                                                            <i class="fas fa-users"></i> {{ $school->students_count }}
                                                            Students
                                                        </div>
                                                    </div>

                                                    <!-- Bottom White Section -->
                                                    <div class="school-body p-3">

                                                        <div class="d-flex justify-content-between py-2">
                                                            <span>UDISE Code</span>
                                                            <span class="fw-bold text-primary">{{ $school->scm_udise_code }}</span>
                                                        </div>

                                                        <div class="d-flex justify-content-between py-2 border-bottom">
                                                            <span>Total Enrollment</span>
                                                            <span
                                                                class="fw-bold text-primary">{{ $school->students_count }}</span>
                                                        </div>


                                                        <a href="/school-{{ $school->scm_id }}-students"
                                                            class="btn btn-primary w-100 mt-3 fw-semibold">
                                                            View Students
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>

                                        @empty
                                            <p class="text-center">No schools found.</p>
                                        @endforelse

                                    </div>
                                    <div id="schoolList"
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"></div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

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


</body>
@include('components.footer')