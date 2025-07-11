# Use Case Diagram GASSOR (Sewa Motor)

## Aktor
- **Admin** (mengelola sistem, verifikasi user, monitoring)
- **Penyewa** (mencari, booking, pembayaran, kelola profil)
- **Pemilik** (kelola motor, kelola pesanan, laporan keuangan, kelola profil)

---

## Use Case

```mermaid
usecaseDiagram

actor Admin
actor Penyewa
actor Pemilik

Admin --> (Verifikasi User)
Admin --> (Kelola Data Motor)
Admin --> (Kelola Data Pemilik)
Admin --> (Kelola Data Penyewa)
Admin --> (Monitoring Transaksi)
Admin --> (Kelola Laporan)

Penyewa --> (Register/Login)
Penyewa --> (Lihat Katalog Motor)
Penyewa --> (Booking Motor)
Penyewa --> (Pembayaran)
Penyewa --> (Lihat Riwayat Booking)
Penyewa --> (Kelola Profil)
Penyewa --> (Logout)

Pemilik --> (Register/Login)
Pemilik --> (Kelola Motor)
Pemilik --> (Kelola Pesanan)
Pemilik --> (Lihat Laporan Keuangan)
Pemilik --> (Kelola Profil)
Pemilik --> (Logout)

(Admin) --|> (Pemilik) : generalisasi
(Admin) --|> (Penyewa) : generalisasi

(Pembayaran) <|-- (Booking Motor) : <<include>>
(Lihat Riwayat Booking) <|-- (Booking Motor) : <<extend>>
(Kelola Motor) <|-- (Kelola Pesanan) : <<extend>>
(Lihat Laporan Keuangan) <|-- (Kelola Pesanan) : <<include>>

(Penyewa) -- (Register/Login) : association
(Penyewa) -- (Lihat Katalog Motor) : association
(Penyewa) -- (Booking Motor) : association
(Penyewa) -- (Pembayaran) : association
(Penyewa) -- (Lihat Riwayat Booking) : association
(Penyewa) -- (Kelola Profil) : association
(Penyewa) -- (Logout) : association

(Pemilik) -- (Register/Login) : association
(Pemilik) -- (Kelola Motor) : association
(Pemilik) -- (Kelola Pesanan) : association
(Pemilik) -- (Lihat Laporan Keuangan) : association
(Pemilik) -- (Kelola Profil) : association
(Pemilik) -- (Logout) : association

(Admin) -- (Verifikasi User) : association
(Admin) -- (Kelola Data Motor) : association
(Admin) -- (Kelola Data Pemilik) : association
(Admin) -- (Kelola Data Penyewa) : association
(Admin) -- (Monitoring Transaksi) : association
(Admin) -- (Kelola Laporan) : association
```

---

## Penjelasan Relasi Garis pada Diagram

- **Garis panah (→)**: Menunjukkan hubungan langsung (association) antara aktor dan use case. Biasanya digunakan untuk menggambarkan aksi yang dilakukan oleh aktor terhadap sebuah use case. Contoh: `Penyewa --> (Booking Motor)` berarti Penyewa dapat melakukan aksi booking motor.
    - **Makna**: Menandakan interaksi langsung, user dapat mengakses fitur tersebut.
    - **Visual**: Panah dari aktor ke use case.
    - **Contoh lain**: `Pemilik --> (Kelola Motor)` berarti Pemilik dapat mengelola data motor.

- **Garis generalisasi (`--|>`)**: Menunjukkan bahwa aktor yang satu adalah turunan atau memiliki hak akses dari aktor lain. Digunakan untuk mewariskan hak akses atau perilaku dari aktor induk ke aktor anak.
    - **Makna**: Aktor anak mewarisi semua use case dari aktor induk.
    - **Visual**: Garis dengan segitiga terbuka di ujungnya.
    - **Contoh**: `Admin --|> Pemilik` berarti Admin dapat melakukan semua aksi yang bisa dilakukan Pemilik.
    - **Konteks**: Sering digunakan jika ada role yang memiliki hak akses lebih tinggi atau lebih luas.

- **Garis include (`<|-- ... : <<include>>`)**: Menandakan bahwa use case yang satu selalu melibatkan use case lain. Use case yang di-include adalah bagian wajib dari use case utama.
    - **Makna**: Proses utama tidak bisa berjalan tanpa proses yang di-include.
    - **Visual**: Garis panah dengan label <<include>> dari use case yang di-include ke use case utama.
    - **Contoh**: `Pembayaran <|-- Booking Motor : <<include>>` berarti setiap proses booking motor pasti melibatkan pembayaran.
    - **Konteks**: Digunakan untuk modularisasi proses yang selalu terjadi bersamaan.

- **Garis extend (`<|-- ... : <<extend>>`)**: Menandakan bahwa use case yang satu dapat memperluas atau terjadi setelah use case lain. Use case yang di-extend bersifat opsional dan hanya terjadi dalam kondisi tertentu.
    - **Makna**: Proses tambahan yang bisa terjadi jika syarat tertentu terpenuhi.
    - **Visual**: Garis panah dengan label <<extend>> dari use case yang di-extend ke use case utama.
    - **Contoh**: `Lihat Riwayat Booking <|-- Booking Motor : <<extend>>` berarti setelah booking motor, user dapat memilih untuk melihat riwayat booking.
    - **Konteks**: Cocok untuk fitur opsional atau tambahan.

