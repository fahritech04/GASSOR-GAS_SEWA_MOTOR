@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 430px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
<div class="relative flex flex-col gap-[30px] my-[60px] px-5">
    <h1 class="font-bold text-[30px] leading-[45px] text-center">Jelajahi Motor<br>Di Webgassor Kami</h1>
    <form action="{{ route('find-motor.results') }}"
        class="flex flex-col rounded-[30px] border border-[#F1F2F6] p-5 gap-6 bg-white">
        <div id="InputContainer" class="flex flex-col gap-[18px]">
            <div class="flex flex-col w-full gap-2">
                <p class="font-semibold">Nama</p>
                <label
                    class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white ring-1 ring-[#F1F2F6] focus-within:ring-[#E6A43B] transition-all duration-300">
                    <img src="assets/images/icons/note-favorite-grey.svg" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <input type="text" name="search" id=""
                        class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                        placeholder="Ketik nama motor">
                </label>
            </div>
            <div class="flex flex-col w-full gap-2">
                <p class="font-semibold">Pilih Wilayah</p>
                <label
                    class="relative flex items-center w-full rounded-full p-[14px_20px] gap-2 bg-white ring-1 ring-[#F1F2F6] focus-within:ring-[#E6A43B] transition-all duration-300">
                    <img src="assets/images/icons/location.svg"
                        class="absolute w-5 h-5 flex shrink-0 transform -translate-y-1/2 top-1/2 left-5"
                        alt="icon">
                    <select name="city" id="" class="appearance-none outline-none w-full bg-white pl-8">
                        <option value="" hidden>Pilih wilayah</option>
                        @foreach ($cities as $city)
                        <option value="{{ $city->slug }}">{{ $city->name}}</option>
                        @endforeach
                    </select>
                    <img src="assets/images/icons/arrow-down.svg" class="w-5 h-5" alt="icon">
                </label>
            </div>
            <div class="flex flex-col w-full gap-2">
                <p class="font-semibold">Pilih Kategori</p>
                <label
                    class="relative flex items-center w-full rounded-full p-[14px_20px] gap-2 bg-white ring-1 ring-[#F1F2F6] focus-within:ring-[#E6A43B] transition-all duration-300">
                    <img src="assets/images/icons/location.svg"
                        class="absolute w-5 h-5 flex shrink-0 transform -translate-y-1/2 top-1/2 left-5"
                        alt="icon">
                    <select name="category" id="" class="appearance-none outline-none w-full bg-white pl-8">
                        <option value="" hidden>Pilih kategori</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name}}</option>
                        @endforeach
                    </select>
                    <img src="assets/images/icons/arrow-down.svg" class="w-5 h-5" alt="icon">
                </label>
            </div>
            <button type="submit"
                class="flex w-full justify-center rounded-full p-[14px_20px] bg-gassor-orange font-bold text-white">Jelajahi Sekarang</button>
        </div>
    </form>
</div>

@include('includes.navigation')
@endsection
