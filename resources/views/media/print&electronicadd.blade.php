@include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Add Print & Electronic Media</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Add Print & Electronic Media</a></li>
                                <li class="breadcrumb-item active">Media</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content relative">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-8xl mx-auto space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <h2 class="text-2xl font-semibold text-center mb-6">Add Print & Electronic Media</h2>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <p class="bg-green-500 text-white p-2 rounded mb-3">
                                            {{ session('success') }}
                                        </p>
                                    @endif
                                    <form action="{{ route('media.print&Electronic.store') }}" method="POST" enctype="multipart/form-data"
                                        class="max-w-4xl mx-auto space-y-6">
                                        @csrf

                                        <!-- Post Type -->
                                        <div>
                                            <label class="block font-semibold mb-1">Media Platform</label>
                                            <select name="platform" required
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">-- Select Platform --</option>
                                                <option value="print">Print Media</option>
                                                <option value="electronic">Electronic Media (YouTube)</option>
                                                <option value="digital">Digital Media</option>
                                            </select>
                                        </div>

                                        <!-- Training Date -->
                                        <div>
                                            <label class="block font-semibold mb-1">Date of Publish</label>
                                            <input type="date" name="publish_date" required
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        <!-- Title -->
                                        <div>
                                            <label class="block font-semibold mb-1">Post Title</label>
                                            <input type="text" name="title" required
                                                placeholder="Enter post title"
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label class="block font-semibold mb-1">Post Description</label>
                                            <textarea name="description" rows="4" required
                                                placeholder="Write post description here..."
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                        </div>

                                        <!-- Upload Image -->
                                        <div>
                                            <label class="block font-semibold mb-1">Upload Image</label>
                                            <input type="file" name="image" accept="image/*" required
                                                class="w-full rounded-lg border border-gray-300 p-2">
                                        </div>

                                        <!-- media link -->
                                        <div>
                                            <label class="block font-semibold mb-1">Media URL Link (Only for youTube)</label>
                                            <input type="text" name="media_link"
                                                placeholder="Enter post url"
                                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="text-center pt-4">
                                            <button type="submit"
                                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                                Save Post
                                            </button>
                                        </div>

                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>


@include('components.footer')