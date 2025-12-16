@include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Training Completion Certificate</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Training Completion Certificates</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid py-12">
                    <div class="max-w-8xl mx-auto space-y-6">
                        <div class="sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Training Completion Certificates
                                </h2>

                                <!-- Filters -->
                                <form method="GET">
                                    <div class="row flex mb-4 justify-between">
                                        <div class="col-md-6">
                                            <label for="district_id">Select District</label>
                                            <select name="district_id" id="district_id" class="form-control">
                                                <option value="">Select Districts</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->DSM_DSCD }}" {{ $districtId == $district->DSM_DSCD ? 'selected' : '' }}>
                                                        {{ $district->DSM_DSNM }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_id">Select School</label>
                                            <select name="school_id" id="school_id" class="form-control">
                                                <option value="">All Schools</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}" {{ $schoolId == $school->scm_id ? 'selected' : '' }}>
                                                        {{ $school->scm_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <hr>
                                </br>
                                @if(count($certificates) > 0)
                                    <div class="row">
                                        @foreach($certificates as $cert)
                                            <div class="col-md-3 mb-3">
                                                <div class="border p-3 rounded shadow-sm">
                                                    <p><strong>District:</strong>
                                                        {{ $cert->school->district->DSM_DSNM ?? 'N/A' }}</p>
                                                    <p><strong>School:</strong> {{ $cert->school->scm_name ?? 'N/A' }}</p>
                                                    <p><strong>Uploaded By:</strong> {{ $cert->user->name ?? 'N/A' }}</p>
                                                    <p><strong>Date:</strong> {{ $cert->training_date ?? $cert->created_at }}
                                                    </p>
                                                    <a href="{{ route('preview.file', ['path' => $cert->onedrive_path]) }}"
                                                        target="_blank" class="btn btn-sm btn-primary w-full mt-2">
                                                        View Certificate
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($schoolId)
                                    <p>No certificates uploaded for this school yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.getElementById('district_id').addEventListener('change', function () {
            this.form.submit();
        });

        document.getElementById('school_id').addEventListener('change', function () {
            this.form.submit();
        });
    </script>
    @include('components.footer')