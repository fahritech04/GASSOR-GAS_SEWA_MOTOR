@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="relative px-5" style="margin-top: 100px">
        <div class="flex flex-col items-center">
          <h1 class="text-3xl font-bold text-center">Welcome Back</h1>
          <p class="mt-2 text-center text-gassor-grey">Please enter your account details</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5 mt-8">
            @csrf
            @if ($errors->any())
                <div class="text-red-500 text-sm">{{ $errors->first() }}</div>
            @endif
            <div class="flex flex-col gap-1">
                <label for="email" class="text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required />
            </div>
            <div class="flex flex-col gap-1">
                <label for="password" class="text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required />
            </div>
            <div class="flex justify-end">
                {{-- <a href="{{ route('password.request') }}" class="text-sm text-gassor-grey">Forgot Password?</a> --}}
            </div>
            <button type="submit" class="w-full p-4 mt-4 font-bold text-white rounded-full" style="background-color: #ff801a;">Login</button>
            <div class="flex items-center my-4" style="gap: 12px">
                <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
                <p style="font-size: 0.875rem; line-height: 1.25rem; color: #9ca3af; position: relative; padding: 0 8px">OR</p>
                <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
            </div>
            <button type="button" style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 12px; padding: 16px; font-weight: 500; border: 1px solid #e6a43b; border-radius: 9999px; background-color: transparent">
                <img src="{{ asset('assets/images/icons/google.svg') }}" style="width: 20px; height: 20px" alt="google icon" />
                Login with Google
            </button>
            <p class="mt-4 text-sm text-center">
                Don't have an account?
                <a href="{{ route('register') }}" style="font-medium; color: #f97316">Register</a>
            </p>
        </form>
    </div>
@endsection
