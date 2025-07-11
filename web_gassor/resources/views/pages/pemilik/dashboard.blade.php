@extends('layouts.app')

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
        {{-- <div style="position: absolute; top: 0; width: 100%; height: 230px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #ff9d00 100%)"></div> --}}
        <div style="position: absolute; top: 0; width: 100%; height: 230px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
        <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
            <div class="flex flex-col gap-1">
            <p>Selamat datang kembali</p>
            <h1 class="font-bold text-xl leading-[30px]">
                @auth
                    @if (empty(Auth::user()->name))
                        Pemilik GASSOR
                    @else
                        {{ Auth::user()->name }}
                    @endif
                @endauth
            </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="https://wa.me/6285942572814" target="_blank" rel="noopener" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
                    <img src="{{ asset('assets/images/icons/whatsapp.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
                </a>
                <a href="{{ route('profile.pemilik') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
                    <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
                </a>
            </div>
        </div>

        <section id="Best" class="relative flex flex-col gap-4 px-5 mt-[30px]">
            <div class="flex flex-col gap-4">
            <div class="card">
                <div class="flex justify-center gap-4 rounded-[30px] p-4 bg-white" style="border: 1.5px solid #e6a43b">
                <div class="flex flex-col items-center flex-1 max-w-[140px] p-4 text-center">
                    <img src="{{ asset('assets/images/icons/total-motor.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    <span class="text-m font-bold">{{ $totalMotor }}</span>
                    <span class="text-m text-gassor-grey">Total Motor</span>
                </div>
                <div class="flex flex-col items-center flex-1 max-w-[140px] p-4 text-center">
                    <img src="{{ asset('assets/images/icons/pesanan-aktif.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    <span class="text-m font-bold">{{ $activeOrders }}</span>
                    <span class="text-m text-gassor-grey">Pesanan Aktif</span>
                </div>
                <div class="flex flex-col items-center flex-1 max-w-[140px] p-4 text-center">
                    <img src="{{ asset('assets/images/icons/pendapatan.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    <span class="text-m font-bold">Rp {{ number_format($totalIncome / 1.11, 0, ',', '.') }}</span>
                    <span class="text-m text-gassor-grey">Pendapatan</span>
                </div>
                </div>
            </div>
            </div>
        </section>

        <section id="Popular" class="flex flex-col gap-4 mt-[30px]">
            <div class="flex items-center justify-between px-5">
            <h2 class="font-bold">Daftar Motor Anda</h2>
            <a href="{{ route('pemilik.daftar-motor') }}">
                <div class="flex items-center gap-2">
                    <span>Semua</span>
                    <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                </div>
            </a>
            </div>
            <div class="w-full overflow-x-hidden swiper">
            <div id="TabsContent" class="px-5">
                <div id="Bonus-Tab" class="flex flex-col gap-5 tab-content">
                    <div class="flex flex-col gap-4">
                        @forelse($motorcycles as $motorcycle)
                            <div class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3">
                                <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden">
                                    <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover" alt="icon">
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $motorcycle->name }}</p>
                                    <div class="flex items-center gap-2 text-sm text-gassor-grey">
                                        <img src="{{ asset('assets/images/icons/status.svg') }}" class="w-4 h-4" alt="status" />
                                        <span>Stok: {{ $motorcycle->available_stock ?? 0 }}/{{ $motorcycle->stock ?? 1 }}</span>
                                    </div>
                                    <p class="text-sm text-gassor-grey">Harga • Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gassor-grey">Belum ada motor terdaftar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section id="transaction" class="flex flex-col p-5 gap-4 bg-[#F5F6F8] mt-[30px]">
            <div class="flex items-center justify-between">
            <h2 class="font-bold">Pesanan Terbaru</h2>
            <a href="{{ route('pemilik.pesanan') }}">
                <div class="flex items-center gap-2">
                    <span>Semua</span>
                    <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                </div>
            </a>
            </div>
            <div id="Bonus-Tab" class="flex flex-col gap-5 tab-content">
                <div class="flex flex-col gap-4">
                    @forelse($recentTransactions as $transaction)
                        <div class="bonus-card flex items-center justify-between rounded-[22px] border border-[#000000] p-[10px] gap-3">
                            <div>
                                <p class="font-semibold">{{ $transaction->motorcycle->name ?? '-' }}</p>
                                <p class="text-sm text-gassor-grey">
                                    Disewa Oleh: <span class="font-semibold">{{ $transaction->name }}</span>
                                </p>
                                <p class="text-sm text-gassor-grey">
                                    Tanggal: {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] break-all text-white text-center" style="background: {{
                                    match(strtoupper($transaction->payment_status)) {
                                        'SUCCESS' => '#27ae60',
                                        'FAILED' => '#eb5757',
                                        'CANCELED' => '#bdbdbd',
                                        'PENDING' => '#E6A43B',
                                        'EXPIRED' => '#9b51e0',
                                        default => '#828282',
                                    }
                                }};">
                                    {{ strtoupper($transaction->payment_status) }}
                                </p>
                                @if($transaction->payment_status === 'success')
                                    @php
                                        $rentalStatus = $transaction->rental_status ?? 'pending';
                                        $statusLabel = match($rentalStatus) {
                                            'pending' => 'MENUNGGU',
                                            'on_going' => 'BERJALAN',
                                            'finished' => 'SELESAI',
                                            'cancelled' => 'BATAL',
                                            default => 'UNKNOWN'
                                        };
                                        $statusColor = match($rentalStatus) {
                                            'pending' => '#3498db',
                                            'on_going' => '#E6A43B',
                                            'finished' => '#27ae60',
                                            'cancelled' => '#95a5a6',
                                            default => '#828282'
                                        };
                                    @endphp
                                    <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] text-white text-center" style="background: {{ $statusColor }};">
                                        {{ $statusLabel }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gassor-grey">Belum ada pesanan terbaru.</p>
                    @endforelse
                </div>
            </div>
        </section>

    </div>

@include('includes.navigation_pemilik')
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/index.js"></script>
@endsection
