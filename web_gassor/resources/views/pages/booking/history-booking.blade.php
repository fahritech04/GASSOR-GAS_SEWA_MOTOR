@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 430px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>

<div id="TopNav" style="position: relative; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; margin-top: 60px;">
    <a href="{{ route('profile.penyewa') }}" style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; overflow: hidden; background: #ffffff; border-radius: 50%; flex-shrink: 0; text-decoration: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: all 0.3s;"
       onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.15)';"
       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" style="width: 28px; height: 28px;" alt="icon" />
    </a>
    <h1 style="font-weight: 600; color: #000000; margin: 0; font-size: 18px;">Riwayat Pemesanan</h1>
    <div style="width: 48px;"></div>
</div>

<div style="position: relative; padding: 0 20px; margin-top: 20px;">
    <form method="GET" action="{{ request()->url() }}" id="filterForm">
        <div style="background: #ffffff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid #f0f0f0;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <label for="payment_status_filter" style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">
                    Filter Status Pembayaran
                </label>
                @if(request('payment_status'))
                    <a href="{{ route('history-booking') }}" style="font-size: 12px; color: #e6a43b; font-weight: 500; text-decoration: none; padding: 4px 8px; border-radius: 6px; background: #fef3e2; transition: all 0.2s;">
                        Reset Filter
                    </a>
                @endif
            </div>

            <div style="display: flex; gap: 8px; align-items: center;">
                <select name="payment_status" id="payment_status_filter"
                        style="flex: 1; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; color: #374151; background: #ffffff; outline: none; transition: all 0.3s; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 4 5\"><path fill=\"%23666\" d=\"M2 0L0 2h4zm0 5L0 3h4z\"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px;"
                        onfocus="this.style.borderColor='#e6a43b'; this.style.boxShadow='0 0 0 3px rgba(230, 164, 59, 0.1)';"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    <option value="">Semua Status</option>
                    <option value="SUCCESS" {{ request('payment_status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                    <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                    <option value="FAILED" {{ request('payment_status') == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                    <option value="CANCELED" {{ request('payment_status') == 'CANCELED' ? 'selected' : '' }}>CANCELED</option>
                    <option value="EXPIRED" {{ request('payment_status') == 'EXPIRED' ? 'selected' : '' }}>EXPIRED</option>
                </select>

                <button type="submit" id="filterButton"
                        style="padding: 12px 20px; background: #e6a43b; color: #ffffff; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(230, 164, 59, 0.2); white-space: nowrap;"
                        onmouseover="this.style.background='#d4932f'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(230, 164, 59, 0.3)';"
                        onmouseout="this.style.background='#e6a43b'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(230, 164, 59, 0.2)';">
                    Filter
                </button>
            </div>

            @if(request('payment_status'))
                <div style="margin-top: 12px; padding: 8px 12px; background: #f9fafb; border-radius: 8px;">
                    <p style="font-size: 12px; color: #6b7280; margin: 0;">
                        Menampilkan transaksi dengan status: <span style="font-weight: 600; color: #e6a43b;">{{ strtoupper(request('payment_status')) }}</span>
                    </p>
                </div>
            @endif

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0; font-weight: 500;">Quick Filter:</p>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    <a href="{{ route('history-booking') }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ !request('payment_status') ? 'background: #e6a43b; color: #ffffff;' : 'background: #f3f4f6; color: #6b7280;' }}"
                       onmouseover="if('{{ !request('payment_status') ? 'false' : 'true' }}' === 'true') { this.style.background='#e5e7eb'; }"
                       onmouseout="if('{{ !request('payment_status') ? 'false' : 'true' }}' === 'true') { this.style.background='#f3f4f6'; }">
                        Semua
                    </a>
                    <a href="{{ route('history-booking', ['payment_status' => 'SUCCESS']) }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request('payment_status') == 'SUCCESS' ? 'background: #27ae60; color: #ffffff;' : 'background: #f3f4f6; color: #6b7280;' }}"
                       onmouseover="if('{{ request('payment_status') == 'SUCCESS' ? 'false' : 'true' }}' === 'true') { this.style.background='#e5e7eb'; }"
                       onmouseout="if('{{ request('payment_status') == 'SUCCESS' ? 'false' : 'true' }}' === 'true') { this.style.background='#f3f4f6'; }">
                        SUCCESS
                    </a>
                    <a href="{{ route('history-booking', ['payment_status' => 'PENDING']) }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request('payment_status') == 'PENDING' ? 'background: #E6A43B; color: #ffffff;' : 'background: #f3f4f6; color: #6b7280;' }}"
                       onmouseover="if('{{ request('payment_status') == 'PENDING' ? 'false' : 'true' }}' === 'true') { this.style.background='#e5e7eb'; }"
                       onmouseout="if('{{ request('payment_status') == 'PENDING' ? 'false' : 'true' }}' === 'true') { this.style.background='#f3f4f6'; }">
                        PENDING
                    </a>
                    <a href="{{ route('history-booking', ['payment_status' => 'FAILED']) }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request('payment_status') == 'FAILED' ? 'background: #eb5757; color: #ffffff;' : 'background: #f3f4f6; color: #6b7280;' }}"
                       onmouseover="if('{{ request('payment_status') == 'FAILED' ? 'false' : 'true' }}' === 'true') { this.style.background='#e5e7eb'; }"
                       onmouseout="if('{{ request('payment_status') == 'FAILED' ? 'false' : 'true' }}' === 'true') { this.style.background='#f3f4f6'; }">
                        FAILED
                    </a>
                    <a href="{{ route('history-booking', ['payment_status' => 'CANCELED']) }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request('payment_status') == 'CANCELED' ? 'background: #bdbdbd; color: #ffffff;' : 'background: #f3f4f6; color: #6b7280;' }}"
                       onmouseover="if('{{ request('payment_status') == 'CANCELED' ? 'false' : 'true' }}' === 'true') { this.style.background='#e5e7eb'; }"
                       onmouseout="if('{{ request('payment_status') == 'CANCELED' ? 'false' : 'true' }}' === 'true') { this.style.background='#f3f4f6'; }">
                        CANCELED
                    </a>
                    <a href="{{ route('history-booking', ['payment_status' => 'EXPIRED']) }}"
                       style="padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request('payment_status') == 'EXPIRED' ? 'background: #9b51e0; color: #ffffff;' : 'background: #f3f4f6; color: #6b7280;' }}"
                       onmouseover="if('{{ request('payment_status') == 'EXPIRED' ? 'false' : 'true' }}' === 'true') { this.style.background='#e5e7eb'; }"
                       onmouseout="if('{{ request('payment_status') == 'EXPIRED' ? 'false' : 'true' }}' === 'true') { this.style.background='#f3f4f6'; }">
                        EXPIRED
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('payment_status_filter');
    const form = document.getElementById('filterForm');
    const button = document.getElementById('filterButton');

    console.log('Filter script initialized');
    console.log('Current payment_status:', '{{ request("payment_status") }}');
    console.log('Current URL:', window.location.href);
    console.log('Form action:', form.action);

    // Auto-submit dropdown berubah
    dropdown.addEventListener('change', function() {
        const selectedValue = this.value;
        console.log('Dropdown changed to:', selectedValue);

        // Update form action parameter baru
        const baseUrl = '{{ route("history-booking") }}';
        const newUrl = selectedValue ? baseUrl + '?payment_status=' + encodeURIComponent(selectedValue) : baseUrl;

        console.log('Redirecting to:', newUrl);

        // Direct redirect untuk memastikan parameter ter-update
        window.location.href = newUrl;
    });

    // Manual submit dengan button
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedValue = dropdown.value;
        console.log('Manual filter button clicked, value:', selectedValue);

        const baseUrl = '{{ route("history-booking") }}';
        const newUrl = selectedValue ? baseUrl + '?payment_status=' + encodeURIComponent(selectedValue) : baseUrl;

        console.log('Manual redirecting to:', newUrl);
        window.location.href = newUrl;
    });

    form.addEventListener('submit', function(e) {
        console.log('Form submitted');
        console.log('Form data:', new FormData(form));
    });
});
</script>

<div style="position: relative; display: flex; flex-direction: column; gap: 24px; margin: 40px 0 60px 0; padding: 0 20px;">
    @if(count($transactions) > 0)
        <div style="background: #ffffff; border-radius: 12px; padding: 16px; margin-bottom: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #f0f0f0;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 20px;"></span>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">
                    <span style="font-weight: 600; color: #374151;">{{ count($transactions) }}</span> transaksi ditemukan
                    @if(request('payment_status'))
                        untuk status <span style="font-weight: 600; color: #e6a43b;">{{ strtoupper(request('payment_status')) }}</span>
                    @endif
                </p>
            </div>
        </div>
    @endif

    @forelse ($transactions as $transaction)
        <div style="width: 100%; margin-bottom: 20px;">
            <form action="{{ route('check-booking.show') }}" method="POST" style="width: 100%;">
                @csrf
                <input type="hidden" name="code" value="{{ $transaction->code }}">
                <input type="hidden" name="email" value="{{ $transaction->email }}">
                <input type="hidden" name="phone_number" value="{{ $transaction->phone_number }}">
                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 16px; border: 2px solid #e5e7eb; padding: 16px; gap: 16px; margin-bottom: 12px; background: #ffffff;">
                        <div style="text-align: left; width: 100%;">
                            <p style="font-weight: 600; font-size: 18px; text-align: left; margin: 0 0 8px 0; color: #1f2937;">{{ $transaction->motorcycle->name ?? '-' }}</p>
                            <p style="font-size: 14px; color: #6b7280; text-align: left; margin: 0 0 6px 0;">
                                <span style="font-weight: 500;">Pemilik:</span> <span style="font-weight: 600; color: #374151;">{{ $transaction->motorcycle->owner->name ?? '-' }}</span>
                            </p>
                            <div style="font-size: 13px; color: #6b7280; text-align: left; line-height: 1.5;">
                                <div style="margin-bottom: 4px;">
                                    <span style="font-weight: 500;">Mulai:</span> {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') . ($transaction->start_time ? ' - ' . (strlen($transaction->start_time) === 5 ? $transaction->start_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                                </div>
                                <div>
                                    <span style="font-weight: 500;">Selesai:</span> {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->addDays(1)->isoFormat('D MMMM YYYY') . ($transaction->end_time ? ' - ' . (strlen($transaction->end_time) === 5 ? $transaction->end_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; justify-content: center; height: 100%;">
                            @php
                                $rentalStatus = $transaction->rental_status ?? 'pending';
                                if ($rentalStatus === 'finished') {
                                    $rentalStatusLabel = 'SELESAI';
                                    $rentalStatusColor = '#27ae60';
                                } elseif ($rentalStatus === 'canceled') {
                                    $rentalStatusLabel = 'DIBATALKAN';
                                    $rentalStatusColor = '#eb5757';
                                } else {
                                    $rentalStatusLabel = null;
                                    $rentalStatusColor = '#828282';
                                }
                            @endphp
                            <span style="border-radius: 20px; padding: 6px 12px; font-weight: 600; font-size: 11px; line-height: 16px; color: #ffffff; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; background: {{
                                match(strtoupper($transaction->payment_status)) {
                                    'SUCCESS' => '#27ae60',
                                    'FAILED' => '#eb5757',
                                    'CANCELED' => '#bdbdbd',
                                    'PENDING' => '#E6A43B',
                                    'EXPIRED' => '#9b51e0',
                                    default => '#828282',
                                }
                            }}; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                {{ strtoupper($transaction->payment_status) }}
                            </span>
                            @if($rentalStatusLabel)
                                <span style="border-radius: 20px; padding: 6px 12px; font-weight: 600; font-size: 11px; line-height: 16px; color: #ffffff; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; background: {{ $rentalStatusColor }}; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    {{ $rentalStatusLabel }}
                                </span>
                            @endif
                        </div>
                    </div>
                </button>
            </form>

            @if($transaction->motorbikeRental && $transaction->motorbikeRental->slug)
                <div style="margin-top: 8px; display: flex; gap: 8px;">
                    <a href="{{ route('motor.show', $transaction->motorbikeRental->slug) }}"
                       style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 14px 0; background: #000000; color: #ffffff; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                       onmouseover="this.style.background='#1f2937'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)';"
                       onmouseout="this.style.background='#000000'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
                        Pesan Lagi
                    </a>

                    {{-- Tombol Review untuk transaksi yang sudah selesai --}}
                    @if($transaction->can_be_reviewed)
                        <a href="{{ route('review.create', $transaction) }}"
                           style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 14px 0; background: #e6a43b; color: #ffffff; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 6px rgba(230, 164, 59, 0.2);"
                           onmouseover="this.style.background='#d4932f'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px rgba(230, 164, 59, 0.3)';"
                           onmouseout="this.style.background='#e6a43b'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(230, 164, 59, 0.2)';">
                            Beri Review
                        </a>
                    @elseif($transaction->is_reviewed)
                        <a href="{{ route('motorcycle.reviews', $transaction->motorcycle_id) }}"
                           style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 14px 0; background: #27ae60; color: #ffffff; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);"
                           onmouseover="this.style.background='#229954'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px rgba(39, 174, 96, 0.3)';"
                           onmouseout="this.style.background='#27ae60'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(39, 174, 96, 0.2)';">
                            Lihat Review
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div style="background: #ffffff; border-radius: 16px; padding: 40px 24px; text-align: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #f0f0f0;">
            @if(request('payment_status'))
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 64px; margin-bottom: 16px;"></div>
                    <h3 style="color: #374151; font-weight: 600; font-size: 18px; margin: 0 0 8px 0;">
                        Tidak Ada Transaksi Ditemukan
                    </h3>
                    <p style="color: #6b7280; font-size: 14px; margin: 0 0 20px 0;">
                        Tidak ada transaksi dengan status <span style="font-weight: 600; color: #e6a43b; background: #fef3e2; padding: 2px 8px; border-radius: 6px;">{{ strtoupper(request('payment_status')) }}</span>
                    </p>
                    <p style="color: #9ca3af; font-size: 13px; margin-bottom: 24px;">
                        Coba pilih status lain atau lihat semua transaksi
                    </p>
                    <a href="{{ route('history-booking') }}"
                       style="display: inline-block; padding: 12px 24px; background: #e6a43b; color: #ffffff; border-radius: 12px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 6px rgba(230, 164, 59, 0.2);"
                       onmouseover="this.style.background='#d4932f'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px rgba(230, 164, 59, 0.3)';"
                       onmouseout="this.style.background='#e6a43b'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(230, 164, 59, 0.2)';">
                        Lihat Semua Transaksi
                    </a>
                </div>
            @else
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #374151; font-weight: 600; font-size: 18px; margin: 0 0 8px 0;">
                        Belum Ada History Pemesanan
                    </h3>
                    <p style="color: #6b7280; font-size: 14px; margin: 0;">
                        Mulai pesan motor untuk melihat riwayat transaksi di sini
                    </p>
                </div>
            @endif
        </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#e6a43b',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endsection
