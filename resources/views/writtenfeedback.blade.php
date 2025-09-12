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
                            <h1 class="m-0 text-dark">Written Feedback</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Feedback</a></li>
                                <li class="breadcrumb-item active">Written Feedback</li>
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

                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Feedback</h2>
                                    <div class="flex justify-end mb-6">
                                        <button id="downloadBtn"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md shadow hover:bg-blue-600 transition flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                            </svg>
                                            <span>Download Training Feedback Form</span>
                                        </button>
                                    </div>
                                    <form class="space-y-5">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">School
                                                Name</label>
                                            <input type="text" id="schoolName" readonly
                                                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                        </div>

                                        <div class="flex items-end space-x-4 mt-2">
                                        <!-- Date -->
                                        <div class="w-1/3 mt-2">
                                            <label for="training_date"
                                                class="block text-sm font-medium text-gray-700 mb-1">Date of
                                                Training</label>
                                            <input type="date" id="training_date" name="training_date"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                        </div>

                                        <!-- Time From - To -->
                                        
                                    </div>
                                        </br>
                                        <!-- Upload Instruction -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Feedback Pdf (all feedback should be in one pdf file)</label>
                                        <!-- Upload File (pdf) -->
                                        <div id="dropZone"
                                            class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                            </svg>
                                            <p class="text-gray-500">Drag and drop PDF file, or click to select</p>
                                            <input type="file" id="fileUpload" class="hidden" accept="application/pdf"
                                                multiple>
                                            <div id="fileList" class="mt-3 text-sm text-gray-700 space-y-3"></div>
                                        </div>
                                        <!-- File Preview Section -->
                                        <div id="fileList" class="mt-3 text-sm text-gray-700 flex flex-wrap gap-3">
                                        </div>
                                    </div>

                                    <!-- Modal for preview -->
                                    <div id="imageModal"
                                        class="fixed inset-0 bg-black bg-opacity-70 hidden justify-center items-center z-50">
                                        <div class="relative max-w-4xl max-h-[90%]">
                                            <button id="closeModal"
                                                class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded-full">X</button>
                                            <img id="modalImage" src=""
                                                class="max-w-full max-h-[90vh] rounded shadow-lg" />
                                        </div>
                                    </div>


                                        <!-- Submit Button -->
                                        <button type="submit"
                                            class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">
                                            Submit Feedback
                                        </button>
                                    </form>

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
            const fileUrl = "/feedbackform/training_camp_feedback_form_image.pdf";
            const link = document.createElement("a");
            link.href = fileUrl;
            link.download = "training_camp_feedback_form.pdf";
            link.click();
        });
    </script>
    <script>
       const dropZone = document.getElementById("dropZone");
    const fileInput = document.getElementById("fileUpload");
    const fileList = document.getElementById("fileList");

    let uploadedFiles = [];

    // Open file dialog only when clicking background, not children
    dropZone.addEventListener("click", (e) => {
        if (e.target === dropZone || e.target.tagName === "P" || e.target.tagName === "SVG" || e.target.tagName === "PATH") {
            fileInput.click();
        }
    });

    // Handle input
    fileInput.addEventListener("change", (e) => {
        handleFiles(e.target.files);
        fileInput.value = ""; // reset so same file can be added again
    });

    // Drag events
    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("border-green-500");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("border-green-500");
    });

    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("border-green-500");
        handleFiles(e.dataTransfer.files);
    });

    // Handle files
    function handleFiles(files) {
        [...files].forEach(file => {
            if (file.type !== "application/pdf") {
                alert("Only PDF files are allowed!");
                return;
            }

            uploadedFiles.push(file);

            // Create file entry
            const fileDiv = document.createElement("div");
            fileDiv.className = "flex items-center justify-between border rounded p-2 shadow-sm bg-gray-50";

            fileDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-sm text-gray-700">${file.name} (${(file.size / 1024).toFixed(1)} KB)</span>
                </div>
                <div class="space-x-2">
                    <button type="button" class="openBtn text-blue-600 underline text-xs">Open</button>
                    <button type="button" class="removeBtn bg-red-500 text-white text-xs px-2 rounded">X</button>
                </div>
            `;

            // Open in new tab
            fileDiv.querySelector(".openBtn").addEventListener("click", () => {
                const pdfURL = URL.createObjectURL(file);
                window.open(pdfURL, "_blank");
            });

            // Remove file
            fileDiv.querySelector(".removeBtn").addEventListener("click", () => {
                fileList.removeChild(fileDiv);
                uploadedFiles = uploadedFiles.filter(f => f !== file);
            });

            fileList.appendChild(fileDiv);
        });
    }

         // Auto fetch Date
        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("training_date").value = today;
        });
        // Auto fetch School Name (example: from login session)
        // In real project, replace this with backend value (Laravel Blade, session, etc.)
        const loggedInSchool = "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // ← This should come from login
        document.getElementById("schoolName").value = loggedInSchool;
    </script>

</body>
@include('components.footer')
