@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="flex items-center justify-center min-h-screen relative px-5">
        <div class="w-full max-w-md">
            <div class="flex flex-col items-center">
                <h2 class="text-3xl font-bold text-center">Lupa Password</h2>
                <p class="mt-2 text-center text-gassor-grey">Masukkan email Anda untuk menerima link reset password
                </p>
            </div>
            @if (session('status'))
                <div class="mb-4 font-medium text-green-600 text-center">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}"
                class="flex flex-col gap-5 mt-6">
                @csrf
                <div class="flex flex-col gap-1">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                        style="border-radius: 12px;" />
                    @error('email')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full p-4 mt-4 font-bold text-white"
                    style="background-color: #ff801a; border-radius: 12px;">Kirim Link Reset Password</button>
                <a href="{{ route('home') }}"
                   class="w-full p-4 mt-2 font-bold text-center text-white block"
                   style="background-color: #000000; border-radius: 12px; text-decoration: none;">Batalkan</a>
            </form>
        </div>
    </div>
@endsection