- **Garis association (`-- ... : association`)**: Menandakan relasi langsung antara aktor dan use case, biasanya tanpa panah, hanya garis lurus. Sering digunakan untuk menandai relasi yang tidak melibatkan aksi langsung, misal akses data atau informasi.
    - **Makna**: Hubungan langsung tanpa aksi spesifik, bisa berupa akses data atau informasi.
    - **Visual**: Garis lurus tanpa panah.
    - **Contoh**: `(Pemilik) -- (Lihat Laporan Keuangan) : association` berarti Pemilik dapat mengakses laporan keuangan.
    - **Konteks**: Sering digunakan untuk relasi pasif atau akses data.

---

## Penjelasan Simbol pada Use Case Diagram

Berikut adalah simbol-simbol yang digunakan pada diagram beserta penjelasan dan bagian penggunaannya:

| Simbol                | Nama Relasi     | Penjelasan                                                                 | Digunakan di Bagian        |
|-----------------------|-----------------|---------------------------------------------------------------------------|----------------------------|
| `actor`               | Aktor           | Merepresentasikan peran pengguna sistem (Admin, Penyewa, Pemilik)         | Atas diagram (actor Admin) |
| `(Use Case)`          | Use Case        | Fitur atau proses yang dapat diakses oleh aktor                            | Di dalam diagram           |
| `-->`                 | Association     | Panah dari aktor ke use case, menandakan aksi langsung                    | Admin --> (Verifikasi User)|
| `--|>`                | Generalisasi    | Garis dengan segitiga, menandakan pewarisan hak akses/aksi                | (Admin) --|> (Pemilik)     |
| `<|-- ... <<include>>`| Include         | Panah <<include>>, menandakan proses wajib yang selalu terjadi             | (Pembayaran) <|-- (Booking Motor) |
| `<|-- ... <<extend>>` | Extend          | Panah <<extend>>, menandakan proses tambahan/opsional                     | (Lihat Riwayat Booking) <|-- (Booking Motor) |
| `-- ... : association`| Association     | Garis lurus tanpa panah, relasi langsung tanpa aksi spesifik              | (Pemilik) -- (Lihat Laporan Keuangan) : association |

### Penjelasan Bagian Penggunaan Simbol
- **actor**: Digunakan di bagian awal diagram untuk mendefinisikan peran utama (Admin, Penyewa, Pemilik).
- **(Use Case)**: Digunakan untuk mendefinisikan fitur/proses yang dapat diakses oleh aktor, misal (Booking Motor), (Kelola Motor), dll.
- **-->**: Digunakan untuk menghubungkan aktor dengan use case yang dapat diakses langsung, misal Admin --> (Verifikasi User).
- **--|>**: Digunakan untuk menunjukkan generalisasi antara aktor, misal Admin --|> Pemilik.
- **<|-- ... <<include>>**: Digunakan untuk menandai proses wajib yang selalu terjadi dalam use case utama, misal (Pembayaran) <|-- (Booking Motor) : <<include>>.
- **<|-- ... <<extend>>**: Digunakan untuk menandai proses tambahan/opsional, misal (Lihat Riwayat Booking) <|-- (Booking Motor) : <<extend>>.
- **-- ... : association**: Digunakan untuk relasi langsung tanpa aksi spesifik, misal (Pemilik) -- (Lihat Laporan Keuangan) : association.

---

### Tabel Ringkasan Simbol Garis

| Simbol         | Nama Relasi     | Makna Utama                                                                 | Visualisasi Diagram         |
|----------------|-----------------|-----------------------------------------------------------------------------|-----------------------------|
| `-->`          | Association     | Interaksi langsung, aksi yang dilakukan aktor ke use case                   | Panah dari aktor ke use case|
| `--|>`         | Generalisasi    | Pewarisan hak akses/aksi dari aktor induk ke aktor anak                     | Garis dengan segitiga       |
| `<|-- ... <<include>>` | Include         | Proses wajib yang selalu terjadi dalam use case utama                        | Panah <<include>>           |
| `<|-- ... <<extend>>`  | Extend          | Proses tambahan/opsional yang terjadi jika syarat tertentu terpenuhi         | Panah <<extend>>            |
| `-- ... : association`| Association     | Relasi langsung tanpa aksi spesifik, akses data/informasi                   | Garis lurus tanpa panah     |

---

### Studi Kasus pada Diagram

- **Penyewa --> (Booking Motor)**: Penyewa dapat melakukan booking motor secara langsung.
- **(Pembayaran) <|-- (Booking Motor) : <<include>>**: Setiap booking motor pasti melibatkan pembayaran, tidak bisa dipisahkan.
- **(Lihat Riwayat Booking) <|-- (Booking Motor) : <<extend>>**: Setelah booking, user bisa memilih untuk melihat riwayat booking, tapi tidak wajib.
- **Admin --|> Pemilik**: Admin mewarisi hak akses Pemilik, sehingga bisa melakukan aksi yang sama.
- **Pemilik -- (Lihat Laporan Keuangan) : association**: Pemilik dapat mengakses laporan keuangan secara langsung.

---

### Tips Membaca Diagram
- Perhatikan arah panah untuk mengetahui siapa yang melakukan aksi dan ke mana aksi tersebut diarahkan.
- Cek label <<include>> dan <<extend>> untuk memahami proses wajib dan opsional.
- Generalisasi penting untuk memahami hak akses dan pewarisan fitur antar aktor.
- Association tanpa panah biasanya untuk akses data atau fitur pasif.

---

## Catatan
- Diagram ini dapat divisualisasikan dengan tools seperti draw.io, Lucidchart, atau mermaid live editor
- Relasi dan use case diambil dari hasil pengujian, struktur folder, dan flow aplikasi web_gassor
- Untuk detail lebih lanjut, silakan tambahkan use case spesifik sesuai kebutuhan bisnis
