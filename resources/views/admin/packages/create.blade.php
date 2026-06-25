@extends('layouts.admin')
@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket Layanan')
@section('page-subtitle', 'Buat paket layanan baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Paket <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300 @error('name') border-red-400 @enderror"
                       placeholder="contoh: Paket Silver">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="dekorasi"  {{ old('category')=='dekorasi'  ? 'selected' : '' }}>Dekorasi</option>
                    <option value="rias"      {{ old('category')=='rias'      ? 'selected' : '' }}>Rias Pengantin</option>
                    <option value="paket"     {{ old('category')=='paket'     ? 'selected' : '' }}>Paket Lengkap</option>
                    <option value="catering"  {{ old('category')=='catering'  ? 'selected' : '' }}>Catering</option>
                    <option value="lainnya"   {{ old('category')=='lainnya'   ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price') }}" required min="0"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                       placeholder="contoh: 5000000">
                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                          placeholder="Jelaskan apa saja yang termasuk dalam paket ini...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Paket</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"
                       onchange="previewImage(this)">
                <img id="preview" src="" alt="" class="hidden mt-3 h-40 rounded-xl object-cover border border-gray-200">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', '1') ? 'checked' : '' }}
                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-300">
                <label for="is_active" class="text-sm text-gray-700">Paket aktif (tampil di halaman publik)</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-rose-600 text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:bg-rose-700 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Paket
                </button>
                <a href="{{ route('admin.packages.index') }}"
                   class="border border-gray-200 text-gray-600 px-8 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
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