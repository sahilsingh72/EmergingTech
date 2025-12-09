@include('components.navbar')
@include('components.sidebar')
<style>
    .activeTab {
        border-bottom-width: 2px;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Media Gallery</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Media Gallery</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content relative">
                <div class="container-fluid py-12">
                    <div class="max-w-8xl mx-auto space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Training Camp Media Gallery</h2>

                                <form method="GET" class="mb-4">
                                    <div class="row flex mb-4 justify-between">
                                        <div class="col-md-6">
                                            <label for="district_id">Select District</label>
                                            <select name="district_id" id="district_id" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="">Select Districts</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->DSM_DSCD }}" {{ $districtId == $district->DSM_DSCD ? 'selected' : '' }}>
                                                        {{ $district->DSM_DSNM }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_id">Select School</label>
                                            <select name="school_id" id="school_id" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="">Select Schools</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}" {{ $schoolId == $school->scm_id ? 'selected' : '' }}>
                                                        {{ $school->scm_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <!-- Gallery Tabs -->
                                <div>
                                    <div class="border-b border-gray-300 mb-4">
                                        <ul
                                            class="flex flex-wrap md:flex-nowrap space-x-0 md:space-x-4 text-gray-600 font-semibold">
                                            <li class="cursor-pointer activeTab px-3 py-2 text-indigo-600 border-b-2 border-indigo-600"
                                                data-tab="trainingPhotoTab">Training Photos</li>

                                            <li class="cursor-pointer px-3 py-2" data-tab="trainingVideoTab">Training
                                                Videos</li>

                                            <li class="cursor-pointer px-3 py-2" data-tab="videoFeedbackTab">Video
                                                Feedback</li>
                                        </ul>
                                    </div>

                                    <!-- Tab Content Wrapper -->
                                    @if($districtId && $schoolId)
                                        <div>
                                            <div class="min-h-[250px]">
                                                <div class="tabContent hidden" id="trainingPhotoTab">
                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                        @php
                                                            $photo = $media->whereIn('filetype_id', [2, 3]);
                                                        @endphp
                                                        @if($photo->isEmpty())
                                                            <div class="p-6 bg-gray-100 rounded-xl text-center border border-gray-300">
                                                                <p class="text-gray-600 text-lg font-semibold">No posts found</p>
                                                                <p class="text-gray-500 text-sm">This school has not uploaded any media yet.</p>
                                                            </div>
                                                        @else
                                                            @foreach($photo as $m)
                                                                @php
                                                                    $paths = is_array($m->onedrive_path) ? $m->onedrive_path : [$m->onedrive_path];
            
                                                                    $districtName = $m->school->district->DSM_DSNM ?? '';
                                                                    $schoolName = $m->school->scm_name ?? '';
                                                                    $trainingDate = $m->school->training_date ?? '';
                                                                @endphp
            
                                                                @foreach($paths as $path)
                                                                    @php
                                                                        $finalPath = is_array($path) ? ($path['url'] ?? $path[0] ?? '') : $path;
                                                                    @endphp

                                                                    <div class="relative overflow-hidden rounded-lg border border-gray-200 shadow hover:shadow-lg transition duration-300">
                                                                        <div class="relative group">
                                                                            <a href="/preview-file?path={{ urlencode($finalPath) }}&mime=image/jpeg" target="_blank">
                                                                                <img src="/preview-file?path={{ urlencode($finalPath) }}&mime=image/jpeg"
                                                                                    class="w-full h-48 object-cover cursor-pointer transition-transform transform group-hover:scale-105" />

                                                                                <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 group-hover:scale-105
                                                                                            transition-opacity duration-300 flex flex-col justify-center items-center text-white p-3 text-center">
                                                                                    <p class="text-sm font-semibold">{{ $districtName }}</p>
                                                                                    <p class="text-xs">{{ $schoolName }}</p>
                                                                                    <p class="text-xs mt-1">{{ $trainingDate }}</p>
                                                                                </div>
                                                                            </a>
                                                                        </div>

                                                                        <div class="p-2 text-center border-t bg-gray-50">
                                                                            <a href="{{ route('download.file', ['path' => $finalPath]) }}"
                                                                            class="px-4 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                                                                Download
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
        
                                                <div class="tabContent hidden" id="trainingVideoTab">
                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                        @php
                                                            $trainingVideo = $media->whereIn('filetype_id', 4);
                                                        @endphp
                                                        @if($trainingVideo->isEmpty())
                                                            <div class="p-6 bg-gray-100 rounded-xl text-center border border-gray-300">
                                                                <p class="text-gray-600 text-lg font-semibold">No posts found</p>
                                                                <p class="text-gray-500 text-sm">This school has not uploaded any media yet.</p>
                                                            </div>
                                                        @else
                                                            @foreach($trainingVideo as $m)
                                                                @php
                                                                    $paths = is_array($m->onedrive_path) ? $m->onedrive_path : [$m->onedrive_path];
                                                                @endphp
                                                                @foreach($paths as $path)
                                                                    @php
                                                                        $finalPath = is_array($path) ? ($path['url'] ?? $path[0] ?? '') : $path;
                                                                    @endphp
                                                                    <a href="/preview-video?path={{ urlencode($finalPath) }}"
                                                                        target="_blank">
                                                                        <div
                                                                            class="overflow-hidden rounded-lg border border-gray-200 shadow hover:shadow-lg transition duration-300">
                                                                            <video controls
                                                                                class="w-full h-48 object-cover rounded cursor-pointer">
                                                                                <source
                                                                                    src="/preview-video?path={{ urlencode($finalPath) }}"
                                                                                    type="video/mp4">
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
        
                                                <div class="tabContent hidden" id="videoFeedbackTab">
                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                        @php
                                                            $feedbackVideo = $media->whereIn('filetype_id', 7);
                                                        @endphp
                                                        @if($feedbackVideo->isEmpty())
                                                            <div class="p-6 bg-gray-100 rounded-xl text-center border border-gray-300">
                                                                <p class="text-gray-600 text-lg font-semibold">No posts found</p>
                                                                <p class="text-gray-500 text-sm">This school has not uploaded any media yet.</p>
                                                            </div>
                                                        @else
                                                            @foreach($feedbackVideo as $m)
                                                                @php
                                                                    $paths = is_array($m->onedrive_path) ? $m->onedrive_path : [$m->onedrive_path];
                                                                    $districtName = $m->school->district->DSM_DSNM ?? '';
                                                                    $schoolName = $m->school->scm_name ?? '';
                                                                    $designation = $m->designation ?? '';
                                                                @endphp
                                                                @foreach($paths as $path)
                                                                    @php
                                                                        $finalPath = is_array($path) ? ($path['url'] ?? $path[0] ?? '') : $path;
                                                                    @endphp
                                                                    <div
                                                                        class="relative group overflow-hidden rounded-lg border border-gray-200 shadow hover:shadow-lg transition duration-300">
                                                                        <a href="/preview-video?path={{ urlencode($finalPath) }}"
                                                                            target="_blank">
                                                                            <video controls
                                                                                class="w-full h-48 object-cover rounded cursor-pointer">
                                                                                <source
                                                                                    src="/preview-video?path={{ urlencode($finalPath) }}"
                                                                                    type="video/mp4">
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                            <div
                                                                                class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 
                                                                                        transition-opacity duration-300 flex flex-col justify-center items-center text-white p-3 text-center">
                                                                                <p class="text-sm font-semibold">{{ $districtName }}</p>
                                                                                <p class="text-xs">{{ $schoolName }}</p>
                                                                                <p class="text-xs">{{ $designation }} - Feedback</p>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-gray-500 py-10">
                                            <i class="fas fa-info-circle text-3xl mb-2"></i>
                                            <p>Please select District & School to view gallery.</p>
                                        </div>
                                    @endif 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // ------------ TAB SWITCHING ------------
        document.querySelectorAll("[data-tab]").forEach(tab => {
            tab.addEventListener("click", () => {
                const selected = tab.getAttribute("data-tab");

                document.querySelectorAll("[data-tab]").forEach(t =>
                    t.classList.remove("activeTab", "text-indigo-600", "border-indigo-600")
                );

                tab.classList.add("activeTab", "text-indigo-600", "border-indigo-600");

                document.querySelectorAll(".tabContent").forEach(tc => tc.classList.add("hidden"));
                document.getElementById(selected).classList.remove("hidden");
            });
        });

    </script>
    <script>
        document.getElementById('district_id').addEventListener('change', function () {
            const districtId = this.value;
            const schoolSelect = document.getElementById('school_id');

            schoolSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/get-schools-by-district-${districtId}`)
                .then(res => res.json())
                .then(data => {
                    schoolSelect.innerHTML = '<option value="">Select School</option>';
                    data.forEach(school => {
                        const option = document.createElement('option');
                        option.value = school.scm_id;
                        option.text = school.scm_name;
                        schoolSelect.appendChild(option);
                    });
                });
        });
    </script>
</body>
@include('components.footer')