@include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Add Student</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Add Student</a></li>
                                <li class="breadcrumb-item active">Students</li>
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
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Add Student</h2>
                                    <div class="mb-4 flex justify-end">
                                        <a href="{{route('studentlist')}}">
                                            <button id=""
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                <i class="fas fa-list"></i>Student List
                                            </button>
                                        </a>
                                    </div>
                                    <!-- Flash Messages -->
                                @if(session('success'))
                                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if($errors->any())
                                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                                        <ul class="list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                
                                <!-- Upload Form -->
                                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">School
                                            Name</label>
                                        <input type="text" id="schoolName" readonly
                                            class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 mb-2 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Choose Excel File</label>
                                        <input type="file" name="file" accept=".xls,.xlsx,.csv"
                                            class="w-full border border-gray-300 rounded-md p-2">
                                        <p class="text-xs text-gray-500 mt-1">Allowed formats: .xls, .xlsx, .csv</p>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md text-lg font-medium transition">
                                        <i class="fas fa-file-upload mr-2"></i> Upload Students
                                    </button>
                                </form>
                                 <!-- Example File Link -->
                                <div class="mt-6">
                                    <p class="text-sm text-gray-600">Need a template? 
                                        <a href="{{ asset('sample/student_template.xlsx') }}" 
                                           class="text-blue-600 hover:underline">Download Sample Excel</a>
                                    </p>
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
    <script>
        document.getElementById("downloadBtn").addEventListener("click", () => {
            // Example: Download certificate template (replace with backend file route)
            const fileUrl =  src="{{ asset('images/FeedbackFormQR.png') }}";
            const link = document.createElement("a");
            link.href = fileUrl;
            link.download = "FeedbackFormQR.png";
            link.click();
        });
    </script>
    <script>
        
        // Auto fetch School Name (example: from session/auth)
        const loggedInSchool =
        "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // Replace with Blade variable in Laravel
        document.getElementById("schoolName").value = loggedInSchool;
    </script>
</body>
@include('components.footer')
