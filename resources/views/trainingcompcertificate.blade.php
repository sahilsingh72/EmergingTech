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
                            <h1 class="m-0 text-dark">Training Completion Certificate</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Training Completion Certificate</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Training Completion
                                        Certificate</h2>

                                    <!-- Download Button -->
                                    <div class="flex justify-end mb-6">
                                        <button id="downloadBtn"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md shadow hover:bg-blue-600 transition flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                            </svg>
                                            <span>Download Training Completion Certificate</span>
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('upload.certificate') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                    <div>
                                        <x-input-label for="school_id" :value="__('School Name')" />
                                            <select name="school_id" id="school_id" class="form-control">
                                                <option value="">-- Select School --</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}">
                                                        {{ $school->scm_name }} - {{ $school->scm_udise_code }},
                                                        {{ $school->scm_dist }}
                                                    </option>
                                                @endforeach
                                            </select>
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

                                    <!-- Upload Instruction -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Training Completion Certificate (with HM Signature)</label>

                                    <!-- Upload Box -->
                                    <div id="dropZone"
                                        class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                        </svg>
                                        <p class="text-gray-500">Drag and drop PDF / Image, or click to select</p>

                                        <input type="file" name="training_completion_certificate" id="fileUpload" class="hidden" accept="image/*,.pdf">
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

                                    <!-- Upload Button -->
                                    <button
                                        class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">
                                        Upload
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
            const fileUrl = "/completioncertificate/Training_Completion_Certificate.docx";
            const link = document.createElement("a");
            link.href = fileUrl;
            link.download = "Training_Completion_Certificate.docx";
            link.click();
        });
    </script>
    <script>
        const dropZone = document.getElementById("dropZone");
const fileInput = document.getElementById("fileUpload");
const fileList = document.getElementById("fileList");
const modal = document.getElementById("imageModal");
const modalImage = document.getElementById("modalImage");
const closeModal = document.getElementById("closeModal");

let uploadedFile = null;

// Only open file dialog if user clicks directly on dropZone background, not children
dropZone.addEventListener("click", (e) => {
    if (e.target === dropZone || e.target.tagName === "P" || e.target.tagName === "SVG" || e.target.tagName === "PATH") {
        fileInput.click();
    }
});

// Handle file input change
fileInput.addEventListener("change", (e) => {
    handleFile(e.target.files[0]); // only take first file
    // fileInput.value = ""; // reset
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
    handleFile(e.dataTransfer.files[0]); // only first file
});

function handleFile(file) {
    if (!file) return;

    // Only one file allowed
    if (uploadedFile) {
        alert("You can only upload one file.");
        return;
    }

    if (!(file.type.startsWith("image/") || file.type === "application/pdf")) {
        alert("Only images and PDF files are allowed!");
        return;
    }

    uploadedFile = file;
    fileList.innerHTML = ""; // clear previous preview

    const reader = new FileReader();
    reader.onload = () => {
        const fileDiv = document.createElement("div");
        fileDiv.className = "relative w-28 h-28 border rounded overflow-hidden shadow flex items-center justify-center";

        if (file.type.startsWith("image/")) {
            // Image preview
            fileDiv.innerHTML = `
                <img src="${reader.result}" class="w-full h-full object-cover cursor-pointer">
                <button type="button" 
                    class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded">X</button>
            `;

            // Open modal on click
            fileDiv.querySelector("img").addEventListener("click", () => {
                modalImage.src = reader.result;
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });

        } else if (file.type === "application/pdf") {
            // PDF preview
            fileDiv.innerHTML = `
                <div class="flex flex-col items-center cursor-pointer">
                    <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 2a2 2 0 00-2 2v16a2 
                                 2 0 002 2h12a2 2 0 002-2V8l-6-6H6z"/>
                    </svg>
                    <span class="text-xs mt-1 truncate w-24 text-center">${file.name}</span>
                </div>
                <button type="button" 
                    class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded">X</button>
            `;

            // Open PDF in new tab
            fileDiv.querySelector("div").addEventListener("click", () => {
                const pdfBlob = new Blob([file], { type: "application/pdf" });
                const pdfUrl = URL.createObjectURL(pdfBlob);
                window.open(pdfUrl, "_blank");
            });
        }

        // Remove button
        fileDiv.querySelector("button").addEventListener("click", () => {
            fileList.removeChild(fileDiv);
            uploadedFile = null;
        });

        fileList.appendChild(fileDiv);
    };

    reader.readAsDataURL(file);
}

// Close modal
closeModal.addEventListener("click", () => {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
});

modal.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
});

// Close modal with ESC key
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
});
        // Auto fetch Date
        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("training_date").value = today;
        });
    </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                title: '✅ Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            })
        </script>
    @endif
</body>
@include('components.footer')
