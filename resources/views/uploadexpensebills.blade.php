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
                            <h1 class="m-0 text-dark">Expenses Bill</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#"></a>Finance & Bills</li>
                                <li class="breadcrumb-item active">Expenses Bill</li>
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

                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Expenses Bill</h2>
                                    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl">

                                        <!-- Tabs -->
                                        <div class="flex border-b mb-6">
                                            <button id="tabFood"
                                                class="py-2 px-4 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">
                                                <i class="fas fa-utensils mr-2"></i>  Food Bill
                                            </button>
                                            <button id="tabHotel"
                                                class="py-2 px-4 text-sm font-medium border-b-2 border-transparent text-gray-600 ml-4">
                                                <i class="fas fa-hotel mr-2"></i>  Hotel Bill
                                            </button>
                                        </div>

                                        <!-- Food Bill Form -->
                                        <div id="foodForm" class="space-y-4">
                                            <form id="foodForm" action="" method=""
                                                enctype="multipart/form-data" class="space-y-4">
                                                <h3 class="text-lg font-semibold text-gray-700">Food Bill Submission
                                                </h3>

                                                <!--Name -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee
                                                        Name</label>
                                                    <input type="text" id="employeeName"
                                                        class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 " placeholder="Enter employee name">
                                                </div>

                                                <!-- Date (Auto Detect) -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date &
                                                        Time</label>
                                                    <input type="text" id="dateTimeField" readonly
                                                        class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                                </div>

                                                <!-- Upload PDF -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Upload
                                                        Complete
                                                        Food Bill (PDF)</label>
                                                    <input type="file" accept="application/pdf"
                                                        class="mt-1 block w-full text-gray-700 sm:text-sm">
                                                </div>
                                                <button type="submit"
                                                    class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">
                                                    Submit Food Bill
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Hotel Bill Form -->
                                        <div id="hotelForm" class="space-y-4 hidden">
                                            <h3 class="text-lg font-semibold text-gray-700">Hotel Bill Submission</h3>
                                            <form id="hotelForm" action="" method="" enctype=""
                                                class="space-y-4">
                                                <!--Name -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee
                                                        Name</label>
                                                    <input type="text" id="employeeName"
                                                        class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 " placeholder="Enter employee name">
                                                </div>
                                                <!-- From Date -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">From
                                                        Date</label>
                                                    <input type="date"
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                                </div>

                                                <!-- To Date -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">To
                                                        Date</label>
                                                    <input type="date"
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                                </div>

                                                <!-- Upload PDF -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Upload
                                                        Complete
                                                        Hotel Bill (PDF)</label>
                                                    <input type="file" accept="application/pdf"
                                                        class="mt-1 block w-full text-gray-700 sm:text-sm">
                                                </div>
                                                <button type="submit"
                                                    class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">Submit
                                                    Hotel Bill
                                                </button>
                                            </form>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto detect values
            $("#food_school").val("Auto-detected School Name");
            $("#food_date").val(new Date().toISOString().split('T')[0]);

            // Tab switching
            $("#tabFood").on("click", function() {
                $("#foodForm").show();
                $("#hotelForm").hide();

                $(this).addClass("border-indigo-600 text-indigo-600")
                    .removeClass("border-transparent text-gray-600");
                $("#tabHotel").addClass("border-transparent text-gray-600")
                    .removeClass("border-indigo-600 text-indigo-600");
            });

            $("#tabHotel").on("click", function() {
                $("#hotelForm").show();
                $("#foodForm").hide();

                $(this).addClass("border-indigo-600 text-indigo-600")
                    .removeClass("border-transparent text-gray-600");
                $("#tabFood").addClass("border-transparent text-gray-600")
                    .removeClass("border-indigo-600 text-indigo-600");
            });
        });
    </script>
    <script>
        // Auto detect Date & Time
        document.getElementById("dateTimeField").value = new Date().toLocaleString();

        // Auto fetch School Name (example: from login session)
        // In real project, replace this with backend value (Laravel Blade, session, etc.)
        const loggedInSchool =
            "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // ← This should come from login
        document.getElementById("schoolName").value = loggedInSchool;
    </script>

</body>
@include('components.footer')
