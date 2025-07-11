# Use Case Diagram GASSOR (Sewa Motor)

## Aktor
- **Admin**: Mengelola sistem, verifikasi user, monitoring transaksi, kelola data motor dan user
- **Penyewa**: Registrasi, login, mencari motor, booking, pembayaran, kelola profil, cek riwayat
- **Pemilik**: Registrasi, login, kelola motor, kelola pesanan, cek laporan keuangan, kelola profil

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

## Penjelasan Relasi

- **include**: Relasi ini digunakan ketika suatu use case selalu melibatkan use case lain sebagai bagian dari prosesnya. Contoh pada diagram:
    - `(Pembayaran) <|-- (Booking Motor) : <<include>>` artinya setiap kali proses booking motor dilakukan, proses pembayaran pasti terjadi. 
    - `(Lihat Laporan Keuangan) <|-- (Kelola Pesanan) : <<include>>` artinya setiap kali pemilik mengelola pesanan, laporan keuangan akan selalu ter-update.
    - **Simbol**: `<|-- ... : <<include>>`
    - **Makna**: Proses wajib, tidak bisa dipisahkan dari use case utama.

- **extend**: Relasi ini digunakan ketika suatu use case dapat memperluas atau menambah proses lain secara opsional, tergantung kondisi tertentu. Contoh pada diagram:
    - `(Lihat Riwayat Booking) <|-- (Booking Motor) : <<extend>>` artinya setelah booking motor, user bisa memilih untuk melihat riwayat booking, tapi tidak wajib.
    - `(Kelola Motor) <|-- (Kelola Pesanan) : <<extend>>` artinya kelola pesanan dapat memperluas fitur kelola motor jika ada perubahan status motor.
    - **Simbol**: `<|-- ... : <<extend>>`
    - **Makna**: Proses tambahan/opsional, terjadi jika syarat tertentu terpenuhi.

- **association**: Relasi ini digunakan untuk menggambarkan hubungan langsung antara aktor dan use case, biasanya berupa aksi atau akses fitur. Contoh pada diagram:
    - `Penyewa -- (Booking Motor) : association` artinya Penyewa dapat melakukan booking motor secara langsung.
    - `Pemilik -- (Kelola Motor) : association` artinya Pemilik dapat mengelola data motor.
    - `Admin -- (Verifikasi User) : association` artinya Admin dapat melakukan verifikasi user.
    - **Simbol**: `-- ... : association` atau `-->`
    - **Makna**: Interaksi langsung antara aktor dan use case.

- **generalisasi**: Relasi ini digunakan untuk menunjukkan bahwa aktor yang satu mewarisi hak akses atau fitur dari aktor lain. Contoh pada diagram:
    - `(Admin) --|> (Pemilik) : generalisasi` artinya Admin memiliki hak akses yang sama seperti Pemilik.
    - `(Admin) --|> (Penyewa) : generalisasi` artinya Admin juga bisa mengakses fitur-fitur milik Penyewa.
    - **Simbol**: `--|>`
    - **Makna**: Pewarisan hak akses/aksi dari aktor induk ke aktor anak.

---

## Catatan
- Diagram ini dapat divisualisasikan dengan tools seperti draw.io, Lucidchart, atau mermaid live editor
- Relasi dan use case diambil dari hasil pengujian, struktur folder, dan flow aplikasi web_gassor
- Untuk detail lebih lanjut, silakan tambahkan use case spesifik sesuai kebutuhan bisnis
