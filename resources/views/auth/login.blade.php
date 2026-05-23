<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-white" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="text-center mb-8">
            <h2 class="text-xl font-medium text-white/80">Silakan login untuk melanjutkan</h2>
        </div>

        <!-- Email Address -->
        <div class="mb-5 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-white/60 group-focus-within:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
            </div>
            <input id="email" class="block w-full pl-10 pr-3 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent focus:bg-white/10 transition-all duration-300 sm:text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-300" />
        </div>

        <!-- Password -->
        <div class="mb-6 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-white/60 group-focus-within:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <input id="password" class="block w-full pl-10 pr-3 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent focus:bg-white/10 transition-all duration-300 sm:text-sm" type="password" name="password" required autocomplete="current-password" placeholder="Password Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-300" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="h-4 w-4 text-indigo-500 focus:ring-indigo-400 border-white/30 bg-white/10 rounded cursor-pointer" name="remember">
                <label for="remember_me" class="ml-2 block text-sm text-white/80 cursor-pointer select-none">
                    {{ __('Ingat Saya') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-300 hover:text-white transition-colors">
                    Lupa Password?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-indigo-900 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transform transition-all duration-200 hover:-translate-y-1 hover:shadow-indigo-500/50">
                MASUK SEKARANG
            </button>
        </div>
    </form>
</x-guest-layout>
