<x-filament-panels::page>
    @if(isset($this->gpsData) && $this->gpsData)
        <div class="p-2 bg-orange-100 text-orange-800 rounded mb-2">
            <b>GPS Data (Initial):</b>
            <pre class="text-xs">{{ json_encode($this->gpsData, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif
    @if(isset($this->motorcyclesWithGps) && count($this->motorcyclesWithGps))
        <div class="p-2 bg-blue-100 text-blue-800 rounded mb-2">
            <b>Daftar Motor (Lagi Disewa & Ada GPS IoT):</b>
            <ul class="text-xs list-disc pl-4">
                @foreach($this->motorcyclesWithGps as $motor)
                    <li>
                        <b>{{ $motor->name }}</b> - {{ $motor->vehicle_number_plate }} ({{ $motor->motorcycle_type }})
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
      let markers = [];
      L.control.zoom({ position: 'topright' }).addTo(map);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      function clearMarkers() {
        markers.forEach(m => map.removeLayer(m));
        markers = [];
      }

      function updateMarkersFromGpsList(motorcycles) {
        clearMarkers();
        if (!motorcycles || motorcycles.length === 0) return;
        motorcycles.forEach(function(motor) {
          // Pastikan setiap motor punya data latitude & longitude
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
          const marker = L.marker([motor.latitude, motor.longitude], {icon: motorIcon}).addTo(map).bindPopup(popup);
          markers.push(marker);
        });
        if (markers.length > 0) {
          map.setView(markers[0].getLatLng(), 15);
        }
      }

      // Ambil data motor dari blade (PHP ke JS)
      const motorcyclesWithGps = @json($this->motorcyclesWithGps);
      // Jika backend sudah mengirim data GPS per motor, gunakan fungsi ini:
      updateMarkersFromGpsList(motorcyclesWithGps);
    </script>
</x-filament-panels::page>
