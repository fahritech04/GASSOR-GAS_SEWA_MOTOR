#include <WiFi.h>
#include <TinyGPS++.h>
#include <Firebase_ESP_Client.h>

// ----------- GANTI DENGAN KONFIGURASI ANDA -----------
#define WIFI_SSID "AlexFurry"
#define WIFI_PASSWORD "00000000"

#define DATABASE_URL "https://gpsiot-cf1d6-default-rtdb.asia-southeast1.firebasedatabase.app"
#define DATABASE_SECRET "zJGSaE8g6JfJWmtINaUf2qTGSUGmFtnhkPSubE15"
// ------------------------------------------------------

// Objek GPS dan serial
TinyGPSPlus gps;
HardwareSerial GPSSerial(2); // RX=16, TX=17

// Firebase objects
FirebaseData fbdo;
FirebaseAuth auth;
FirebaseConfig config;

unsigned long lastDataTime = 0;
unsigned long lastSendTime = 0;
const unsigned long SEND_INTERVAL = 1000; // ms

void connectToWiFi() {
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Menghubungkan ke WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\n✅ WiFi Connected");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
}

void setupFirebase() {
  config.database_url = DATABASE_URL;
  config.signer.tokens.legacy_token = DATABASE_SECRET;

  Firebase.begin(&config, &auth);
  Firebase.reconnectWiFi(true);
}

void sendDataToFirebase() {
  if (gps.location.isValid()) {
    FirebaseJson json;

    double lat = gps.location.lat();
    double lng = gps.location.lng();
    double speed = gps.speed.kmph();
    double alt = gps.altitude.meters();
    double heading = gps.course.deg();
    int satelit = gps.satellites.isValid() ? gps.satellites.value() : -1;
    float hdop = gps.hdop.isValid() ? gps.hdop.hdop() : -1.0;

    // Format waktu
    String waktu = "Not available";
    if (gps.time.isValid() && gps.date.isValid()) {
      int hour = gps.time.hour() + 7;
      if (hour >= 24) hour -= 24;

      char buf[30];
      sprintf(buf, "%02d:%02d:%02d %02d/%02d/%04d", hour, gps.time.minute(), gps.time.second(),
              gps.date.day(), gps.date.month(), gps.date.year());
      waktu = String(buf);
    }

    // Buat link Google Maps
    String mapsLink = "https://maps.google.com/?q=" + String(lat, 6) + "," + String(lng, 6);

    // Simpan ke JSON
    json.set("latitude", lat);
    json.set("longitude", lng);
    json.set("speed_kmph", speed);
    json.set("altitude_m", alt);
    json.set("heading_deg", heading);
    json.set("satelit", satelit);
    json.set("hdop", hdop);
    json.set("timestamp", waktu);
    json.set("maps_url", mapsLink);

    // Path penyimpanan
    String path = "/tracking";

    // Kirim ke Firebase
    if (Firebase.RTDB.setJSON(&fbdo, path.c_str(), &json)) {
      Serial.println("✅ Data berhasil dikirim ke Firebase.");
    } else {
      Serial.print("❌ Gagal mengirim data: ");
      Serial.println(fbdo.errorReason());
    }

  } else {
    Serial.println("📡 GPS data belum valid.");
  }
}

void setup() {
  Serial.begin(115200);
  GPSSerial.begin(9600, SERIAL_8N1, 16, 17);
  Serial.println("=== Motor Tracker + Firebase ===");

  connectToWiFi();
  setupFirebase();
}

void loop() {
  // Baca data dari GPS
  while (GPSSerial.available() > 0) {
    char c = GPSSerial.read();
    if (gps.encode(c)) {
      lastDataTime = millis();
    }
  }

  // Timeout jika tidak ada data GPS
  if (millis() - lastDataTime > 5000) {
    Serial.println("🚫 Tidak ada data GPS atau modul tidak terhubung.");
    delay(2000);
    return;
  }

  // Kirim data setiap interval
  if (millis() - lastSendTime > SEND_INTERVAL) {
    sendDataToFirebase();
    lastSendTime = millis();
  }
}
