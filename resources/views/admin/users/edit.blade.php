@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Quản lý người dùng</p>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">Chỉnh sửa tài khoản</h1>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                <input type="text" value="{{ $user->name }}" disabled class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="text" value="{{ $user->email }}" disabled class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 text-sm">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Vai trò</label>
                <select name="role" id="role" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Khách hàng (Customer)</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Tài khoản hoạt động</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Hủy</a>
                <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection
