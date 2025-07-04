@extends('layouts.app')

@section('content')
<div id="Background" class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]"></div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('home') }}" class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="assets/images/icons/arrow-left.svg" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Rincian Pemesanan Saya</p>
    <div class="dummy-btn w-12"></div>
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
        <div class="flex gap-4">
            <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $transaction->motorbikeRental->thumbnail) }}" class="w-full h-full object-cover"
                    alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <p class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">
                    {{ $transaction->motorbikeRental->name }}
                </p>
                <hr class="border-[#F1F2F6]">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Wilayah {{ $transaction->motorbikeRental->city->name }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Kategori {{ $transaction->motorbikeRental->category->name }}</p>
                </div>
            </div>
        </div>
        <hr class="border-[#F1F2F6]">
        <div class="flex gap-4">
            <div class="flex w-[120px] h-[156px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $transaction->motorcycle->images->first()->image) }}" class="w-full h-full object-cover"
                    alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <p class="font-semibold text-lg leading-[27px]">{{ $transaction->motorcycle->name }}</p>
                <hr class="border-[#F1F2F6]">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/notes.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">STNK : {{ $transaction->motorcycle->stnk }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/police.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Nomor Polisi : {{ $transaction->motorcycle->vehicle_number_plate }}</p>
                </div>
                <hr class="border-[#F1F2F6]">
                <p class="font-semibold text-lg text-gassor-orange">Rp
                    {{ number_format($transaction->motorcycle->price_per_day, 0, ',', '.') }}<span
                        class="text-sm text-gassor-grey font-normal">/hari</span>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
    <label class="relative flex items-center justify-between">
        <p class="font-semibold text-lg">Pelanggan</p>
        <img src="assets/images/icons/arrow-up.svg" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon">
        <input type="checkbox" class="absolute hidden">
    </label>
    <div class="flex flex-col gap-4 pt-[22px]">
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/profile-2user.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Nama</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction->name }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/sms.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Email</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction->email }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/call.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Nomor Telepon</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction->phone_number }}</p>
        </div>
    </div>
</div>
<div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
    <label class="relative flex items-center justify-between">
        <p class="font-semibold text-lg">Pemesanan</p>
        <img src="assets/images/icons/arrow-up.svg" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon">
        <input type="checkbox" class="absolute hidden">
    </label>
    <div class="flex flex-col gap-4 pt-[22px]">
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/calendar.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">ID Pemesanan</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction->code }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/clock.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Durasi</p>
            </div>
            <p class="font-semibold w-full break-all text-right">{{ $transaction->duration }} Hari</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/calendar.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Dimulai pada</p>
            </div>
            <p class="font-semibold w-full break-all text-right">
                {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i') }} WIB
            </p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/calendar.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Berakhir pada</p>
            </div>
            <p class="font-semibold w-full break-all text-right">
                {{ \Carbon\Carbon::parse($transaction['start_date'])->addDays(intval($transaction['duration']))->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i') }} WIB
            </p>
        </div>
    </div>
</div>
<div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
    <label class="relative flex items-center justify-between">
        <p class="font-semibold text-lg">Pembayaran</p>
        <img src="assets/images/icons/arrow-up.svg" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon">
        <input type="checkbox" class="absolute hidden">
    </label>
    @php
    $subtotal = $transaction->motorcycle->price_per_day * $transaction->duration;
    $total = $subtotal;
    @endphp
    <div class="flex flex-col gap-4 pt-[22px]">
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/card-tick.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Pembayaran</p>
            </div>
            <p class="font-semibold w-full break-all text-right">Pembayaran Lunas 100%</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/receipt-2.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Jumlah Total</p>
            </div>
            <p class="font-semibold w-full break-all text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/receipt-text.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Total Keseluruhan</p>
            </div>
            <p class="font-semibold w-full break-all text-right">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
            <div class="flex items-center gap-3 w-full">
                <img src="assets/images/icons/security-card.svg" class="flex w-6 h-6 shrink-0" alt="icon" />
                <p class="text-gassor-grey text-left">Status</p>
            </div>
            @php
                $status = strtoupper($transaction->payment_status);
                $statusColor = match($status) {
                    'SUCCESS' => '#27ae60',
                    'FAILED' => '#eb5757',
                    'CANCELED' => '#bdbdbd',
                    'PENDING' => '#E6A43B',
                    'EXPIRED' => '#9b51e0',
                    default => '#828282',
                };
            @endphp
            <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] w-full break-all text-right text-white" style="background: {{ $statusColor }};">
                {{ $status }}
            </p>
        </div>
    </div>
</div>
<div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
    <label class="relative flex items-center justify-between">
        <p class="font-semibold text-lg">Whatsapp Pemilik</p>
        <img src="assets/images/icons/arrow-up.svg" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon">
        <input type="checkbox" class="absolute hidden">
    </label>
    <div class="flex flex-col gap-4 pt-[22px]">
        @foreach ($motorbikeRental->contacts ?? [$motorbikeRental] as $contact)
            <div
                onclick="window.open('https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->contact) }}', '_blank')"
                class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3 hover:border-[#25D366] transition-all duration-300 cursor-pointer"
                style="cursor:pointer;"
            >
                <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#ffffff] overflow-hidden items-center justify-center">
                    <img src="{{ asset('assets/images/icons/whatsapp.svg') }}" class="w-12 h-12" alt="WhatsApp">
                </div>
                <div>
                    <p class="font-semibold">WhatsApp -
                        @php
                            $owners = $motorbikeRental->owners;
                            $ownerNames = $owners->pluck('name')->unique()->implode(', ');
                        @endphp
                        {{ $ownerNames }}
                    </p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->contact) }}" target="_blank" class="text-orange-600 font-bold hover:underline flex items-center gap-1">
                        {{ $contact->contact }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@if(strtoupper($transaction->payment_status) === 'SUCCESS')
<div class="flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5">
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold text-lg">STNK Motor</p>
    </div>
    <div class="flex flex-col gap-4 pt-[22px]">
        @if(isset($transaction->motorcycle->stnk_images) && is_array($transaction->motorcycle->stnk_images) && count($transaction->motorcycle->stnk_images) > 0)
            <div class="flex flex-col gap-4 items-center">
                @foreach($transaction->motorcycle->stnk_images as $stnkImg)
                    <div class="rounded-lg border max-w-xs w-full overflow-hidden bg-white cursor-pointer" onclick="showStnkModal('{{ asset('storage/' . $stnkImg) }}')">
                        <img src="{{ asset('storage/' . $stnkImg) }}" alt="Gambar STNK" class="object-contain w-full h-48">
                    </div>
                @endforeach
            </div>
        @elseif(isset($transaction->motorcycle->stnk_images) && is_string($transaction->motorcycle->stnk_images) && !empty($transaction->motorcycle->stnk_images))
            <div class="rounded-lg border max-w-xs w-full overflow-hidden bg-white mx-auto cursor-pointer" onclick="showStnkModal('{{ asset('storage/' . $transaction->motorcycle->stnk_images) }}')">
                <img src="{{ asset('storage/' . $transaction->motorcycle->stnk_images) }}" alt="Gambar STNK" class="object-contain w-full h-48">
            </div>
        @else
            <p class="text-center text-gassor-grey">Gambar STNK tidak tersedia.</p>
        @endif
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showStnkModal(imgUrl) {
    Swal.fire({
        imageUrl: imgUrl,
        imageAlt: 'Gambar STNK',
        showConfirmButton: false,
        width: 'auto',
        background: '#fff',
        customClass: {
            image: 'w-full h-auto max-w-2xl rounded-lg',
            popup: 'p-0 text-black',
        },
        color: '#000000'
    });
}
</script>
@endif
<div id="BottomButton" class="relative flex w-full h-[98px] shrink-0">
    <div class="fixed bottom-[30px] w-full max-w-[640px] px-5 z-10">
        <a href="https://wa.me/6285174309823" class="flex w-full justify-center rounded-full p-[14px_20px] bg-gassor-orange font-bold text-white mt-2">Hubungi Layanan Pelanggan</a>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/accodion.js') }}"></script>
<script>
    const tabLinks = document.querySelectorAll('.tab-link');

    tabLinks.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-target-tab');
            console.log(targetTab)
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            document.querySelector(targetTab).classList.toggle('hidden');
        });
    });
</script>
@endsection
