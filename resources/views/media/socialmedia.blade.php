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

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Social Media</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Social Media</a></li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Social Media</h2>
                                    @php
                                        $roleId = Auth::user()->role_id;
                                    @endphp
                                    @if($roleId == 9)
                                        <div class="mb-4 flex justify-end">
                                            <a href="{{route('social.media.add')}}"><button
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
                                                <button onclick="openTab('instagram')" class="tab-btn {{ $tab === 'instagram' ? 'active-tab' : '' }}">
                                                    Instagram
                                                </button>
                                                <button onclick="openTab('facebook')" class="tab-btn {{ $tab === 'facebook' ? 'active-tab' : '' }}">
                                                    Facebook
                                                </button>
                                                <button onclick="openTab('twitter')" class="tab-btn {{ $tab === 'twitter' ? 'active-tab' : '' }}">
                                                    Twitter
                                                </button>
                                                <button onclick="openTab('linkedin')" class="tab-btn {{ $tab === 'linkedin' ? 'active-tab' : '' }}">
                                                    LinkedIn
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Search -->
                                    <div class="mb-6 flex justify-center md:justify-end px-2">
                                        <div class="relative w-full md:w-72">
                                            <input
                                                type="text"
                                                id="socialSearch"
                                                placeholder="Search by school, district, title..."
                                                class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2
                                                    focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                                onkeyup="filterPosts()"
                                            >
                                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        </div>
                                    </div>

                                    <div id="instagram" class="tab-content {{ $tab !== 'instagram' ? 'hidden' : '' }}">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                            @forelse($posts['instagram'] ?? [] as $post)
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
                                                        <p class="font-regular text-black">{{ Str::limit($post->description, 100) }}</p>

                                                        <div class="mt-auto pt-4">
                                                            <p class="font-medium text-blue-600 flex items-center gap-1"><i class="fas fa-map-marker-alt text-blue-600"> </i> {{ $post->district_name }}</p>
                                                            <p class="text-sm text-black-600 pl-3">{{ $post->school_name }}</p>
                                                        
                                                        
                                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 mt-auto">
                                                                <p class="text-sm text-gray-500"><strong>Training Date: </strong>{{ $post->training_date 
                                                                    ? \Carbon\Carbon::parse($post->training_date)->format('d-m-Y')
                                                                    : 'N/A'
                                                                }}</p>
                                                                <a href="{{ $post->media_link }}" target="_blank"><p class="text-blue-500 hover:text-blue-800 flex items-center gap-1"></i> View Post  <i class="fas fa-external-link-alt text-sm"></i></p></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center col-span-4 text-gray-500">No Instagram posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['instagram']->appends(['tab' => 'instagram'])->links() }}
                                        </div>
                                    </div>

                                    <div id="facebook" class="tab-content {{ $tab !== 'facebook' ? 'hidden' : '' }}">
                                        <div class="space-y-6">
                                            @forelse($posts['facebook'] ?? [] as $post)
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
                                                            <p class="font-medium text-blue-600 flex items-center gap-1"><i class="fas fa-map-marker-alt text-blue-600"> </i> {{ $post->district_name }}</p>
                                                            <p class="text-sm text-black-600 pl-3">{{ $post->school_name }}</p>
                                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 mt-auto">
                                                                <p class="text-sm text-gray-500"><strong>Training Date: </strong>{{ $post->training_date 
                                                                    ? \Carbon\Carbon::parse($post->training_date)->format('d-m-Y')
                                                                    : 'N/A'
                                                                }}</p>
                                                                <a href="{{ $post->media_link }}" target="_blank"><p class="text-blue-500 hover:text-blue-800 flex items-center gap-1"></i> View Post  <i class="fas fa-external-link-alt text-sm"></i></p></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center text-gray-500">No Facebook posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['facebook']->appends(['tab' => 'facebook'])->links() }}
                                        </div>
                                    </div>

                                    <div id="twitter" class="tab-content {{ $tab !== 'twitter' ? 'hidden' : '' }}">
                                        <div class="space-y-4">
                                            @forelse($posts['twitter'] ?? [] as $post)
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
                                                            <p class="font-medium text-blue-600 flex items-center gap-1"><i class="fas fa-map-marker-alt text-blue-600"> </i> {{ $post->district_name }}</p>
                                                            <p class="text-sm text-black-600 pl-3">{{ $post->school_name }}</p>
                                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 mt-auto">
                                                                <p class="text-sm text-gray-500"><strong>Training Date: </strong>{{ $post->training_date 
                                                                    ? \Carbon\Carbon::parse($post->training_date)->format('d-m-Y')
                                                                    : 'N/A'
                                                                }}</p>
                                                                <a href="{{ $post->media_link }}" target="_blank"><p class="text-blue-500 hover:text-blue-800 flex items-center gap-1"></i> View Post  <i class="fas fa-external-link-alt text-sm"></i></p></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center text-gray-500">No Twitter posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['twitter']->appends(['tab' => 'twitter'])->links() }}
                                        </div>
                                    </div>

                                    <div id="linkedin" class="tab-content {{ $tab !== 'linkedin' ? 'hidden' : '' }}">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                           @forelse($posts['linkedin'] ?? [] as $post)
                                                <div class="relative p-6 bg-gray-100 rounded-xl shadow hover:shadow-lg transition-all duration-300 ease-out hover:shadow-2xl hover:-translate-y-1">
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
                                                            class="w-full h-48 object-cover rounded-lg mb-4"
                                                        >
                                                    </a>
                                                    <h3 class="font-semibold text-lg">{{ $post->title }}</h3>
                                                    <p class="font-medium text-blue-600 flex items-center gap-1"><i class="fas fa-map-marker-alt text-blue-600"> </i> {{ $post->district_name }}</p>
                                                    <p class="text-sm text-black-600 pl-3">{{ $post->school_name }}</p>

                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 mt-auto">
                                                        <p class="text-sm text-gray-500"><strong>Training Date: </strong>{{ $post->training_date 
                                                            ? \Carbon\Carbon::parse($post->training_date)->format('d-m-Y')
                                                            : 'N/A'
                                                        }}</p>
                                                        <a href="{{ $post->media_link }}" target="_blank"><p class="text-blue-500 hover:text-blue-800 flex items-center gap-1"></i> View Post  <i class="fas fa-external-link-alt text-sm"></i></p></a>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center text-gray-500 col-span-2">No LinkedIn posts found.</p>
                                            @endforelse
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            {{ $posts['linkedin']->appends(['tab' => 'linkedin'])->links() }}
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
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">Twitter</option>
                            <option value="linkedin">LinkedIn</option>
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold text-sm">District</label>
                        <input type="text" name="district_name" id="edit_district" class="w-full rounded-lg border-gray-300" placeholder="District">
                    </div>
                    <div>
                        <label class="font-semibold text-sm">School Name</label>
                        <input type="text" name="school_name" id="edit_school" class="w-full rounded-lg border-gray-300" placeholder="School">
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Training Date</label>
                        <input type="date" name="training_date" id="edit_training_date" class="w-full rounded-lg border-gray-300">
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
                        <label class="font-semibold text-sm">Social Media Link</label>
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
        `/social-media/${post.id}/update`;

    document.getElementById('edit_platform').value = post.media_type;
    document.getElementById('edit_district').value = post.district_name;
    document.getElementById('edit_school').value = post.school_name;
    document.getElementById('edit_training_date').value = post.training_date;
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
<script>
function changeTab(tab) {
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.location = url;
}
</script>
<script>
function filterPosts() {
    const query = document.getElementById('socialSearch').value.toLowerCase();

    document.querySelectorAll('.tab-content:not(.hidden) > div > div').forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(query) ? '' : 'none';
    });
}
</script>


@include('components.footer')