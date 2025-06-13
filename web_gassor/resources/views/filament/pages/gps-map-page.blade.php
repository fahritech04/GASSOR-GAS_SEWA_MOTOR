<x-filament-panels::page>
    @if(isset($this->gpsData) && $this->gpsData)
        <div class="p-2 bg-green-100 text-green-800 rounded mb-2">
            <b>GPS Data (Initial):</b>
            <pre class="text-xs">{{ json_encode($this->gpsData, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif
    @if(isset($this->motorcyclesWithGps) && count($this->motorcyclesWithGps))
        <div class="p-2 bg-blue-100 text-blue-800 rounded mb-2">
            <b>Daftar Motor dengan GPS IoT:</b>
            <ul class="text-xs list-disc pl-4">
                @foreach($this->motorcyclesWithGps as $motor)
                    <li>
                        <b>{{ $motor->name }}</b> - {{ $motor->vehicle_number_plate }}
                        ({{ $motor->motorcycle_type }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
      .leaflet-map { width: 100%; height: 380px; border-radius: 18px; }
    </style>
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
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
      <main id="Details" class="relative flex flex-col rounded-t-[40px] py-5 pb-[10px] gap-4 bg-white z-10" style="position:relative; z-index:10;">
        <div id="Title" class="flex items-center justify-between gap-2 px-5">
          <h1 class="font-bold text-[22px] leading-[33px]">Lokasi Kendaraan Sekarang</h1>
        </div>
        <hr class="border-[#F1F2F6] mx-5" />
        <div id="TabsContent" class="px-5">
          <div id="Bonus-Tab" class="flex flex-col gap-5 tab-content">
            <div class="flex flex-col gap-4">
              <div class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3 hover:border-[#91BF77] transition-all duration-300">
                <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden">
                  <img src="/assets/images/thumbnails/bonus-1.png" class="object-cover w-full h-full" alt="thumbnails" />
                </div>
                <div>
                  <p class="font-semibold">Honda Beat</p>
                  <p class="text-sm text-gassor-grey">Super Fast • 2 Max</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="Features" class="grid grid-cols-2 gap-x-[10px] gap-y-4 px-5">
          <div class="flex items-center gap-[6px]">
            <img src="/assets/images/icons/location.svg" class="w-[26px] h-[26px] flex shrink-0" alt="icon" />
            <p class="text-gassor-grey">Wilayah Bojongsoang</p>
          </div>
          <div class="flex items-center gap-[6px]">
            <img src="/assets/images/icons/3dcube.svg" class="w-[26px] h-[26px] flex shrink-0" alt="icon" />
            <p class="text-gassor-grey">Kategori Matic</p>
          </div>
          <div class="flex items-center gap-[6px]">
            <img src="/assets/images/icons/profile-2user.svg" class="w-[26px] h-[26px] flex shrink-0" alt="icon" />
            <p class="text-gassor-grey">2 Max</p>
          </div>
          <div class="flex items-center gap-[6px]">
            <img src="/assets/images/icons/shield-tick.svg" class="w-[26px] h-[26px] flex shrink-0" alt="icon" />
            <p class="text-gassor-grey">Privacy 100%</p>
          </div>
        </div>
      </main>
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
      L.control.zoom({ position: 'topright' }).addTo(map);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);
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
      setInterval(() => {
        $.getJSON('/filament/api/gps', function(data) {
          updateMarker(data);
        });
      }, 1000);
    </script>
</x-filament-panels::page>
