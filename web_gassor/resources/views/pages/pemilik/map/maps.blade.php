@extends('layouts.app')

@section('vendor-style')
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
      .leaflet-map { width: 100%; height: 500px; }
    </style>
@endsection

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
      <div id="ForegroundFade" class="absolute top-0 w-full h-[143px] bg-[linear-gradient(180deg,#070707_0%,rgba(7,7,7,0)_100%)] z-10"></div>
      <div id="TopNavAbsolute" class="absolute top-[60px] flex items-center justify-between w-full px-5 z-10">
        <a href="{{ route('pemilik.pesanan') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden rounded-full shrink-0 bg-white/10 backdrop-blur-sm">
          <img src="{{ asset('assets/images/icons/arrow-left-transparent.svg') }}" class="w-8 h-8" alt="icon" />
        </a>
      </div>
      <div id="Gallery" class="swiper-gallery w-full overflow-x-hidden -mb-[38px]" style="position:relative; z-index:1;">
        <div class="row">
          <!-- Marker Circle & Polygon -->
          <div class="col-12">
            <div class="mb-4 card">
              <div class="card-body">
                <div class="leaflet-map" id="shapeMap"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <main id="Details" class="relative flex flex-col rounded-t-[40px] py-5 pb-[10px] gap-4 bg-white z-10" style="position:relative; z-index:10;">
        <div id="Title" class="flex items-center justify-between gap-2 px-5">
          <h1 class="font-bold text-[22px] leading-[33px]">Lokasi Kendaraan Sekarang</h1>
        </div>
        <hr class="border-[#F1F2F6] mx-5" />
        <div id="TabsContent" class="px-5">
          <div id="Bonus-Tab" class="flex flex-col gap-5 tab-content">
            <div class="flex flex-col gap-4">
              <div class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3 hover:border-[#E6A43B] transition-all duration-300">
                <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden items-center justify-center">
                  <img src="{{ asset('storage/' . ($transaction->motorcycle->images->first()->image ?? 'default.png')) }}" class="object-cover w-full h-full" alt="icon">
                </div>
                <div>
                  <p class="font-semibold">{{ $transaction->motorcycle->name ?? '-' }}</p>
                  <p class="text-sm text-gassor-grey">
                    Disewa : <span class="font-semibold">{{ $transaction->name }}</span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="p-2 bg-orange-100 text-orange-800 rounded mb-2 mt-[120px] mx-5">
            <div class="flex justify-between items-center mb-2">
                <b>GPS Tracking</b>
                <div class="flex items-center gap-2">
                    <span id="connection-status" class="flex items-center gap-1 text-xs">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                        Menghubungkan...
                    </span>
                    <span id="last-update" class="text-xs text-gray-600"></span>
                </div>
            </div>
            <pre id="gps-json" class="text-xs">{{ json_encode($gpsData, JSON_PRETTY_PRINT) }}</pre>
        </div>
      </main>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
      const motorIcon = L.icon({
        iconUrl: '/assets/images/icons/motor.svg',
        iconSize:     [40, 40],
        iconAnchor:   [20, 40],
        popupAnchor:  [0, -35]
      });

      let map = L.map('shapeMap', { zoomControl: false }).setView([-6.2, 106.8], 13);
      let marker;

      L.control.zoom({ position: 'topright' }).addTo(map);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      function updateConnectionStatus(isConnected, error = null) {
        const statusElement = document.getElementById('connection-status');
        const lastUpdateElement = document.getElementById('last-update');

        if (statusElement) {
          if (isConnected) {
            statusElement.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Terhubung';
          } else {
            statusElement.innerHTML = '<span class="w-2 h-2 bg-red-500 rounded-full"></span> Terputus';
          }
        }

        if (lastUpdateElement && isConnected) {
          const now = new Date();
          lastUpdateElement.textContent = `Diperbarui: ${now.toLocaleTimeString()}`;
        }
      }

      // Update marker
      function updateMarker(data) {
        if (!data || !data.latitude || !data.longitude) return;

        const lat = data.latitude;
        const lng = data.longitude;

        const popup = `
          <strong>Lokasi Terkini</strong><br>
          Lat: ${lat}<br>
          Lng: ${lng}<br>
          Speed: ${data.speed_kmph ?? '-'} km/h<br>
          Arah: ${data.heading_deg ?? '-'}°<br>
          <a href="https://maps.google.com/?q=${lat},${lng}" target="_blank">📍 Google Maps</a>
        `;

        if (!marker) {
          marker = L.marker([lat, lng], {icon: motorIcon}).addTo(map).bindPopup(popup).openPopup();
          map.setView([lat, lng], 15);
        } else {
          marker.setLatLng([lat, lng]).setPopupContent(popup);
        }
      }

      // Polling sederhana ke endpoint Laravel setiap 2 detik
      setInterval(() => {
        $.getJSON('/api/gps')
          .done(function(data) {
            // Update status koneksi menjadi connected
            updateConnectionStatus(true);

            // Tampilkan JSON di atas peta
            document.getElementById('gps-json').textContent = JSON.stringify(data, null, 2);
            updateMarker(data);
          })
          .fail(function(xhr, status, error) {
            console.error('Terjadi kesalahan saat mengambil data GPS:', error);
            console.log('Response:', xhr.responseText);

            // Update status koneksi menjadi disconnected
            updateConnectionStatus(false, error);

            // Tampilkan pesan error di UI
            document.getElementById('gps-json').textContent = 'Error: ' + error + ' - Check console for details';
          });
      }, 2000);
    </script>
@endsection
