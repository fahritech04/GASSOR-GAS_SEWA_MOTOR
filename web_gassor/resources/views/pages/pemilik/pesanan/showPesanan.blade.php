@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="Background"
    class="absolute top-0 w-full h-[570px] rounded-b-[75px]"
    style="background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%);">
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Pesanan</h1>
        <p class="text-gassor-grey">Tersedia {{ $transactions->count() }} Pesanan</p>
    </div>
</div>
<section id="Result" class="relative flex flex-col gap-4 px-5 mt-5 mb-9">
    <h2 class="font-bold text-lg mb-2">Pesanan yang dimiliki {{ auth()->user()->name }}</h2>
    @forelse ($transactions as $transaction)
        @php
            $rentalStatus = $transaction->rental_status ?? 'pending';

            if ($transaction->payment_status === 'success') {
                if ($rentalStatus === 'finished') {
                    $rentalStatusLabel = 'SELESAI';
                    $rentalStatusColor = '#27ae60';
                } elseif ($rentalStatus === 'on_going') {
                    $rentalStatusLabel = 'SEDANG BERJALAN';
                    $rentalStatusColor = '#E6A43B';
                } else {
                    $rentalStatusLabel = null;
                    $rentalStatusColor = '#828282';
                }
            } else {
                $rentalStatusLabel = null;
                $rentalStatusColor = '#828282';
            }

            $borderColor = ($rentalStatus === 'on_going' && $transaction->payment_status === 'success') ? '#E6A43B' : '#000000';
        @endphp

        <div class="bonus-card flex items-center justify-between rounded-[22px] border-2 p-[10px] gap-3 mb-3 bg-white"
             data-transaction-id="{{ $transaction->id }}"
             style="border-color: {{ $borderColor }};">
            <div>
                <p class="font-semibold">
                    {{ $transaction->motorcycle->name ?? '-' }}
                    {{-- <span class="text-xs text-gray-500">(ID: #{{ $transaction->id }})</span> --}}
                </p>
                <p class="text-sm text-gassor-grey">
                    Disewa : <span class="font-semibold">{{ $transaction->name }}</span>
                </p>
                {{-- <p class="text-xs text-gray-500">
                    Motor ID: {{ $transaction->motorcycle_id }} |
                    Plat: {{ $transaction->motorcycle->vehicle_number_plate ?? '-' }}
                </p> --}}
                <p class="text-sm text-gassor-grey" style="text-align:left">
                    Mulai : {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') . ($transaction->start_time ? ' - ' . (strlen($transaction->start_time) === 5 ? $transaction->start_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                    <br>
                    Selesai : {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->addDays(1)->isoFormat('D MMMM YYYY') . ($transaction->end_time ? ' - ' . (strlen($transaction->end_time) === 5 ? $transaction->end_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                </p>
            </div>
            <div class="flex flex-col items-end gap-2 justify-center h-full">
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
                @if(strtoupper($transaction->payment_status) === 'PENDING')
                    <form method="POST" action="{{ route('pemilik.pesanan.sync', $transaction->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] break-all text-white text-center border-none cursor-pointer" style="background: #3498db;" title="Sinkronisasi status dengan Midtrans">
                            SYNC
                        </button>
                    </form>
                @endif
                @if(strtoupper($transaction->payment_status) === 'SUCCESS' && $rentalStatusLabel)
                    <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] text-white text-center" style="background: {{ $rentalStatusColor }};">
                        {{ $rentalStatusLabel }}
                    </p>
                @endif
            </div>
        </div>
        @if($rentalStatus === 'on_going' && strtoupper($transaction->payment_status) === 'SUCCESS')
            <div class="mt-2 p-2 bg-gray-50 rounded-lg">
                {{-- <p class="text-xs text-gray-600 mb-2">
                    Status: {{ ucfirst($rentalStatus) }} - Transaksi #{{ $transaction->id }}
                </p> --}}
                <form method="POST" action="{{ route('pemilik.pesanan.return', $transaction->id) }}" id="return-form-{{ $transaction->id }}">
                    @csrf
                    <button type="button" onclick="confirmReturn({{ $transaction->id }}, '{{ addslashes($transaction->motorcycle->name ?? '') }}', '{{ addslashes($transaction->name) }}')"
                            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #27ae60; color: #fff; font-weight: bold; font-size: 1rem; box-shadow: none; transition: border 0.2s, box-shadow 0.2s; cursor: pointer;"
                            onmouseover="this.style.borderColor='#E6A43B'" onmouseout="this.style.borderColor='#fff'">
                        Motor Sudah Dikembalikan
                    </button>
                </form>
                <a href="{{ route('pemilik.pesanan.tracking', $transaction->id) }}"
                   style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #000000; color: #fff; font-weight: bold; font-size: 1rem; box-shadow: none; transition: border 0.2s, box-shadow 0.2s; cursor: pointer; margin-top: 8px;">
                    Lihat Tracking Motor
                </a>
            </div>
        @elseif($rentalStatus === 'finished' && strtoupper($transaction->payment_status) === 'SUCCESS')
            <div class="mt-2 p-2 bg-green-50 rounded-lg">
                <p class="text-xs text-green-600 text-center">
                    ✅ Motor sudah dikembalikan pada transaksi ini
                </p>
            </div>
        @endif
    @empty
        <p class="text-center text-gassor-grey">Belum ada pesanan terbaru.</p>
    @endforelse

    <div class="mt-4 flex justify-center">
        @if ($transactions->hasPages())
            <nav style="display: flex; gap: 4px; align-items: center;" aria-label="Pagination">
                {{-- Halaman Sebelumnya --}}
                @if ($transactions->onFirstPage())
                    <span style="padding: 6px 14px; border-radius: 8px; background: #e5e5e5; color: #aaa; font-weight: bold; border: 1px solid #e5e5e5; cursor: not-allowed;">&laquo;</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" style="padding: 6px 14px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">&laquo;</a>
                @endif

                {{-- halaman angka --}}
                @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if ($page == $transactions->currentPage())
                        <span style="padding: 6px 12px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 6px 12px; border-radius: 8px; background: #fff; color: #000; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Halaman Selanjutnya --}}
                @if ($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" style="padding: 6px 14px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">&raquo;</a>
                @else
                    <span style="padding: 6px 14px; border-radius: 8px; background: #e5e5e5; color: #aaa; font-weight: bold; border: 1px solid #e5e5e5; cursor: not-allowed;">&raquo;</span>
                @endif
            </nav>
        @endif
    </div>
</section>

{{-- Auto-refresh untuk transaksi pending --}}
@php
    $hasPendingTransactions = $transactions->where('payment_status', 'pending')->count() > 0;
@endphp

@if($hasPendingTransactions)
<script>
    // Auto-refresh untuk transaksi pending dengan AJAX
    let autoRefreshInterval;
    let pendingTransactionIds = [];
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($transactions as $transaction)
            @if($transaction->payment_status === 'pending')
                pendingTransactionIds.push({{ $transaction->id }});
            @endif
        @endforeach

        if (pendingTransactionIds.length > 0) {
            startAutoRefresh();
            showAutoRefreshNotification();
        }
    });
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(function() {
            checkPendingTransactions();
        }, 15000); // Check setiap 15 detik untuk lebih responsive
    }
    function checkPendingTransactions() {
        pendingTransactionIds.forEach(transactionId => {
            fetch(`/pemilik/pesanan/${transactionId}/check-status`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.updated) {
                    console.log(`🎉 Status updated for transaction ${transactionId}:`, data.message);

                    // Reload halaman jika ada update
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            })
            .catch(error => {
                console.log('Error checking status:', error);
            });
        });
    }
    function showAutoRefreshNotification() {
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div id="auto-refresh-notification" style="position: fixed; bottom: 130px; right: 20px; background: #3498db; color: white; padding: 10px 15px; border-radius: 8px; font-size: 12px; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                🔄 Auto-check status aktif (${pendingTransactionIds.length} transaksi pending)
                <button onclick="stopAutoRefresh()" style="margin-left: 10px; background: transparent; border: 1px solid white; color: white; padding: 2px 6px; border-radius: 4px; cursor: pointer;">Stop</button>
            </div>
        `;
        document.body.appendChild(notification);

        // Hide notification after 5 seconds
        setTimeout(() => {
            const notif = document.getElementById('auto-refresh-notification');
            if (notif) {
                notif.style.opacity = '0.5';
            }
        }, 5000);
    }
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        const notif = document.getElementById('auto-refresh-notification');
        if (notif) {
            notif.style.display = 'none';
        }
    }
    // Stop auto-refresh when user navigates away
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
</script>
@endif

<script>
function confirmReturn(transactionId, motorName, customerName) {
    Swal.fire({
        icon: 'question',
        title: 'Konfirmasi Pengembalian',
        text: `Apakah motor ${motorName} sudah dikembalikan oleh ${customerName}?`,
        showCancelButton: true,
        confirmButtonColor: '#27ae60',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Ya, Sudah Dikembalikan',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'text-black',
            confirmButton: 'rounded-full',
            cancelButton: 'rounded-full'
        },
        color: '#000000'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('return-form-' + transactionId).submit();
        }
    });
}
</script>

@include('includes.navigation_pemilik')
@endsection
