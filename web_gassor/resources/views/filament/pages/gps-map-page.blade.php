<x-filament-panels::page>
    @if(isset($this->gpsData) && $this->gpsData)
        <div class="p-2 bg-orange-100 text-orange-800 rounded mb-2">
            <div class="flex justify-between items-center mb-2">
                <b>GPS Tracking</b>
                <div class="flex items-center gap-2">
                    <span id="connection-status" class="flex items-center gap-1 text-xs">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Menghubungkan...
                    </span>
                    <span id="last-update" class="text-xs text-gray-600"></span>
                </div>
            </div>
            <pre id="gps-data-display" class="text-xs">{{ json_encode($this->gpsData, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @else
        <div class="p-2 bg-orange-100 text-orange-800 rounded mb-2">
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
            <pre id="gps-data-display" class="text-xs">Memuat data GPS...</pre>
        </div>
    @endif
    @if(isset($this->motorcyclesWithGps) && count($this->motorcyclesWithGps))
        <div class="p-2 bg-blue-100 text-white-800 rounded mb-2">
            <b>Motor (Lagi Disewa & Ada GPS IoT):</b>
            <ul class="text-xs list-disc pl-4">
                @foreach($this->motorcyclesWithGps as $motor)
                    <li>
                        <b>{{ $motor->name }}</b> - {{ $motor->vehicle_number_plate }} (Kategori {{ $motor->motorcycle_type }})
                        @if($motor->owner)
                            <br><small class="text-white-600">Pemilik: {{ $motor->owner->name ?? '-' }}</small>
                        @endif
                        @php
                            $activeTransaction = $this->getActiveTransactionForMotor($motor->id);
                        @endphp
                        @if($activeTransaction)
                            <br><small class="text-white-600">Disewa oleh: {{ $activeTransaction->name ?? '-' }}</small>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="p-2 bg-yellow-100 text-yellow-800 rounded mb-2">
            Tidak ada motor yang sedang disewa & memiliki GPS IoT.
        </div>
    @endif
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
      .leaflet-map { width: 100%; height: 640px; border-radius: 18px; }
    </style>
    <div id="Content-Container" class="relative flex flex-col bg-white overflow-x-hidden">
      <div id="Gallery" class="swiper-gallery w-full overflow-x-hidden -mb-[38px]" style="position:relative; z-index:1;">
        <div class="row">
          <div class="col-12">
            <div class="mb-4 card">
              <div class="card-body">
                <div class="leaflet-map" id="shapeMap"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
      let motorcycleMarkers = [];

      L.control.zoom({ position: 'topright' }).addTo(map);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      // Fungsi update marker GPS utama (dari Firebase)
      function updateMainGpsMarker(data) {
        if (!data || !data.latitude || !data.longitude) return;

        const lat = data.latitude;
        const lng = data.longitude;

        const popup = `
          <strong>Lokasi GPS Terkini</strong><br>
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

      function clearMotorcycleMarkers() {
        motorcycleMarkers.forEach(m => map.removeLayer(m));
        motorcycleMarkers = [];
      }

      function updateMarkersFromGpsList(motorcycles) {
        clearMotorcycleMarkers();
        if (!motorcycles || motorcycles.length === 0) return;
        motorcycles.forEach(function(motor) {
          // Setiap motor punya data latitude & longitude
          if (!motor.latitude || !motor.longitude) return;
          const popup = `
            <strong>${motor.name}</strong><br>
            Plat: ${motor.vehicle_number_plate}<br>
            Lat: ${motor.latitude}<br>
            Lng: ${motor.longitude}<br>
            Speed: ${motor.speed_kmph ?? '-'} km/h<br>
            Arah: ${motor.heading_deg ?? '-'}°<br>
            <a href="https://maps.google.com/?q=${motor.latitude},${motor.longitude}" target="_blank">📍 Google Maps</a>
          `;
          const motorcycleMarker = L.marker([motor.latitude, motor.longitude], {icon: motorIcon}).addTo(map).bindPopup(popup);
          motorcycleMarkers.push(motorcycleMarker);
        });
        if (motorcycleMarkers.length > 0) {
          map.setView(motorcycleMarkers[0].getLatLng(), 15);
        }
      }

      // Ambil data motor dari blade (PHP ke JS)
      let motorcyclesWithGps = @json($this->motorcyclesWithGps);

      // Update status koneksi
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

      // Update data GPS secara realtime
      function updateGpsData() {
        fetch('/api/gps')
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
          })
          .then(data => {
            // Update status koneksi menjadi connected
            updateConnectionStatus(true);

            // Update tampilan GPS data di bagian atas
            const gpsDataElement = document.getElementById('gps-data-display');
            if (gpsDataElement) {
              gpsDataElement.textContent = JSON.stringify(data, null, 2);
            }

            // Update marker GPS utama dari Firebase (prioritas utama)
            updateMainGpsMarker(data);

            // Update data motor dengan GPS terbaru (sebagai fallback)
            if (motorcyclesWithGps && motorcyclesWithGps.length > 0) {
              motorcyclesWithGps.forEach(motor => {
                // Update koordinat motor dengan data GPS terbaru
                if (data && data.latitude && data.longitude) {
                  motor.latitude = data.latitude;
                  motor.longitude = data.longitude;
                  motor.speed_kmph = data.speed_kmph;
                  motor.heading_deg = data.heading_deg;
                }
              });

              // Hanya tampilkan marker motor jika tidak ada GPS utama
              if (!data || !data.latitude || !data.longitude) {
                updateMarkersFromGpsList(motorcyclesWithGps);
              }
            }
          })
          .catch(error => {
            console.error('Terjadi kesalahan saat mengambil data GPS:', error);

            // Update status koneksi menjadi disconnected
            updateConnectionStatus(false, error);

            const gpsDataElement = document.getElementById('gps-data-display');
            if (gpsDataElement) {
              gpsDataElement.textContent = 'Terjadi kesalahan saat memuat data GPS: ' + error.message;
            }
          });
      }

      // Menampilkan marker motor yang tersedia (jika ada)
      updateMarkersFromGpsList(motorcyclesWithGps);

      // Jika tidak ada data motor dengan GPS, tampilkan marker dummy untuk demo
      if (!motorcyclesWithGps || motorcyclesWithGps.length === 0) {
        const dummyData = {
          latitude: -6.2088,
          longitude: 106.8456,
          speed_kmph: 0,
          heading_deg: 0
        };
        updateMainGpsMarker(dummyData);
      }

      // Variable untuk menyimpan interval ID
      let gpsPollingInterval;

      // Fungsi untuk memulai polling
      function startGpsPolling() {
        // Clear interval yang sudah ada (jika ada)
        if (gpsPollingInterval) {
          clearInterval(gpsPollingInterval);
        }

        // Update pertama kali setelah 1 detik
        setTimeout(updateGpsData, 1000);

        // Polling GPS data setiap 2 detik
        gpsPollingInterval = setInterval(updateGpsData, 2000);
      }

      // Fungsi untuk menghentikan polling
      function stopGpsPolling() {
        if (gpsPollingInterval) {
          clearInterval(gpsPollingInterval);
          gpsPollingInterval = null;
        }
      }

      // Event listener untuk mengelola polling berdasarkan visibility halaman
      document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
          // Halaman tidak aktif, hentikan polling
          stopGpsPolling();
          console.log('GPS polling stopped - page is hidden');
        } else {
          // Halaman aktif kembali, mulai polling
          startGpsPolling();
          console.log('GPS polling started - page is visible');
        }
      });

      // Event listener untuk menghentikan polling saat halaman akan ditutup
      window.addEventListener('beforeunload', function() {
        stopGpsPolling();
      });

      // Mulai polling GPS
      startGpsPolling();
    </script>
</x-filament-panels::page>
