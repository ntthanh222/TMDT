@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Quản lý người dùng</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Danh sách tài khoản</h1>
        </div>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm theo tên hoặc email..." class="rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 flex-1">
        
        <select name="role" class="rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tất cả vai trò</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Khách hàng</option>
        </select>

        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Lọc</button>
        @if(request('keyword') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 text-center">Xóa lọc</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Tên</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Vai trò</th>
                    <th class="px-6 py-3">Trạng thái</th>
                    <th class="px-6 py-3">Ngày tạo</th>
                    <th class="px-6 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ $user->id }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $user->is_active ? 'Hoạt động' : 'Tạm khóa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-900">Sửa</a>
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-900">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Không tìm thấy người dùng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
