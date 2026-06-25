@extends('layouts.admin')
@section('title', 'Portfolio')
@section('page-title', 'Kelola Portfolio')
@section('page-subtitle', 'Galeri foto hasil kerja Rias Pesta Pekanbaru')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">Total: <span class="font-semibold text-gray-800">{{ $portfolios->total() }}</span> foto</p>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-rose-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-rose-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Foto
    </button>
</div>

{{-- Grid Foto --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($portfolios as $item)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
        <div class="relative h-48 overflow-hidden">
            <img src="{{ asset('storage/'.$item->image) }}"
                 alt="{{ $item->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300 flex items-center justify-center">
                <div class="hidden group-hover:flex gap-2">
                    <a href="{{ asset('storage/'.$item->image) }}" target="_blank"
                       class="w-9 h-9 bg-white rounded-full flex items-center justify-center text-gray-700 hover:bg-rose-50 transition">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.portfolios.destroy', $item) }}">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus foto ini?')"
                                class="w-9 h-9 bg-white rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            {{-- Badge Featured --}}
            @if($item->is_featured)
            <div class="absolute top-2 left-2">
                <span class="text-xs bg-yellow-400 text-yellow-900 px-2 py-0.5 rounded-full font-semibold">
                    <i class="fas fa-star mr-1"></i>Unggulan
                </span>
            </div>
            @endif
        </div>
        <div class="p-3">
            <p class="font-medium text-sm text-gray-800 truncate">{{ $item->title }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ $item->category }}</p>
        </div>
    </div>
    @empty
    <div class="col-span-4 bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
        <i class="fas fa-images text-5xl mb-4 block"></i>
        <p class="font-medium">Belum ada foto portfolio</p>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="mt-3 text-rose-600 text-sm hover:underline">
            + Tambah foto pertama
        </button>
    </div>
    @endforelse
</div>

@if($portfolios->hasPages())
<div class="mt-6">{{ $portfolios->links() }}</div>
@endif

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Tambah Foto Portfolio</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.portfolios.store') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Foto <span class="text-red-500">*</span></label>
                <input type="text" name="title" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                       placeholder="contoh: Dekorasi Pelaminan Silver">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    <option value="dekorasi">Dekorasi</option>
                    <option value="rias">Rias Pengantin</option>
                    <option value="tenda">Tenda</option>
                    <option value="akad">Akad Nikah</option>
                    <option value="resepsi">Resepsi</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto <span class="text-red-500">*</span></label>
                <input type="file" name="image" accept="image/*" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none"
                       onchange="previewPortfolio(this)">
                <img id="previewPortfolio" src="" alt="" class="hidden mt-3 h-40 w-full rounded-xl object-cover border border-gray-200">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-300">
                <label for="is_featured" class="text-sm text-gray-700">Tampilkan sebagai unggulan di beranda</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-rose-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-rose-700 transition">
                    <i class="fas fa-upload mr-2"></i>Upload Foto
                </button>
                <button type="button"
                        onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewPortfolio(input) {
    const preview = document.getElementById('previewPortfolio');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush