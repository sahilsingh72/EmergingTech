@include('components.navbar')
@include('components.sidebar')
<style>
    .tab-btn {
        padding: 10px 20px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        display: flex;
        gap: 8px;
        border-radius: 12px;
    }

    .tab-btn:hover {
        background: rgba(66, 202, 206, 0.7);
        color: #111827;
    }

    .active-tab {
        background: #50d4ef;
        color: #111827;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .tab-wrapper {
        background: #f3f4f6;
        border-radius: 14px;
        padding: 6px;
        display: inline-flex;
        gap: 6px;
        min-width: max-content;
    }
    .tab-scroll {
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }
    .tab-scroll::-webkit-scrollbar {
        display: none;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">
<link rel="stylesheet" href="css/adminlte.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Print & Electronic Media</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Print & Electronic Media</a></li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Print & Electronic Media</h2>
                                    @php
                                        $roleId = Auth::user()->role_id;
                                    @endphp
                                    @if($roleId == 9)
                                        <div class="mb-4 flex justify-end">
                                            <a href="{{route('media.print&Electronic.add')}}"><button
                                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                    <i class="fas fa-add"></i> Add Post
                                                </button>
                                            </a>
                                        </div>
                                    @endif
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
                                    <!-- Tabs -->
                                    <div class="flex justify-center mb-8 px-2">
                                        <div class="tab-scroll w-full md:w-auto">
                                            <div class="tab-wrapper mx-auto">
                                                <button onclick="openTab('print')" class="tab-btn {{ $tab === 'print' ? 'active-tab' : '' }}">
                                                    Print Media
                                                </button>
                                                <button onclick="openTab('electronic')" class="tab-btn {{ $tab === 'electronic' ? 'active-tab' : '' }}">
                                                    Electronic Media
                                                </button>
                                                <button onclick="openTab('digital')" class="tab-btn {{ $tab === 'digital' ? 'active-tab' : '' }}">
                                                    Digital Media
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div id="print" class="tab-content {{ $tab !== 'print' ? 'hidden' : '' }}">
                                        <!-- Print Media Intro -->
                                        <div class="mb-8">
                                            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white shadow-lg">
                                                <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1504711434969-e33886168f5c')] bg-cover bg-center"></div>

                                                <div class="relative p-6 sm:p-8 lg:p-10">
                                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                                        <div>
                                                            <h2 class="text-2xl sm:text-3xl font-bold tracking-wide">
                                                                📰 Print Media Coverage
                                                            </h2>
                                                            <p class="mt-3 text-gray-200 max-w-2xl leading-relaxed">
                                                                A curated collection of newspaper cuttings, magazine features, and printed publications
                                                                highlighting training programs, institutional activities, and public outreach.
                                                            </p>

                                                            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Newspapers
                                                                </span>
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Magazines
                                                                </span>
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Press Coverage
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-6">
                                                            <div class="text-center">
                                                                <p class="text-3xl font-bold">
                                                                    {{ $posts['print']->total() ?? 0 }}
                                                                </p>
                                                                <p class="text-sm text-gray-300">Total Posts</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                            @forelse($posts['print'] ?? [] as $post)
                                                <div class="relative rounded-xl bg-blue-50 overflow-hidden shadow hover:shadow-lg transition-all duration-300 ease-out hover:shadow-2xl hover:-translate-y-1 h-full flex flex-col bg-[#FAFAFA]">
                                                    @php
                                                        $roleId = Auth::user()->role_id;
                                                    @endphp
                                                    @if($roleId == 9)
                                                        <div class="absolute top-3 right-3 z-20 flex gap-1">
                                                            <form action="{{ route('social.media.destroy', $post->id) }}" method="POST" onsubmit="return confirmDelete()">
                                                                @csrf        
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="bg-black/40 backdrop-blur text-blue-800 px-3 py-1 rounded-full shadow-md text-xs font-semibold
                                                                        hover:bg-blue-600 hover:text-white transition">
                                                                    <i class="fas fa-trash"></i>  
                                                                </button>
                                                            </form>
                                                            <button
                                                                onclick="openEditModal({{ $post->toJson() }})"
                                                                class="bg-black/40 backdrop-blur text-blue-800 px-3 py-1 rounded-full shadow-md text-xs font-semibold
                                                                    hover:bg-blue-600 hover:text-white transition">
                                                                <i class="fas fa-edit"></i>  
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('preview.files', [
                                                            'path' => $post->media_path,
                                                            'filename' => $post->title
                                                        ]) }}" target="_blank">
                                                        <img src="{{ route('preview.files', [
                                                            'path' => $post->media_path,
                                                            'filename' => $post->title
                                                        ]) }}"
                                                        class="w-full h-96 object-cover rounded-lg">
                                                    </a>
                                                </div>
                                            @empty
                                                <p class="text-center col-span-4 text-gray-500">No Print Media posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['print']->appends(['tab' => 'print'])->links() }}
                                        </div>
                                    </div>

                                    <div id="electronic" class="tab-content {{ $tab !== 'electronic' ? 'hidden' : '' }}">

                                        <!-- Electronic Media Intro -->
                                        <div class="mb-8">
                                            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 text-white shadow-xl">
                                                <div class="absolute inset-0 opacity-15 bg-[url('https://images.unsplash.com/photo-1518770660439-4636190af475')] bg-cover bg-center"></div>

                                                <div class="relative p-6 sm:p-8 lg:p-10">
                                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                                        <div>
                                                            <h2 class="text-2xl sm:text-3xl font-bold tracking-wide flex items-center gap-2">
                                                                📺 Electronic Media Highlights
                                                            </h2>

                                                            <p class="mt-3 text-blue-100 max-w-2xl leading-relaxed">
                                                                Coverage across television, digital news portals, and online media platforms
                                                                showcasing events, interviews, campaigns, and training initiatives.
                                                            </p>

                                                            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    TV News
                                                                </span>
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Online Media
                                                                </span>
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Video Coverage
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-8">
                                                            <div class="text-center">
                                                                <p class="text-3xl font-bold">
                                                                    {{ $posts['electronic']->total() ?? 0 }}
                                                                </p>
                                                                <p class="text-sm text-blue-100">Total Posts</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                            @forelse($posts['electronic'] ?? [] as $post)
                                                <div class="relative rounded-xl bg-blue-50 overflow-hidden shadow hover:shadow-lg transition-all duration-300 ease-out hover:shadow-2xl hover:-translate-y-1 h-full flex flex-col bg-[#FAFAFA]">
                                                    @php
                                                        $roleId = Auth::user()->role_id;
                                                    @endphp
                                                    @if($roleId == 9)
                                                        <div class="absolute top-3 right-3 z-20 flex gap-1">
                                                            <form action="{{ route('social.media.destroy', $post->id) }}" method="POST" onsubmit="return confirmDelete()">
                                                                @csrf        
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="bg-black/40 backdrop-blur text-blue-800 px-3 py-1 rounded-full shadow-md text-xs font-semibold
                                                                        hover:bg-blue-600 hover:text-white transition">
                                                                    <i class="fas fa-trash"></i>  
                                                                </button>
                                                            </form>
                                                            <button
                                                                onclick="openEditModal({{ $post->toJson() }})"
                                                                class="bg-black/40 backdrop-blur text-blue-800 px-3 py-1 rounded-full shadow-md text-xs font-semibold
                                                                    hover:bg-blue-600 hover:text-white transition">
                                                                <i class="fas fa-edit"></i>  
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('preview.files', [
                                                            'path' => $post->media_path,
                                                            'filename' => $post->title
                                                        ]) }}" target="_blank">
                                                        <img src="{{ route('preview.files', [
                                                            'path' => $post->media_path,
                                                            'filename' => $post->title
                                                        ]) }}"
                                                        class="w-full h-48 object-cover">
                                                    </a>
                                                    <div class="p-4 flex flex-col flex-1">
                                                        <p class="font-semibold text-black">{{ $post->title }}</p>
                                                        <p class="font-regular text-black">{{ Str::limit($post->description, 150) }}</p>

                                                        <div class="mt-auto pt-4">
                                                    
                                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 mt-auto">
                                                                <p class="text-sm text-gray-500"></p>
                                                                <a href="{{ $post->media_link }}" target="_blank"><p class="text-blue-500 hover:text-blue-800 flex items-center gap-1"></i> View Post  <i class="fas fa-external-link-alt text-sm"></i></p></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center text-gray-500">No Electronic media posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['electronic']->appends(['tab' => 'electronic'])->links() }}
                                        </div>
                                    </div>

                                    <div id="digital" class="tab-content {{ $tab !== 'digital' ? 'hidden' : '' }}">
                                        <!-- Digital Media Intro -->
                                        <div class="mb-8">
                                            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-700 text-white shadow-xl">
                                                <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1526378722484-bd91ca387e72')] bg-cover bg-center"></div>

                                                <div class="relative p-6 sm:p-8 lg:p-10">
                                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                                        <div>
                                                            <h2 class="text-2xl sm:text-3xl font-bold tracking-wide flex items-center gap-2">
                                                                🌐 Digital Media Coverage
                                                            </h2>

                                                            <p class="mt-3 text-blue-100 max-w-2xl leading-relaxed">
                                                                Online news articles, web portals, blogs, and digital publications
                                                                highlighting programs, achievements, and institutional initiatives.
                                                            </p>

                                                            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    News Portals
                                                                </span>
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Blogs
                                                                </span>
                                                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                                                    Online Articles
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-8">
                                                            <div class="text-center">
                                                                <p class="text-3xl font-bold">
                                                                    {{ $posts['digital']->total() ?? 0 }}
                                                                </p>
                                                                <p class="text-sm text-blue-100">Total Posts</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            @forelse($posts['digital'] ?? [] as $post)
                                                <div class="relative p-6 bg-blue-50 rounded-xl shadow flex items-center gap-4 hover:shadow-lg transition-all duration-300 ease-out hover:shadow-2xl hover:-translate-y-1">
                                                    @php
                                                        $roleId = Auth::user()->role_id;
                                                    @endphp
                                                    @if($roleId == 9)
                                                        <div class="absolute top-3 right-3 z-20 flex gap-1">
                                                            <form action="{{ route('social.media.destroy', $post->id) }}" method="POST" onsubmit="return confirmDelete()">
                                                                @csrf        
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="bg-black/40 backdrop-blur text-blue-800 px-3 py-1 rounded-full shadow-md text-xs font-semibold
                                                                        hover:bg-blue-600 hover:text-white transition">
                                                                    <i class="fas fa-trash"></i>  
                                                                </button>
                                                            </form>
                                                            <button
                                                                onclick="openEditModal({{ $post->toJson() }})"
                                                                class="bg-black/40 backdrop-blur text-blue-800 px-3 py-1 rounded-full shadow-md text-xs font-semibold
                                                                    hover:bg-blue-600 hover:text-white transition">
                                                                <i class="fas fa-edit"></i>  
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('preview.files', [
                                                            'path' => $post->media_path,
                                                            'filename' => $post->title
                                                        ]) }}" target="_blank">
                                                        <img
                                                            src="{{ route('preview.files', [
                                                                'path' => $post->media_path,
                                                                'filename' => $post->title
                                                            ]) }}"
                                                            class="w-40 h-40 object-cover rounded-lg">
                                                    </a>
                                                    <div class="p-4 flex flex-col flex-1">
                                                        <h3 class="font-semibold text-lg">{{ $post->title }}</h3>
                                                        <p class="text-gray-700 font-medium mt-2">{{ Str::limit($post->description, 350) }}</p>

                                                        <div class="mt-auto pt-4">
                                                            
                                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 mt-auto">
                                                                <p class="text-sm text-gray-500"><strong>Publish Date: </strong>{{ $post->training_date 
                                                                    ? \Carbon\Carbon::parse($post->training_date)->format('d-m-Y')
                                                                    : 'N/A'
                                                                }}</p>
                                                                <a href="{{ $post->media_link }}" target="_blank"><p class="text-blue-500 hover:text-blue-800 flex items-center gap-1"></i> View Post  <i class="fas fa-external-link-alt text-sm"></i></p></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center text-gray-500">No Digital Media posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['digital']->appends(['tab' => 'digital'])->links() }}
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
    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-5xl max-h-[90vh] overflow-hidden shadow-xl relative">

            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-xl font-semibold">Edit Social Media Post</h3>
                <button onclick="closeEditModal()"
                        class="text-gray-600 hover:text-black text-2xl leading-none">
                    ×
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">


                <form id="editForm" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <input type="hidden" id="edit_id">

                    <!-- Platform -->
                    <div>
                        <label class="font-semibold text-sm">Platform</label>
                        <select name="platform" id="edit_platform" class="w-full rounded-lg border-gray-300">
                            <option value="print">Print Media</option>
                            <option value="electronic">Electronic Media</option>
                            <option value="digital">Digital Media</option>
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Publish Date</label>
                        <input type="date" name="publish_date" id="edit_publish_date" class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="md:col-span-2">
                        <label class="font-semibold text-sm">Title</label>  
                        <input type="text" name="title" id="edit_title" class="w-full rounded-lg border-gray-300" placeholder="Title">
                    </div>

                    <div class="md:col-span-2">
                        <label class="font-semibold text-sm">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full rounded-lg border-gray-300" placeholder="Description"></textarea>
                    </div>
                    
                    <!-- Existing Image -->
                    <div>
                        <label class="font-semibold text-sm">Current Image</label>
                        <img id="edit_preview" class="mt-2 w-full max-h-48 object-cover rounded-lg border">
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Replace Image (optional)</label>
                        <input type="file" name="image" class="w-full rounded-lg border-gray-300">
                    </div>
                    <div class="md:col-span-2">
                        <label class="font-semibold text-sm">Media URL Link (Only for youTube)</label>
                        <input type="text" name="media_link" id="edit_media_link" class="w-full rounded-lg border-gray-300" placeholder="Social media URL">
                    </div>
                    <div class="md:col-span-2 flex justify-end pt-4">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this post? This action cannot be undone.");
}
</script>
<script>
    function openTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active-tab');
        });

        document.getElementById(tabId).classList.remove('hidden');
        event.target.classList.add('active-tab');
    }
</script>
<script>
function openEditModal(post) {

    document.getElementById('editForm').action =
        `/media-print&Electronic/${post.id}/update`;

    document.getElementById('edit_platform').value = post.media_type;
    document.getElementById('edit_publish_date').value = post.training_date;
    document.getElementById('edit_title').value = post.title;
    document.getElementById('edit_description').value = post.description;
    document.getElementById('edit_media_link').value = post.media_link;

    document.getElementById('edit_preview').src =
        `/preview-files?path=${encodeURIComponent(post.media_path)}&filename=${encodeURIComponent(post.title)}`;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>

@include('components.footer')