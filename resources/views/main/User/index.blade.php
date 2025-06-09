@extends('layout.pemilik')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Daftar User</h2>
        <form action="{{ route('user') }}" method="GET" class="flex items-center">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-3 py-2 text-left">#</th>
                    <th class="px-3 py-2 text-left">Foto</th>
                    <th class="px-3 py-2 text-left">Nama</th>
                    <th class="px-3 py-2 text-left">Email</th>
                    <th class="px-3 py-2 text-left">Role</th>
                    <th class="px-3 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($users as $user)
                <tr>
                    <td class="px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2">
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                            class="w-10 h-10 rounded-full object-cover">
                    </td>

                    <td class="px-3 py-2">{{ $user->name }}</td>
                    <td class="px-3 py-2">{{ $user->email }}</td>
                    <td class="px-3 py-2 text-left align-middle">
                        @if ($user->role == 0)
                        <!-- Ikon Mahkota (King) untuk Pemilik dengan warna kuning -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500 " fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M2.003 5.884L3 14h14l.997-8.116-4.528 3.395a1 1 0 01-1.43-.288L10 4 7.961 9.991a1 1 0 01-1.43.288L2.003 5.884z" />
                        </svg>
                        @elseif ($user->role == 1)
                        <!-- Ikon User untuk Karyawan -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 " fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9.003 9.003 0 0112 15c2.21 0 4.21.805 5.879 2.138M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        @else
                        <!-- Ikon Tanda Tanya untuk Unknown -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 " fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M12 6a4 4 0 00-4 4c0 1.657 1.5 2.5 2.5 3s1.5 1 1.5 2v1m0 4h.01" />
                        </svg>
                        @endif
                    </td>



                    <td class="px-3 py-2 flex space-x-2">
                        <button
                            onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->profile_photo_path }}')"
                            class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></button>

                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit -->
<div id="editUserModal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Edit User</h2>
            <button onclick="closeEditModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST" enctype="multipart/form-data">
            @csrf @method('POST')
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="editUserId" name="user_id">

            <div class="mb-4">
                <label class="block mb-1 font-medium text-sm">Foto Profil</label>
                <div class="flex items-center space-x-3">
                    <img id="editUserImagePreview" src="" class="w-16 h-16 rounded-full object-cover">
                    <input type="file" name="profile_photo_path" id="editUserImageUpload" accept="image/*"
                        onchange="previewImage(event)">
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-sm">Nama</label>
                <input type="text" name="name" id="editUserName" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-sm">Email</label>
                <input type="email" name="email" id="editUserEmail" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-sm">Role</label>
                <select name="role" id="editUserRole" class="w-full border rounded px-3 py-2">
                    <option value="0">Pemilik</option>
                    <option value="1">Karyawan</option>
                </select>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openEditModal(id, name, email, role, imagePath) {
    console.log(id, name, email, role);
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserRole').value = role;

    // set image preview
    const preview = document.getElementById('editUserImagePreview');
    if (imagePath) {
        preview.src = imagePath;
    } else {
        preview.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name);
    }

    // Update form action
    const form = document.getElementById('editUserForm');
    form.action = `/admin/user/update/${id}`;
}

function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}

function previewImage(event) {
    const output = document.getElementById('editUserImagePreview');
    output.src = URL.createObjectURL(event.target.files[0]);
}
</script>
@endsection