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
                            <h1 class="m-0 text-dark">School Details</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">{{ $school->scm_name }}</a></li>
                                <li class="breadcrumb-item active">School Details</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">School Details — {{ $school->scm_name }}</h2>

                                    <div class="container mt-4">

                                        <h2 class="mb-4 text-2xl text-center"><u> Annexure A-2 </u></h2>

                                        @if(session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        <form action="{{ route('dlc.school.update', $school->scm_id) }}" method="POST">
                                            @csrf

                                            <div class="row">

                                                <!-- School Info Card -->
                                                <div class="col-md-12 mb-4">
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-header bg-primary text-white">
                                                            <h5 class="mb-0">Basic School Information</h5>
                                                        </div>
                                                        <div class="card-body">

                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <p><b>District:</b> {{ $school->scm_dist }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p><b>School:</b> {{ $school->scm_name }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p><b>UDISE:</b> {{ $school->scm_udise_code }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p><b>Pin Code:</b> {{ $school->scm_pin_code }}</p>
                                                                </div>
                                                            </div>
                                                                
                                                            <div class="row mt-4">

                                                                    <div class="col-md-12">
                                                                        <label class="form-label"><b>School
                                                                                Address</b></label>
                                                                        <input type="text" class="form-control"
                                                                            name="scm_address"
                                                                            value="{{ $school->scm_address }}" required>
                                                                    </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>




                                                <!-- HM Details -->
                                                <div class="col-md-12 mb-4">
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-header bg-dark text-white">
                                                            <h5 class="mb-0">Headmaster Details</h5>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="row">

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">HM Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="scm_hm_name"
                                                                        value="{{ $school->scm_hm_name }}" required>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">HM Contact No</label>
                                                                    <input type="text" class="form-control"
                                                                        name="scm_hm_phone"
                                                                        value="{{ $school->scm_hm_phone }}" required>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">HM WhatsApp No</label>
                                                                    <input type="text" class="form-control"
                                                                        name="scm_hm_wp"
                                                                        value="{{ $school->scm_hm_wp }}" required>
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">HM Email Id</label>
                                                                    <input type="email" class="form-control"
                                                                        name="scm_hm_email"
                                                                        value="{{ $school->scm_hm_email }}" required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>




                                                <!-- School SPOC Details -->
                                                <div class="col-md-12 mb-4">
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-header bg-gray-700 text-white">
                                                            <h5 class="mb-0">School Single Point of Contact (SPOC) Details</h5>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="row">

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">SPOC Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="scm_spoc_name"
                                                                        value="{{ $school->scm_spoc_name }}" required>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">SPOC Contact No</label>
                                                                    <input type="text" class="form-control"
                                                                        name="scm_spoc_phone"
                                                                        value="{{ $school->scm_spoc_phone }}" required>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">SPOC WhatsApp No</label>
                                                                    <input type="text" class="form-control"
                                                                        name="scm_spoc_wp"
                                                                        value="{{ $school->scm_spoc_wp }}" required>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">SPOC Email Id</label>
                                                                    <input type="email" class="form-control"
                                                                        name="scm_spoc_email"
                                                                        value="{{ $school->scm_spoc_email }}" required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>




                                                <!-- Infrastructure Details -->
                                                <div class="col-md-12 mb-4">
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-header bg-secondary text-white">
                                                            <h5 class="mb-0">Infrastructure Information</h5>
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="row">

                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Avalibility of three Classrooms?</label>
                                                                    <select class="form-select w-16 ml-2"
                                                                        name="scm_avail_3_class" required>
                                                                        <option value="Yes" {{ $school->scm_avail_3_class == 1 ? 'selected' : '' }}>Yes</option>
                                                                        <option value="No" {{ $school->scm_avail_3_class == 0 ? 'selected' : '' }}>No</option>
                                                                    </select>
                                                                </div>
 
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Power Back-up in Classroom (DG/ Inverter)?</label>
                                                                    <select class="form-select w-16 ml-2" name="scm_powerbackup" id="scm_powerbackup" required>
                                                                        <option value="Yes" {{ $school->scm_powerbackup == 1 ? 'selected' : '' }}>Yes</option>
                                                                        <option value="No" {{ $school->scm_powerbackup == 0 ? 'selected' : '' }}>No</option>
                                                                    </select>
                                                                </div>
                                                                <!-- POWER BACKUP TYPE (Conditional) -->
                                                                <div class="col-md-4 mb-3 d-none" id="power_type_box">
                                                                    <label class="form-label">Select Power Backup Type</label>
                                                                    <select class="form-select w-24 ml-2" name="scm_powerbackup_type" id="scm_powerbackup_type">
                                                                        <option value="">-- Select --</option>
                                                                        <option value="DG" {{ $school->scm_powerbackup_type == 'DG' ? 'selected' : '' }}>DG</option>
                                                                        <option value="Inverter" {{ $school->scm_powerbackup_type == 'Inverter' ? 'selected' : '' }}>Inverter</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Internet facility in Classrooms?</label>
                                                                    <select class="form-select w-16 ml-2" id="scm_internet" name="scm_internet" required>
                                                                        <option value="Yes" {{ $school->scm_internet == 1 ? 'selected' : '' }}>Yes</option>
                                                                        <option value="No" {{ $school->scm_internet == 0 ? 'selected' : '' }}>No</option>
                                                                    </select>
                                                                </div>
                                                                <!-- INTERNET TYPE (Conditional) -->
                                                                <div class="col-md-4 mb-3 d-none" id="internet_type_box">
                                                                    <label class="form-label">Select Internet Type</label>
                                                                    <select class="form-select w-24 ml-2" name="scm_internet_type" id="scm_internet_type">
                                                                        <option value="">-- Select --</option>
                                                                        <option value="Broadband" {{ $school->scm_internet_type == 'Broadband' ? 'selected' : '' }}>Broadband</option>
                                                                        <option value="Jio Fiber" {{ $school->scm_internet_type == 'Airtel Fiber' ? 'selected' : '' }}>Jio Fiber</option>
                                                                        <option value="Airtel Fiber" {{ $school->scm_internet_type == 'Airtel Fiber' ? 'selected' : '' }}>Airtel Fiber</option>
                                                                    </select>
                                                                </div>
                                                                    
                                                                    
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">No of Smart
                                                                        Classrooms</label>
                                                                    <input type="number" class="form-control"
                                                                        name="scm_smartclass"
                                                                        value="{{ $school->scm_smartclass }}" required>
                                                                    </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- Submit Button -->
                                                <div class="col-md-12 text-center flex justify-between">
                                                    <div>
                                                        <a href="{{ route('my.schools') }}" class="btn btn-secondary">Back</a>
                                                    </div>
                                                    <div>
                                                        <button type="submit" class="btn btn-success">
                                                            Update School Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<script>
function toggleFields() {
    let pb = document.getElementById('scm_powerbackup').value;
    let pbBox = document.getElementById('power_type_box');
    let pbType = document.getElementById('scm_powerbackup_type');

    let net = document.getElementById('scm_internet').value;
    let netBox = document.getElementById('internet_type_box');
    let netType = document.getElementById('scm_internet_type');

    // Power Backup Toggle
    if (pb === "Yes") {
        pbBox.classList.remove('d-none');
        pbType.setAttribute('required', 'required');
    } else {
        pbBox.classList.add('d-none');
        pbType.removeAttribute('required');
        pbType.value = "";
    }

    // Internet Toggle
    if (net === "Yes") {
        netBox.classList.remove('d-none');
        netType.setAttribute('required', 'required');
    } else {
        netBox.classList.add('d-none');
        netType.removeAttribute('required');
        netType.value = "";
    }
}

// Trigger on change
document.getElementById('scm_powerbackup').addEventListener('change', toggleFields);
document.getElementById('scm_internet').addEventListener('change', toggleFields);

// Trigger on page load (edit page)
toggleFields();
</script>

</body>
@include('components.footer')