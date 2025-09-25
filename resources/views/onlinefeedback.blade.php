
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
                            <h1 class="m-0 text-dark">Online Feedback</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer</a></li>
                                <li class="breadcrumb-item active">Online Feedback</li>
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

                                    <h2 class="text-2xl font-semibold text-center mb-6">Online Feedback</h2>
                                     <!-- Download Button -->
                                    <div class="flex justify-end mb-6">
                                        <button id="downloadBtn"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md shadow hover:bg-blue-600 transition flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                            </svg>
                                            <span>Download Feedback QR Code</span>
                                        </button>
                                    </div>

                                    <!-- QR Code Card -->
                                    <div class="flex justify-center">
                                        <div class=" p-6 rounded-2xl  w-90">
                                            <h3 class="text-lg font-medium mb-4 text-gray-800 text-center">Scan to Submit Feedback</h3>
                                            <p class="text-sm text-gray-700 mt-1 text-center">
                                                Open your camera or QR scanner and scan this code to access the feedback form.
                                            </p>
                                            
                                            <!-- QR Code Image -->
                                            <img src="{{ asset('images/FeedbackFormQR.png') }}" alt="Feedback QR Code" 
                                                 class="mx-auto w-80 object-contain rounded-lg ">

                                            <div style="text-align:center; color:blue">
                                            <a href="https://forms.gle/sKRLei4hGFTcgpmP9" target="_blank"
                                               >
                                                Click here for feedback
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                    <!-- End QR Code Card -->
                                    

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
    
</body>
@include('components.footer')
