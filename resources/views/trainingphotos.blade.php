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
                            <h1 class="m-0 text-dark">Training Photos</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Training Photos</a></li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Training Photos</h2>
                                    <form action="{{ route('upload.trainingphotos') }}" method="POST"
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


                                        </div>

                                        <!-- Upload Instruction -->
                                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Images
                                            of
                                            Training</label>

                                        <!-- Upload Box -->
                                        <div id="dropZone"
                                            class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                            </svg>
                                            <p class="text-gray-500">Drag and drop Image, or click to select</p>

                                            <input type="file" name="training_photo[]" id="fileUpload" class="hidden"
                                                accept="image/*" multiple>
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

                                        <!-- Any Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Any
                                                Description</label>
                                            <textarea rows="3" name="description" placeholder="Write details here..."
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300"></textarea>
                                        </div>

                                        <!-- Upload Button -->
                                        <button type="submit"
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
        const dropZone = document.getElementById("dropZone");
        const fileInput = document.getElementById("fileUpload");
        const fileList = document.getElementById("fileList");
        const modal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");
        const closeModal = document.getElementById("closeModal");

        let uploadedFiles = [];

        // Only open file dialog if user clicks directly on dropZone background, not children
        dropZone.addEventListener("click", (e) => {
            if (e.target === dropZone || e.target.tagName === "P" || e.target.tagName === "SVG" || e.target
                .tagName === "PATH") {
                fileInput.click();
            }
        });

        // Handle file input change
        fileInput.addEventListener("change", (e) => {
            handleFiles(e.target.files);
            //fileInput.value = ""; // reset so same file can be added again
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

        function handleFiles(files) {
            [...files].forEach(file => {
                if (uploadedFiles.length >= 5) {
                    alert("You can only upload up to 5 images.");
                    return;
                }

                if (!file.type.startsWith("image/")) {
                    alert("Only images are allowed!");
                    return;
                }

                uploadedFiles.push(file);

                const reader = new FileReader();
                reader.onload = () => {
                    const imgDiv = document.createElement("div");
                    imgDiv.className = "relative w-28 h-28 border rounded overflow-hidden shadow";

                    // insert preview
                    imgDiv.innerHTML = `
                    <img src="${reader.result}" class="w-full h-full object-cover cursor-pointer">
                    <button type="button" 
                        class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded">X</button>
                `;

                    // Remove image
                    imgDiv.querySelector("button").addEventListener("click", (ev) => {
                        ev.stopPropagation();
                        fileList.removeChild(imgDiv);
                        uploadedFiles = uploadedFiles.filter(f => f !== file);
                    });

                    // Open modal on thumbnail click
                    imgDiv.querySelector("img").addEventListener("click", (ev) => {
                        ev.stopPropagation();
                        modalImage.src = reader.result;
                        modal.classList.remove("hidden");
                        modal.classList.add("flex");
                    });

                    fileList.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            });
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