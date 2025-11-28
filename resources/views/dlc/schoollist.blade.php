@include('components.navbar')
@include('components.sidebar')
<style>
    .school-card:hover {
        transform: translateY(-6px);
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">District School</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">{{ $district->DSM_DSNM }}</a></li>
                                <li class="breadcrumb-item active">District School</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-8xl mx-auto space-y-6">
                            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">School Details</h2>

                                    <div class="row">

                                        @foreach($schools as $school)
                                            <div class="col-md-4 mb-4">
                                                <div class="card shadow border-0 rounded">

                                                    <div class="card-header bg-[#153058] text-white text-center">
                                                        <h5 class="fw-bold mb-0">{{ $school->scm_name }}</h5>
                                                    </div>

                                                    <div class="card-body bg-[#e3effa]">
                                                        <p><b>UDISE:</b> {{ $school->scm_udise_code }}</p>
                                                        <p><b>School Address:</b> {{ $school->scm_address ?? 'N/A' }}</p>
                                                        <p><b>Headmaster name:</b> {{ $school->scm_hm_name ?? 'N/A' }}</p>
                                                        <p><b>HM Contact No:</b> <a
                                                                href="tel:{{ $school->scm_hm_phone}}">{{ $school->scm_hm_phone ?? 'N/A' }}</a>
                                                        </p>
                                                        <p class="mb-3"><b>HM Email Id:</b> <a
                                                                href="mailto:{{ $school->scm_hm_email}}">{{ $school->scm_hm_email ?? 'N/A' }}</a>
                                                        </p>

                                                        {{-- <p>
                                                            <b>Training:</b>
                                                            @if($school->training_completed)
                                                            <span class="text-success">Completed</span>
                                                            @else
                                                            <span class="text-danger">Not Completed</span>
                                                            @endif
                                                        </p> --}}

                                                        {{-- <p>
                                                            <b>Total Students:</b> {{ $school->students_count ?? 0 }}
                                                        </p> --}}

                                                        <a href="{{ route('dlc.school.details', $school->scm_id) }}"
                                                            class="btn btn-primary bg-[#153058] w-100">
                                                            View / Update
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach

                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</body>
@include('components.footer')