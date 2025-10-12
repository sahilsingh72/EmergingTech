@include('components.navbar')
@include('components.sidebar')
<style>
@keyframes gradientMove {
  0% { background-position: 0% 50%; }
  100% { background-position: 200% 50%; }
}
.animate-gradient-move {
  animation: gradientMove 2s linear infinite;
}

  #uploadOverlay {
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(2px);
  }

  .loader {
    border-right-color: transparent;
    border-bottom-color: transparent;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.6);
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  /* .wrapper, .content-wrapper {
  position: static !important;
} */

</style>
<body class="hold-transition sidebar-mini layout-fixed">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Student Attendance</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Student Attendance</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content relative">
                <div id="uploadOverlay"
                    class="hidden absolute inset-0 bg-black/40 flex flex-col items-center justify-center z-[9999] rounded-lg backdrop-blur-sm">
                 <div class="loader border-t-4 border-green-400 rounded-full w-16 h-16 animate-spin mb-4"></div>
                 <p class="text-white text-lg font-medium mt-4">Uploading, please wait...</p>
               </div>
                <div class="container-fluid py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white p-8 rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Upload Student Attendance</h2>
                                <div class="mb-4 flex justify-end">
                                    <a href="{{route('attendance.list')}}"><button
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                        <i class="fas fa-list"></i> View Attendance Records
                                    </button></a>
                                </div>


                                <form id="attendanceUploadForm" action="{{ route('upload.attendance') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <!-- School Select -->
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

                                    <!-- Training Date -->
                                    <div class="flex items-end space-x-4 mt-2">
                                        <div class="w-1/3 mt-2">
                                            <label for="training_date"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                Date of Training
                                            </label>
                                            <input type="date" id="training_date" name="training_date"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                        </div>
                                    </div>

                                    <!-- Attendance Upload -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">
                                        Upload Student's Attendance Sheet (PDF or Image)<span style="color:red">*</span>
                                    </label>
                                    {{-- <input type="file" name="attendance_files[]" multiple
                                        accept="application/pdf,image/*" class="border p-2 rounded w-full mb-6"> --}}

                                    <div id="dropZone"
                                        class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                        </svg>
                                        <p class="text-gray-500">Drag and drop PDF / Image, or click to select</p>

                                        <input type="file" name="attendance_files[]" id="fileUpload" class="hidden"
                                            multiple accept="application/pdf,image/*" required>
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
                                    <!-- Trainer Image Upload -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">
                                        Upload Image of Trainer with Students <span style="color:red">*</span>
                                    </label>
                                    {{-- <input type="file" name="trainer_image" accept="image/*"
                                        class="border p-2 rounded w-full mb-6"> --}}

                                    <div id="trainerDropZone"
                                         class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3"/>
                                        </svg>
                                        <p class="text-gray-500">Drag and drop trainer image, or click to select</p>
                                        <input type="file" name="trainer_image" id="trainerUpload" class="hidden" accept="image/*" required>
                                        <div id="trainerPreview" class="mt-3 text-sm text-gray-700 flex flex-wrap gap-3"></div>
                                    </div>

                                    <!-- Modal for trainer image -->
                                    <div id="trainerModal"
                                         class="fixed inset-0 bg-black bg-opacity-70 hidden justify-center items-center z-50">
                                        <div class="relative max-w-4xl max-h-[90%]">
                                            <button id="closeTrainerModal"
                                                    class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded-full">X
                                            </button>
                                            <img id="modalTrainerImage" src="" class="max-w-full max-h-[90vh] rounded shadow-lg"/>
                                        </div>
                                    </div>
                                    <!-- Upload Progress Section -->
                                    <div id="progressContainer" class="hidden mt-6">
                                        <div class="w-full bg-gray-200 rounded-full overflow-hidden h-5">
                                            <div id="progressBar"
                                            class="h-5 bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 bg-[length:200%_100%] animate-gradient-move text-center text-white text-sm font-medium rounded-full transition-all duration-300 ease-linear"
                                            style="width:0%">0%</div>
                                        </div>
                                        <p id="progressStatus" class="text-gray-600 text-sm mt-2 text-center italic">Preparing upload...</p>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                        class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">
                                        Upload
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('attendanceUploadForm');
    const overlay = document.getElementById('uploadOverlay');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressStatus = document.getElementById('progressStatus');
    const attendanceInput = document.getElementById('fileUpload');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const file = attendanceInput.files[0];
        if (!file) {
            Swal.fire('Error', 'Please select a file before uploading.', 'error');
            return;
        }

        const formData = new FormData(form);
        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        progressStatus.textContent = 'Uploading...';
        progressBar.classList.remove('bg-red-500');
        progressBar.classList.add('bg-gradient-to-r');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('upload.attendance') }}", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

        let smoothProgress = 0;
        let animationSpeed = 50; // lower = faster visual motion
        let targetPercent = 0;
        let animTimer;

        function smoothTo(target) {
            clearInterval(animTimer);
            animTimer = setInterval(() => {
                if (smoothProgress < target && smoothProgress < 90) {
                    smoothProgress += 0.5; // fine-grained smooth motion
                    progressBar.style.width = smoothProgress + '%';
                    progressBar.textContent = Math.floor(smoothProgress) + '%';
                } else {
                    clearInterval(animTimer);
                }
            }, animationSpeed);
        }

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                targetPercent = Math.min(Math.round((e.loaded / e.total) * 100), 90);
                smoothTo(targetPercent);
            }
        });

        xhr.onload = function () {
            clearInterval(animTimer);
            if (xhr.status === 200) {
                progressStatus.textContent = 'Finalizing...';
                let final = smoothProgress;
                const finishTimer = setInterval(() => {
                    if (final < 100) {
                        final += 0.5;
                        progressBar.style.width = final + '%';
                        progressBar.textContent = Math.floor(final) + '%';
                    } else {
                        clearInterval(finishTimer);
                        progressStatus.textContent = '✅ Upload Complete!';
                        setTimeout(() => {
                            Swal.fire('✅ Success', 'File uploaded successfully!', 'success');
                            overlay.classList.add('hidden');
                            form.reset();
                            progressContainer.classList.add('hidden');
                            document.getElementById('attendanceList').innerHTML = '';
                        }, 700);
                    }
                }, 60);
            } else {
                progressBar.classList.remove('bg-gradient-to-r');
                progressBar.classList.add('bg-red-500');
                progressStatus.textContent = '❌ Upload failed.';
                Swal.fire('❌ Failed', 'Upload failed. Please try again.', 'error');
                overlay.classList.add('hidden');
            }
        };
        overlay.classList.remove('hidden');
        xhr.send(formData);
    });
});
</script>
<!-- Validation Errors -->
@if ($errors->any())
<script>
    let errorMessage = "";
    @foreach ($errors->all() as $error)
        errorMessage += "{{ $error }}\n";
    @endforeach

    Swal.fire({
        title: '⚠️ Warning!',
        text: errorMessage,
        icon: 'warning',
        confirmButtonText: 'OK'
    });
