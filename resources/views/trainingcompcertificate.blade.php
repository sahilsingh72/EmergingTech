@include('components.navbar')
@include('components.sidebar')
<style>
    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 200% 50%;
        }
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
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* .wrapper, .content-wrapper {
  position: static !important;
} */
</style>

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
            <section class="content relative">
                <div id="uploadOverlay"
                    class="hidden absolute inset-0 bg-black/40 flex flex-col items-center justify-center z-[9999] rounded-lg backdrop-blur-sm">
                    <div class="loader border-t-4 border-green-400 rounded-full w-16 h-16 animate-spin mb-4"></div>
                    <p class="text-white text-lg font-medium mt-4">Uploading, please wait...</p>
                </div>
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-8xl mx-auto space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Training Completion Certificate</h2>

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

                                    <form id="certificateUploadForm" method="POST"
                                        action="{{ route('upload.certificate') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <x-input-label for="school_id" :value="__('School Name')" />
                                            <select name="school_id" id="school_id" class="form-control">
                                                <option value="">-- Select School --</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}"
                                                        {{ $selectedSchoolId == $school->scm_id ? 'selected' : '' }}>
                                                        {{ $school->scm_name }} - {{ $school->scm_udise_code }}, {{ $school->scm_dist }}
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Training
                                            Completion Certificate (with HM Signature)</label>

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

                                            <input type="file" name="training_completion_certificate" id="fileUpload"
                                                class="hidden" accept="image/*,.pdf">
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


                                        <!-- Declaration 1 -->
                                        <div class="flex items-center mb-3">
                                            <input type="checkbox" id="declarationCheckbox" name="declaration"
                                                class="mr-2 mb-2">
                                            <label for="declarationCheckbox" class="text-gray-700 text-sm">
                                                I hereby declare that the uploaded <strong>Training Completion
                                                    Certificate</strong>
                                                is authentic and signed by the Headmaster.
                                            </label>
                                        </div>
                                        <!-- Declaration 2 -->
                                        <div class="flex items-center mb-3">
                                            <input type="checkbox" id="trainingCompletedCheckbox"
                                                name="training_completed" value="1" class="mr-2 mb-2">
                                            <label for="trainingCompletedCheckbox" class="text-gray-700 text-sm">
                                                I confirm that the <strong>training session has been successfully
                                                    completed</strong> at this school.
                                            </label>
                                        </div>
                                        <!-- Upload Progress Section -->
                                        <div id="progressContainer" class="hidden mt-6">
                                            <div class="w-full bg-gray-200 rounded-full overflow-hidden h-5">
                                                <div id="progressBar"
                                                    class="h-5 bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 bg-[length:200%_100%] animate-gradient-move text-center text-white text-sm font-medium rounded-full transition-all duration-300 ease-linear"
                                                    style="width:0%">0%</div>
                                            </div>
                                            <p id="progressStatus"
                                                class="text-gray-600 text-sm mt-2 text-center italic">Preparing
                                                upload...</p>
                                        </div>


                                        <!-- Upload Button -->
                                        <button id="uploadBtn"
                                            class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ $canUploadCertificate ? '' : 'disabled' }}>
                                            Upload
                                        </button>
                                        @if($selectedSchoolId)
                                            @php
                                                $totalStudents = \App\Models\StudentMst::where('stu_scm_id', $selectedSchoolId)->where('attendance', 1)->count();
                                                $studentsWithFeedback = \App\Models\StudentMst::where('stu_scm_id', $selectedSchoolId)
                                                    ->where('attendance', 1)
                                                    ->whereNotNull('feedback_file_url')
                                                    ->count();
                                            @endphp
                                            {{-- @if($studentsWithFeedback < $totalStudents)
                                                <div class="text-red-600 mt-2">
                                                    ⚠️ All student feedback must be uploaded before uploading the Training Completion Certificate.
                                                    ({{ $studentsWithFeedback }} / {{ $totalStudents }} uploaded)
                                                </div>
                                            @endif --}}
                                        @endif

                                        @if(!$canUploadCertificate)
                                            <p class="text-red-600 mt-2">
                                                ⚠️ You must upload all previous training evidence files (pages 1 to 4) before
                                                uploading the Training Completion Certificate.
                                            </p>
                                        @endif
                                    </form>

                                    <!-- Upload Progress Tracker -->
                                    <div class="mt-10 border-t border-gray-300 pt-6">
                                        <h3 class="text-lg font-semibold mb-4 text-gray-800 text-center">
                                            Upload Progress Overview
                                        </h3>

                                        <div class="flex flex-wrap justify-center gap-6">
                                            @foreach($requiredFiles as $key => $label)
                                                @php
                                                    $isUploaded = in_array($key, $uploadedFiles);
                                                    $routeName = match ($key) {
                                                        'attendance_sheet' => 'attendance',
                                                        'training_photo' => 'trainingphotos',
                                                        'training_video' => 'trainingvideos',
                                                        'video_feedback' => 'uploadfeedback',
                                                        default => null,
                                                    };
                                                    // For written_feedback, check if all student feedbacks uploaded
                                                    // if ($key === 'written_feedback' && isset($selectedSchoolId)) {
                                                    //     $totalStudents = \App\Models\StudentMst::where('stu_scm_id', $selectedSchoolId)->where('attendance', 1)->count();
                                                    //     $studentsWithFeedback = \App\Models\StudentMst::where('stu_scm_id', $selectedSchoolId)
                                                    //         ->where('attendance', 1)
                                                    //         ->whereNotNull('feedback_file_url')
                                                    //         ->count();
                                                    //     $isUploaded = ($totalStudents > 0 && $studentsWithFeedback == $totalStudents);
                                                    // }
                                                @endphp

                                                {{-- <a href="{{ $routeName ? route($routeName) : '#' }}"
                                                    class="flex flex-col items-center group hover:scale-110 transition-transform duration-200"
                                                    title="{{ $label }}">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-12 h-12 flex items-center justify-center rounded-full border-4 transition-all duration-300
                                                                {{ $isUploaded
                                                                ? 'border-green-500 bg-green-100 text-green-600'
                                                                : 'border-gray-300 bg-gray-100 text-gray-400'
                                                                }}">
                                                            @if($isUploaded)
                                                                <i class="fas fa-check text-xl"></i>
                                                            @else
                                                                <i class="fas fa-times text-xl"></i>
                                                            @endif
                                                        </div>

                                                        <span
                                                            class="mt-2 text-sm font-medium {{ $isUploaded ? 'text-green-600' : 'text-gray-500' }}">
                                                            {{ $key === 'written_feedback' ? 'Student Feedbacks' : $label }}
                                                        </span>
                                                  </div>
                                                </a> --}}
                                                <a href="{{ $routeName ? route($routeName) : '#' }}"
                                                    class="flex flex-col items-center group hover:scale-110 transition"
                                                    title="{{ $label }}">

                                                    <div class="w-12 h-12 flex items-center justify-center rounded-full border-4
                                                        {{ $isUploaded ? 'border-green-500 bg-green-100 text-green-600'
                                                                    : 'border-gray-300 bg-gray-100 text-gray-400' }}">
                                                        <i class="fas {{ $isUploaded ? 'fa-check' : 'fa-times' }} text-xl"></i>
                                                    </div>

                                                    <span class="mt-2 text-sm font-medium
                                                        {{ $isUploaded ? 'text-green-600' : 'text-gray-500' }}">
                                                        {{ $label }}
                                                    </span>
                                                </a>
                                            @endforeach

                                            {{-- ✅ Always show the 6th step: Completion Certificate --}}
                                            @php
                                                $certificateUploaded = in_array('training_completion_certificate', $uploadedFiles);
                                            @endphp
                                            <div class="flex flex-col items-center">
                                                <div class="w-12 h-12 flex items-center justify-center rounded-full border-4 transition-all duration-300
                                                {{ $certificateUploaded
                                    ? 'border-green-500 bg-green-100 text-green-600'
                                    : 'border-gray-300 bg-gray-100 text-gray-400'
                                                }}">
                                                    <i class="fas fa-award text-xl"></i>
                                                </div>
                                                <span
                                                    class="mt-2 text-sm font-medium {{ $canUploadCertificate ? 'text-blue-600' : 'text-gray-500' }}">
                                                    Completion Certificate
                                                </span>
                                            </div>
                                            @if($selectedSchoolId)
                                                @php
                                                    $totalStudents = \App\Models\StudentMst::where('stu_scm_id', $selectedSchoolId)
                                                        ->where('attendance', 1)->count();

                                                    $studentsWithFeedback = \App\Models\StudentMst::where('stu_scm_id', $selectedSchoolId)
                                                        ->where('attendance', 1)
                                                        ->whereNotNull('feedback_file_url')->count();

                                                    $feedbackCompleted = ($totalStudents > 0 && $studentsWithFeedback === $totalStudents);
                                                @endphp

                                                <div class="flex flex-col items-center">
                                                    <div class="w-12 h-12 flex items-center justify-center rounded-full border-4
                                                        {{ $feedbackCompleted ? 'border-green-500 bg-green-100 text-green-600'
                                                                            : 'border-yellow-500 bg-yellow-100 text-yellow-600' }}">
                                                        <i class="fas fa-users text-xl"></i>
                                                    </div>

                                                    <span class="mt-2 text-sm font-medium">
                                                        Student Feedbacks
                                                    </span>

                                                    <span class="text-xs text-gray-600 mt-1">
                                                        {{ $studentsWithFeedback }} / {{ $totalStudents }} uploaded
                                                    </span>
                                                </div>
                                            @endif
                                            {{-- ✅ FINAL STEP: Training Completed --}}
                                            <div class="flex flex-col items-center">
                                                <div class="w-14 h-14 flex items-center justify-center rounded-full border-4 transition-all duration-300
                                                    {{ $trainingCompleted
                                                        ? 'border-green-600 bg-green-200 text-green-700'
                                                        : 'border-gray-300 bg-gray-100 text-gray-400'
                                                    }}">
                                                    @if($trainingCompleted)
                                                        <i class="fas fa-check-double text-2xl"></i>
                                                    @else
                                                        <i class="fas fa-flag-checkered text-2xl"></i>
                                                    @endif
                                                </div>

                                                <span class="mt-2 text-sm font-semibold
                                                    {{ $trainingCompleted ? 'text-green-700' : 'text-gray-500' }}">
                                                    Training Completed
                                                </span>
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const uploadBtn = document.getElementById('uploadBtn');
    const checkbox1 = document.getElementById('declarationCheckbox');
    const checkbox2 = document.getElementById('trainingCompletedCheckbox');
    const canUploadCertificate = {{ $canUploadCertificate ? 'true' : 'false' }};

    function toggleUploadButton() {
        if (canUploadCertificate && checkbox1.checked && checkbox2.checked) {
            uploadBtn.disabled = false;
            uploadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            uploadBtn.disabled = true;
            uploadBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Run once on load
    toggleUploadButton();

    // Re-run whenever checkbox changes
    checkbox1.addEventListener('change', toggleUploadButton);
    checkbox2.addEventListener('change', toggleUploadButton);
});
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('certificateUploadForm');
            const overlay = document.getElementById('uploadOverlay');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressStatus = document.getElementById('progressStatus');
            const fileInput = document.getElementById('fileUpload');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const file = fileInput.files[0];
                if (!file) {
                    Swal.fire('Error', 'Please select a File before uploading.', 'error');
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
                xhr.open('POST', "{{ route('upload.certificate') }}", true);
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
                                progressStatus.textContent = 'Upload Complete!';
                                setTimeout(() => {
                                    Swal.fire('✅ Success', 'Certificate uploaded successfully!', 'success');
                                    overlay.classList.add('hidden');
                                    form.reset();
                                    progressContainer.classList.add('hidden');
                                    document.getElementById('fileList').innerHTML = '';
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('certificateUploadForm');
            const checkbox1 = document.getElementById('declarationCheckbox');
            const checkbox2 = document.getElementById('trainingCompletedCheckbox');
            const errorMsg = document.getElementById('declarationError');

            if (!form || !checkbox1 || !checkbox2 || !errorMsg) return;

            form.addEventListener('submit', function (e) {
                if (!checkbox1.checked || !checkbox2.checked) {
                    e.preventDefault(); // Stop form submit
                    errorMsg.classList.remove('hidden');
                    errorMsg.classList.add('block');
                    checkbox1.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    errorMsg.classList.add('hidden');
                    errorMsg.classList.remove('block');
                }
            });
        });

    </script>

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


<script>
document.getElementById('school_id').addEventListener('change', function() {
    const schoolId = this.value;
    if (schoolId) {
        window.location.href = `?school_id=${schoolId}`;
    }
});
</script>
</body>
@include('components.footer')