@extends('layouts.app')

@section('vendor-style')
<style>
    .form-row { display: flex; gap: 20px; }
    .form-col { flex: 1; display: flex; flex-direction: column; gap: 0.25rem; }
</style>
@endsection

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
      <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
      <div class="relative flex items-center justify-between px-5 mt-[60px]">
        <a href="{{ route('login') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
          <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
        </a>
        <p class="font-semibold">Create Account</p>
        <div class="w-12 dummy-btn"></div>
      </div>
      <div style="position: relative; display: flex; flex-direction: column; padding-left: 1.25rem; padding-right: 1.25rem; margin-top: 2rem">
        <h1 style="font-size: 1.5rem; line-height: 2rem; font-weight: 700">Sign Up</h1>
        <p style="margin-top: 0.5rem; color: #6b7280">Fill in the form below to create an account</p>
      </div>
      <form method="POST" action="{{ route('register') }}" class="relative flex flex-col gap-5 px-5 mt-8">
        @csrf
        @if ($errors->any())
          <div class="text-red-500 text-sm">{{ $errors->first() }}</div>
        @endif
        <div style="display: flex; flex-direction: column; gap: 0.25rem">
          <label for="fullname" class="text-sm font-medium">Full Name</label>
          <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required />
        </div>
        <div class="form-row">
          <div class="form-col">
            <label for="email" class="text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required />
          </div>
          <div class="form-col">
            <label for="phone" class="text-sm font-medium">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-col">
            <label for="password" class="text-sm font-medium">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required />
          </div>
          <div class="form-col">
            <label for="password_confirmation" class="text-sm font-medium">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-col">
            <label for="role" class="text-sm font-medium">Daftar Sebagai</label>
            <select id="role" name="role" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" required>
              <option value="penyewa">Penyewa</option>
              <option value="pemilik">Pemilik</option>
            </select>
          </div>
        </div>
        <button type="submit" class="w-full p-4 mt-4 font-bold text-white rounded-full" style="background-color: #ff801a;">Create Account</button>
        <div class="flex items-center my-4" style="gap: 12px">
          <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
          <p style="font-size: 0.875rem; line-height: 1.25rem; color: #9ca3af; position: relative; padding: 0 8px">OR</p>
          <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
        </div>
        <button type="button" style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 12px; padding: 16px; font-weight: 500; border: 1px solid #e6a43b; border-radius: 9999px; background-color: transparent">
          <img src="{{ asset('assets/images/icons/google.svg') }}" style="width: 20px; height: 20px" alt="google icon" />
          Sign Up with Google
        </button>
        <p style="margin-top: 1rem; margin-bottom: 2rem; font-size: 0.875rem; line-height: 1.25rem; text-align: center">
          Already have an account?
          <a href="{{ route('login') }}" style="font-weight: 500; color: #f97316">Login</a>
        </p>
      </form>
    </div>
@endsection
