<div id="BottomNav" class="relative flex w-full h-[138px] shrink-0">
    <nav class="fixed bottom-5 w-full max-w-[640px] px-5 z-10">
        <div class="grid grid-cols-4 h-fit rounded-[40px] justify-between py-4 px-5 bg-gassor-black">
            <a href="{{ route('home') }}" class="flex flex-col items-center text-center gap-2">
                <img src="{{ asset('assets/images/icons/global' . (request()->routeIs('pemilik.dashboard') ? '-orange' : '') . '.svg') }}" class="w-8 h-8 flex shrink-0" alt="icon">
                <span class="font-semibold text-sm text-white">Home</span>
            </a>
            <a href="{{ route('pemilik.daftar-motor') }}" class="flex flex-col items-center text-center gap-2">
                <img src="{{ asset('assets/images/icons/note-favorite' . (request()->routeIs('pemilik.daftar-motor') ? '-orange' : '') . '.svg') }}" class="w-8 h-8 flex shrink-0" alt="icon">
                <span class="font-semibold text-sm text-white">Daftar Motor</span>
            </a>
            <a href="{{ route('pemilik.pesanan') }}" class="flex flex-col items-center text-center gap-2">
                <img src="{{ asset('assets/images/icons/note-favorite' . (request()->routeIs('pemilik.pesanan') ? '-orange' : '') . '.svg') }}" class="w-8 h-8 flex shrink-0" alt="icon">
                <span class="font-semibold text-sm text-white">Pesanan</span>
            </a>
            <a href="{{ route('pemilik.laporan-keuangan') }}" class="flex flex-col items-center text-center gap-2">
                <img src="{{ asset('assets/images/icons/money-bill' . (request()->routeIs('pemilik.laporan-keuangan') ? '-orange' : '') . '.svg') }}" class="w-8 h-8 flex shrink-0" alt="icon">
                <span class="font-semibold text-sm text-white">Laporan Keuangan</span>
            </a>
        </div>
    </nav>
</div>
