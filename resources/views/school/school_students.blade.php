@include('components.navbar')
@include('components.sidebar')

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
                            <h1 class="m-0 text-dark">Student List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a>{{ $school->scm_name }}</a></li>
                                <li class="breadcrumb-item active">Student List</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="py-1">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-0 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <h1 class="text-3xl font-bold text-center mb-3">
                                        Students of {{ $school->scm_name }}
                                    </h1>
                                    <div class="container-fluid">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-[#153058] text-white">
                                                <h4 class="mb-0">Student List</h4>
                                            </div>

                                            <div class="card-body">
                                                @if($students->isEmpty())
                                                    <p class="text-center text-danger">No students found.</p>
                                                @else
                                                    <table class="table table-bordered table-striped">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Sno</th>
                                                                <th>Name</th>
                                                                {{-- <th>Roll No</th> --}}
                                                                <th>Father Name</th>
                                                                <th>Class</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($students as $stu)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $stu->stu_name }}</td>
                                                                    {{-- <td>{{ $stu->stu_roll_number }}</td> --}}
                                                                    <td>{{ $stu->stu_fathername }}</td>
                                                                    <td>{{ $stu->stu_class }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
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
</div>

@include('components.footer')
