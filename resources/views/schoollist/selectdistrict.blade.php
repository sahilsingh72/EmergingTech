@include('components.navbar')
@include('components.sidebar')
<style>
    .directory-header {
        text-align: center;
        padding: 20px 0;
    }

    .search-box input {
        width: 60%;
        margin: 0 auto;
        padding: 12px 18px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        outline: none;
        transition: 0.3s;
    }

    .search-box input:focus {
        border-color: #1d4ed8;
        box-shadow: 0 0 5px rgba(29, 78, 216, 0.4);
    }

    .district-card-wrapper {
        display: block;
    }

    .district-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 320px;
        display: flex;
        flex-direction: column;
        /* overflow: hidden; */
    }

    .district-card:hover {
        transform: translateY(-5px);
    }

    .district-header {
        background: #153058;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
        color: white;
        padding: 18px;
    }

    .district-body {
        padding: 20px;
        background: #e3effa;
        flex: 1;
        display: flex;
        flex-direction: column;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
    }

    .school-list li {
        margin-bottom: 5px;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: list-item !important;
    }

    .school-list {
        list-style-type: disc !important;
        list-style-position: inside !important;
        padding-left: 15px !important;
        margin-left: 0 !important;
    }

    .view-btn,
    .district-body a.btn {
        margin-top: auto !important;
        display: block;
        background: #153058;

    }

    .training-count {
        background: #16a34a;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
    }

    a.btn:hover {
        background: #1b5db5;
        border: none
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
                            <h1 class="m-0 text-dark">District Schools Directory</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">District Schools Directory</li>
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

                                    @php
                                        $roleId = Auth::user()->role_id;
                                    @endphp
                                    @if($roleId == 2)
                                        <div class="flex justify-end">
                                            <a href="{{ route('main.school.list') }}">
                                                <button
                                                    class="bg-blue-950 hover:bg-blue-900 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                    <i class="fas fa-list"></i> School List
                                                </button>
                                            </a>
                                        </div>
                                    @endif

                                    <div class="directory-header">
                                        <h1 class="text-3xl font-bold">District Schools Directory</h1>
                                        <p class="text-gray-600">Browse schools across all districts</p>

                                        <div class="search-box mt-3 mb-4">
                                            <input type="text" class="form-control" id="searchInput"
                                                placeholder="Search districts or schools...">
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="row" id="districtContainer">

                                            @foreach($districts as $dis)
                                                <div class="col-md-4 mb-4 district-card-wrapper">
                                                    <div class="district-card">

                                                        <!-- Blue Header -->
                                                        <div
                                                            class="district-header d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h5 class="mb-1">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                    {{ $dis->DSM_DSNM }}
                                                                </h5>
                                                                <small><i class="fas fa-school"></i> Loading...</small>
                                                            </div>

                                                            <!-- Completed Training Count -->
                                                            <div class="text-center">
                                                                <p style="margin:0; font-weight:600; font-size:14px;">
                                                                    Training
                                                                </p>
                                                                <p style="margin:0; font-weight:600; font-size:14px;">
                                                                    <span class="training-count"
                                                                        id="training-{{ $dis->DSM_DSCD }}">
                                                                        0 / 0
                                                                    </span>
                                                                </p>
                                                            </div>

                                                            <!-- Color Legend -->
                                                            <div class="text-right">
                                                                <p style="display:block; font-weight:500;">
                                                                    <span style="color:green;">●</span> Completed
                                                                </p>
                                                                <p style="display:block; font-weight:500;">
                                                                    <span style="color:red;">●</span> Not Completed
                                                                </p>
                                                            </div>
                                                        </div>


                                                        <!-- Body -->
                                                        <div class="district-body">
                                                            <ul class="school-list"
                                                                id="school-preview-{{ $dis->DSM_DSCD }}">
                                                                <li>Loading district...</li>
                                                            </ul>

                                                            <a href="{{ route('select.school', $dis->DSM_DSCD) }}"
                                                                class="btn btn-primary mt-2 w-100">
                                                                View Details
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
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            @foreach($districts as $d)
                $.get(`/district-{{ $d->DSM_DSCD }}-schools`, function (response) {

                    let schools = response.schools;

                    let previewBox = $("#school-preview-{{ $d->DSM_DSCD }}");

                    // Total schools in district
                    let total = schools.length;

                    // Count completed
                    let completed = schools.filter(s => s.training_completed == 1).length;

                    // training count in header
                    $("#training-{{ $d->DSM_DSCD }}").text(`${completed} / ${total}`);

                    // school count label
                    $(".district-card-wrapper small")
                        .eq({{ $loop->index }})
                        .html(`<i class="fas fa-school"></i> ${total} Schools`);



                    if (schools.length === 0) {
                        previewBox.html("<li>No schools available</li>");
                        return;
                    }

                    let preview = "";
                    schools.slice(0, 6).forEach(s => {

                        let color = s.training_completed == 1 ? "green" : "red";

                        preview += `<li style="color:${color}; font-weight:500;">${s.scm_name}</li>`;
                    });

                    previewBox.html(preview);

                    $(".district-card-wrapper small")
                        .eq({{ $loop->index }})
                        .html(`<i class="fas fa-school"></i> ${schools.length} Schools`);
                });
            @endforeach
        });
    </script>
    <script>
        $("#searchInput").on("keyup", function () {
            let value = $(this).val().toLowerCase();
            $("#districtContainer .district-card-wrapper").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>


</body>
@include('components.footer')