</script>
@endif
    <script>
        // Auto-set today's date
        document.addEventListener('DOMContentLoaded', function () {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById('training_date').value = today;
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
        /** Trainer Image Drag & Drop */
        const trainerDropZone = document.getElementById("trainerDropZone");
        const trainerInput = document.getElementById("trainerUpload");
        const trainerPreview = document.getElementById("trainerPreview");
        const trainerModal = document.getElementById("trainerModal");
        const modalTrainerImage = document.getElementById("modalTrainerImage");
        const closeTrainerModal = document.getElementById("closeTrainerModal");

        let uploadedTrainerImage = null;

        trainerDropZone.addEventListener("click", (e) => {
            if ([trainerDropZone.tagName, "P","SVG","PATH"].includes(e.target.tagName) || e.target === trainerDropZone) {
                trainerInput.click();
            }
        });
        trainerInput.addEventListener("change", (e) => handleTrainerImage(e.target.files[0]));
        trainerDropZone.addEventListener("dragover", (e) => { e.preventDefault(); trainerDropZone.classList.add("border-green-500"); });
        trainerDropZone.addEventListener("dragleave", () => trainerDropZone.classList.remove("border-green-500"));
        trainerDropZone.addEventListener("drop", (e) => { e.preventDefault(); trainerDropZone.classList.remove("border-green-500"); handleTrainerImage(e.dataTransfer.files[0]); });

        function handleTrainerImage(file) {
            if (!file) return;
            if (uploadedTrainerImage) { alert("You can only upload one trainer image."); return; }
            if (!file.type.startsWith("image/")) { alert("Only image files are allowed!"); return; }

            uploadedTrainerImage = file;
            trainerPreview.innerHTML = "";

            const reader = new FileReader();
            reader.onload = () => {
                const imgDiv = document.createElement("div");
                imgDiv.className = "relative w-28 h-28 border rounded overflow-hidden shadow flex items-center justify-center";
                imgDiv.innerHTML = `<img src="${reader.result}" class="w-full h-full object-cover cursor-pointer">
                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded">X</button>`;

                imgDiv.querySelector("img").addEventListener("click", () => { modalTrainerImage.src = reader.result; trainerModal.classList.remove("hidden"); trainerModal.classList.add("flex"); });
                imgDiv.querySelector("button").addEventListener("click", () => { trainerPreview.removeChild(imgDiv); uploadedTrainerImage = null; trainerInput.value = ""; });

                trainerPreview.appendChild(imgDiv);
            };
            reader.readAsDataURL(file);
        }

        closeTrainerModal.addEventListener("click", () => { trainerModal.classList.add("hidden"); trainerModal.classList.remove("flex"); });
        trainerModal.addEventListener("click", (e) => { if (e.target === trainerModal) { trainerModal.classList.add("hidden"); trainerModal.classList.remove("flex"); } });
        document.addEventListener("keydown", (e) => { if (e.key === "Escape" && !trainerModal.classList.contains("hidden")) { trainerModal.classList.add("hidden"); trainerModal.classList.remove("flex"); } });
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