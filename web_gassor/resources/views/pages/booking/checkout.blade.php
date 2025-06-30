@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
</div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('booking.information', $motorbikeRental->slug) }}"
        class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Konfirmasi Pesanan</p>
    <div class="dummy-btn w-12"></div>
</div>

<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
        <div class="flex gap-4">
            <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $motorbikeRental->thumbnail) }}" class="w-full h-full object-cover"
                    alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <p class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">
                    {{ $motorbikeRental->name }}
                </p>
                <hr class="border-[#F1F2F6]">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Wilayah {{ $motorbikeRental->city->name }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Kategori {{ $motorbikeRental->category->name }}</p>
                </div>
            </div>
        </div>
        <hr class="border-[#F1F2F6]">
        <div class="flex gap-4">
            <div class="flex w-[120px] h-[156px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover"
                    alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <p class="font-semibold text-lg leading-[27px]">{{ $motorcycle->name }}</p>
                <hr class="border-[#F1F2F6]">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/notes.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">STNK : {{ $motorcycle->stnk }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/police.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                    <p class="text-sm text-gassor-grey">Nomor Polisi : {{ $motorcycle->vehicle_number_plate }}</p>
                </div>
                <hr class="border-[#F1F2F6]">
                <p class="font-semibold text-lg text-gassor-orange">Rp
                    {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}<span
                        class="text-sm text-gassor-grey font-normal">/hari</span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
    <label class="relative flex items-center justify-between">
        <p class="font-semibold text-lg">Pelanggan</p>
        <img src="{{ asset('assets/images/icons/arrow-up.svg') }}"
            class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300"
            alt="icon">
        <input type="checkbox" class="absolute hidden">
    </label>
    <div class="flex flex-col gap-4 pt-[22px]">
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Nama</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction['name'] }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ asset('assets/images/icons/sms.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Email</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction['email'] }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ asset('assets/images/icons/call.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Nomor Telepon</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction['phone_number'] }}</p>
        </div>
    </div>
</div>

<div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
    <label class="relative flex items-center justify-between">
        <p class="font-semibold text-lg">Pemesanan</p>
        <img src="{{ asset('assets/images/icons/arrow-up.svg') }}"
            class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300"
            alt="icon">
        <input type="checkbox" class="absolute hidden">
    </label>
    <div class="flex flex-col gap-4 pt-[22px]">
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ asset('assets/images/icons/clock.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Durasi</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction['duration'] }} Hari</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ asset('assets/images/icons/calendar.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Dimulai pada</p>
            </div>
            <p class="font-semibold w-full break-all text-right">
                {{ \Carbon\Carbon::parse($transaction['start_date'])->isoFormat('D MMMM YYYY') }} - {{ isset($transaction['start_time']) ? (strlen($transaction['start_time']) === 5 ? $transaction['start_time'] : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction['start_time'])->format('H:i'))) : '-' }} WIB
            </p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ asset('assets/images/icons/calendar.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Berakhir pada</p>
            </div>
            <p class="font-semibold w-full break-all text-right">
                {{ \Carbon\Carbon::parse($transaction['start_date'])->addDays(intval($transaction['duration']))->isoFormat('D MMMM YYYY') }} - {{ isset($transaction['end_time']) ? (strlen($transaction['end_time']) === 5 ? $transaction['end_time'] : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction['end_time'])->format('H:i'))) : '-' }} WIB
            </p>
        </div>
    </div>
</div>

