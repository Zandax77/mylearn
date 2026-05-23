<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex flex-col gap-1">
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                    Manajemen User
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kelola akses guru dan siswa</p>
            </div>
            
            <div class="flex items-center p-1.5 bg-gray-100/50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request('role') ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                    Semua
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" 
                   class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('role') == 'guru' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                    Guru
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" 
                   class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('role') == 'siswa' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                    Siswa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- PREMIUM FLOATING ALERT -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="fixed top-24 right-8 z-[100] transform transition-all duration-500 ease-in-out"
                 x-transition:enter="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="translate-x-full opacity-0">
                <div class="bg-white dark:bg-gray-800 border-l-4 border-emerald-500 shadow-2xl rounded-2xl p-5 flex items-center min-w-[350px]">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center shrink-0 mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="mr-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Berhasil</p>
                        <p class="text-[13px] font-bold text-gray-900 dark:text-white leading-tight">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-auto p-2 text-gray-300 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- SEARCH BOX -->
        <div class="flex justify-center">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-2 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <div class="pl-6 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="flex-1 bg-transparent border-none focus:ring-0 text-sm px-6 py-5 outline-none placeholder:text-gray-400 dark:text-white" 
                           style="border: none !important; box-shadow: none !important;"
                           placeholder="Cari nama atau email user..."
                           autocomplete="off">
                    <button type="submit" class="bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.1em] px-12 py-5 rounded-[2rem] hover:bg-indigo-700 transition-all shrink-0 shadow-lg shadow-indigo-100 flex items-center justify-center">
                        Cari User
                    </button>
                </form>
            </div>
        </div>

        <!-- USER TABLE -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi User</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Role</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl {{ $user->is_active ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center font-black text-sm shadow-sm group-hover:scale-110 transition-transform">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ $user->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight mt-1">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg {{ $user->role == 'guru' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-700 uppercase tracking-widest">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black bg-rose-100 text-rose-700 uppercase tracking-widest">
                                            Terblokir
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-2 transition-all">
                                        <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Reset password user ini menjadi: password123?')">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Reset Password">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                            @csrf
                                            @if($user->is_active)
                                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Blokir Akun">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                </button>
                                            @else
                                                <button type="submit" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Aktifkan Akun">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tidak ada user ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-8 py-5 border-t border-gray-50 dark:border-gray-700 bg-gray-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
