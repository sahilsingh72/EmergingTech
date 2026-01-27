@include('components.navbar')
@include('components.sidebar')

<style>
    .tab-btn {
        padding: 0.6rem 1.4rem;
        font-weight: 600;
        border-radius: 9999px;
        /* pill shape */
        cursor: pointer;
        color: #6b7280;
        /* gray-500 */
        transition: all 0.25s ease;
        border: 1px solid transparent;
        background-color: #f3f4f6;
        /* gray-100 */
    }

    .tab-btn:hover {
        background-color: #e5e7eb;
        /* gray-200 */
        color: #4f46e5;
        /* indigo-600 */
    }

    .tab-btn.activeTab {
        background-color: #eef2ff;
        /* indigo-50 */
        color: #4f46e5;
        /* indigo-600 */
        border-color: #c7d2fe;
        /* indigo-200 */
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.15);
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Feedback View</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Feedback</a></li>
                                <li class="breadcrumb-item active">Feedback View</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid py-12">
                    <div class="max-w-8xl mx-auto space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Feedback View</h2>
                                <div>
                                    <form method="GET" class="mb-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>District</label>
                                                <select name="district_id" id="district_id" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">Select District</option>
                                                    @foreach($districts as $d)
                                                        <option value="{{ $d->DSM_DSCD }}" {{ $districtId == $d->DSM_DSCD ? 'selected' : '' }}>
                                                            {{ $d->DSM_DSNM }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>School</label>
                                                <select name="school_id" id="school_id" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">Select School</option>
                                                    @foreach($schools as $s)
                                                        <option value="{{ $s->scm_id }}" {{ $schoolId == $s->scm_id ? 'selected' : '' }}>
                                                            {{ $s->scm_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div>
                                    @if(!$districtId || !$schoolId)
                                        <div class="flex flex-col items-center justify-center py-16 bg-gray-50 rounded-xl border border-dashed">
                                            <div class="text-indigo-600 text-4xl mb-3">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            <p class="text-lg font-semibold text-gray-700">
                                                Select District & School
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1 text-center max-w-md">
                                                Please select a district and school from the dropdowns above to view feedback files.
                                            </p>
                                        </div>
                                    @endif

                                    @if($schoolId)
                                        <!-- Tabs -->
                                        <div class="flex flex-wrap gap-3 mb-6 bg-gray-100 p-2 rounded-xl">
                                            <button class="tab-btn activeTab" data-tab="studentFeedbackTab">
                                                Student Feedback
                                            </button>

                                            <button class="tab-btn" data-tab="InstituteFeedbackTab">
                                                Institute Feedback
                                            </button>

                                        </div>

                                        <!-- School Feedback -->
                                        <div id="studentFeedbackTab" class="tabContent">
                                            @if($studentFeedbackFiles->isEmpty())
                                                <div class="text-center py-12 bg-gray-50 rounded-lg border">
                                                    <p class="text-gray-500 font-medium">No student feedback uploaded.</p>
                                                </div>
                                            @else
                                                <div class="space-y-3">
                                                    @foreach($studentFeedbackFiles as $file)
                                                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 p-4 border rounded-lg hover:shadow transition">
                                                            <div class="flex items-center gap-3">
                                                                <i class="fas fa-file-alt text-red-500 text-xl"></i>
                                                                <span class="font-medium text-gray-700">
                                                                    {{ $file->file_name }}
                                                                </span>
                                                            </div>

                                                            <div class="flex gap-2 flex-wrap">
                                                                <a target="_blank"
                                                                    href="{{ route('preview.files', [
                                                                            'path' => $file->onedrive_path,
                                                                            'filename' => $file->school->scm_name . '_' . $file->file_type
                                                                    ]) }}"
                                                                    class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                                                    Preview
                                                                </a>
                                                                <a href="{{ route('download.file', ['path' => $file->onedrive_path,
                                                                    'filename' => $file->school->scm_name . '_' . $file->file_type
                                                                    ]) }}"
                                                                    class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                                    Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Institute Feedback -->
                                        <div id="InstituteFeedbackTab" class="tabContent hidden">
                                            @if($institutefeedbackFiles->isEmpty())
                                                <div class="text-center py-12 bg-gray-50 rounded-lg border">
                                                    <p class="text-gray-500 font-medium">No institute feedback uploaded.</p>
                                                </div>
                                            @else
                                                <div class="space-y-3">
                                                    @foreach($institutefeedbackFiles as $file)
                                                        <div
                                                            class="flex justify-between items-center p-4 border rounded-lg hover:shadow transition">
                                                            <div class="flex items-center gap-3">
                                                                <i class="fas fa-file-alt text-red-500 text-xl"></i>
                                                                <span class="font-medium text-gray-700">
                                                                    {{ $file->file_name }}
                                                                </span>
                                                            </div>

                                                            <div class="flex gap-2">
                                                                <a target="_blank"
                                                                    href="{{ route('preview.files', ['path' => $file->onedrive_path,
                                                                    'filename' => $file->school->scm_name . '_' . $file->file_type
                                                                    ]) }}"
                                                                    class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                                                    Preview
                                                                </a>
                                                                <a href="{{ route('download.file', ['path' => $file->onedrive_path,
                                                                    'filename' => $file->school->scm_name . '_' . $file->file_type]) }}"
                                                                    class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                                    Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
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
        document.querySelectorAll('[data-tab]').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('[data-tab]').forEach(t => t.classList.remove('activeTab'));
                tab.classList.add('activeTab');

                document.querySelectorAll('.tabContent').forEach(c => c.classList.add('hidden'));
                document.getElementById(tab.dataset.tab).classList.remove('hidden');
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const firstTab = document.querySelector('[data-tab="studentFeedbackTab"]');
            if (firstTab) firstTab.click();
        });
    </script>

</body>
@include('components.footer')