<form action="{{ route('booking.payment', $motorbikeRental->slug) }}" class="relative flex flex-col gap-6 mt-5 pt-5" method="POST">
    @csrf
    <div id="PaymentOptions" class="flex flex-col rounded-[30px] border border-[#F1F2F6] p-5 gap-4 mx-5">
        <div id="TabButton-Container" class="flex items-center border-b border-[#F1F2F6] gap-[18px]">
            <label class="tab-link group relative flex flex-col justify-between gap-4" data-target-tab="#FullPayment-Tab">
                <input type="radio" name="payment_method" value="full_payment" class="absolute -z-10 top-1/2 left-1/2 opacity-0" checked>
                <div class="flex items-center gap-3 mx-auto">
                    <div class="relative w-6 h-6">
                        <img src="{{ asset('assets/images/icons/diamonds-orange.svg') }}" class="absolute w-6 h-6 flex shrink-0 opacity-0 group-has-[:checked]:opacity-100 transition-all duration-300" alt="icon">
                        <img src="{{ asset('assets/images/icons/diamonds.svg') }}" class="absolute w-6 h-6 flex shrink-0 group-has-[:checked]:opacity-0 transition-all duration-300" alt="icon">
                    </div>
                    <p class="font-semibold">Pembayaran Lunas</p>
                </div>
                <div class="w-0 mx-auto group-has-[:checked]:ring-1 group-has-[:checked]:ring-[#E6A43B] group-has-[:checked]:w-[90%] transition-all duration-300"></div>
            </label>
        </div>
        <div id="TabContent-Container">
            @php
            $subtotal = $motorcycle->price_per_day * $transaction['duration'];
            $total = $subtotal;
            @endphp
            <div id="FullPayment-Tab" class="tab-content flex flex-col gap-4">
                <p class="text-sm text-gassor-grey">Anda tidak perlu membayar biaya tambahan apapun ketika survey motor</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/card-tick.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-gassor-grey">Pembayaran</p>
                    </div>
                    <p class="font-semibold">Lunas 100%</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/receipt-2.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-gassor-grey">Jumlah Total</p>
                    </div>
                    <p class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/receipt-text.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-gassor-grey">Total Keseluruhan</p>
                    </div>
                    <p id="fullPaymentPrice" class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div id="BottomNav" class="relative flex w-full h-[132px] shrink-0">
        <div class="fixed bottom-5 w-full max-w-[640px] px-5 z-10">
            <div class="flex items-center justify-between rounded-[40px] py-4 px-6 bg-gassor-black">
                <div class="flex flex-col gap-[2px]">
                    <p id="price" class="font-bold text-xl leading-[30px] text-white">
                        <!-- Price mengikuti pilihan yang dipilih dan diambil dari text grand total -->
                    </p>
                    <span class="text-sm text-white">Total Keseluruhan</span>
                </div>
                <button type="submit"
                    class="flex shrink-0 rounded-full py-[14px] px-5 bg-gassor-orange font-bold text-white">Bayar Sekarang</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/accodion.js') }}"></script>
<script>
    // Hanya satu metode pembayaran, update langsung harga total
    document.getElementById('price').innerHTML = document.getElementById('fullPaymentPrice').textContent;

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#E6A43B',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-lg shadow-lg',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops! Ada Masalah',
            text: '{{ session('error') }}',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#EB5757',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-lg shadow-lg',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold'
            }
        });
    @endif

    // Handle form submission dengan validasi tambahan
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Konfirmasi pembayaran dengan SweetAlert2
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Pembayaran',
            html: `
                <div style="text-align: left; margin: 20px 0;">
                    <p><strong>Motor:</strong> {{ $motorcycle->name ?? '-' }}</p>
                    <p><strong>Durasi:</strong> {{ $transaction['duration'] ?? '-' }} hari</p>
                    <p><strong>Total:</strong> Rp {{ number_format(($motorcycle->price_per_day ?? 0) * ($transaction['duration'] ?? 1), 0, ',', '.') }}</p>
                </div>
                <p style="margin-top: 15px;">Anda akan diarahkan ke halaman pembayaran Midtrans. Lanjutkan?</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#E6A43B',
            cancelButtonColor: '#95A5A6',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-lg shadow-lg',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold',
                cancelButton: 'rounded-full px-6 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable tombol submit untuk mencegah double click
                const submitBtn = document.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                // Tampilkan loading dengan SweetAlert2
                Swal.fire({
                    title: 'Memproses Pembayaran...',
                    html: 'Mohon tunggu, Anda akan diarahkan ke halaman pembayaran',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form setelah konfirmasi
                e.target.submit();

                // Re-enable setelah 10 detik jika tidak redirect
                setTimeout(function() {
                    submitBtn.disabled = false;
                    Swal.close();
                }, 10000);
            }
        });
    });

    // Jika user kembali dari halaman lain (Midtrans), cek data
    window.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            // Halaman dimuat dari cache (user menekan back button)
            console.log('User returned to checkout page');
        }
    });
</script>
@endsection
