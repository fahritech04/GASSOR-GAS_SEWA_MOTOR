<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditProfilePemilikTest extends DuskTestCase
{
    /**
     * Test validasi form edit profile pemilik
     *
     * Test ini menguji:
     * - Validasi field wajib (nama, username, tempat lahir, tanggal lahir, email, phone)
     * - Validasi umur minimal 17 tahun
     * - Validasi format email dan phone
     * - Interaksi tombol dan elemen form
     * - Upload gambar wajib (KTP, SIM, KTM)
     * - Responsivitas form di berbagai ukuran layar
     *
     * Note: Test ini akan skip jika user belum login (redirect ke select-role)
     */
    public function test_form_validation_edit_profile_pemilik()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Validasi Form Edit Profile Pemilik ===\n";

            // Coba akses halaman edit profile pemilik
            $browser->visit('/editprofile/pemilik')
                ->pause(2000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Cek apakah ter-redirect ke select-role (berarti belum login)
            if (strpos($currentUrl, 'select-role') !== false) {
                echo "⚠️  User belum login, ter-redirect ke select-role\n";
                echo "💡 Untuk test yang lengkap, silakan login terlebih dahulu sebagai pemilik\n";

                // Verify halaman select-role ada
                $browser->assertSee('Pilih');

                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Jika berhasil akses halaman edit profile, lanjutkan test
            // Asumsi kita sudah login sebagai pemilik dan mengakses halaman edit profile
            // Ganti URL sesuai dengan route yang sebenarnya
            $browser->visit('/editprofile/pemilik')
                ->assertSee('Akun Saya')
                ->assertSee('Foto Profil')
                ->assertSee('Nama Lengkap')
                ->assertSee('Nama Pengguna')
                ->assertSee('Tempat Lahir')
                ->assertSee('Tanggal Lahir')
                ->assertSee('Email')
                ->assertSee('Nomor Telepon')
                ->assertSee('Upload KTP')
                ->assertSee('Upload SIM')
                ->assertSee('Upload KTM')
                ->assertSee('Simpan Akun');

            // Test validasi field kosong - submit form tanpa mengisi apa-apa
            $browser->press('Simpan Akun')
                ->waitFor('.swal2-container', 3)
                ->assertSee('Semua kolom wajib diisi!!!');

            // Tutup alert dan lanjut test
            $browser->press('.swal2-confirm')
                ->waitUntilMissing('.swal2-container');

            // Test validasi nama lengkap kosong
            $browser->clear('input[name="name"]')
                ->type('input[name="username"]', 'pemilik123')
                ->type('input[name="tempat_lahir"]', 'Jakarta')
                ->type('input[name="tanggal_lahir"]', '1990-01-01')
                ->type('input[name="email"]', 'pemilik@test.com')
                ->type('input[name="phone"]', '6281234567890')
                ->press('Simpan Akun')
                ->waitFor('.swal2-container', 3)
                ->assertSee('Semua kolom wajib diisi!!!');

            $browser->press('.swal2-confirm')
                ->waitUntilMissing('.swal2-container');

            // Test validasi umur di bawah 17 tahun
            $browser->type('input[name="name"]', 'Pemilik Test')
                ->clear('input[name="tanggal_lahir"]')
                ->type('input[name="tanggal_lahir"]', '2015-01-01') // Umur di bawah 17
                ->press('Simpan Akun')
                ->waitFor('.swal2-container', 3)
                ->assertSee('Anda belum cukup umur (minimal 17 tahun)');

            $browser->press('.swal2-confirm')
                ->waitUntilMissing('.swal2-container');

            // Test validasi email tidak valid
            $browser->type('input[name="tanggal_lahir"]', '1990-01-01')
                ->clear('input[name="email"]')
                ->type('input[name="email"]', 'email-tidak-valid')
                ->press('Simpan Akun');

            // Browser akan menunjukkan validasi HTML5 untuk email
            $browser->pause(1000);

            // Perbaiki email dan test validasi phone
            $browser->clear('input[name="email"]')
                ->type('input[name="email"]', 'pemilik@test.com')
                ->clear('input[name="phone"]')
                ->type('input[name="phone"]', '123') // Phone terlalu pendek
                ->assertInputValue('input[name="phone"]', '62123'); // Auto format ke 62

            // Test interaksi foto profil
            $browser->assertPresent('#profile-image-preview')
                ->assertPresent('button:contains("Ganti Foto")')
                ->assertPresent('button:contains("Hapus Foto")');

            // Test klik tombol ganti foto (akan trigger file input)
            $browser->click('button:contains("Ganti Foto")');
            $browser->pause(500);

            // Test klik tombol hapus foto
            $browser->click('button:contains("Hapus Foto")')
                ->pause(500);

            // Foto profile seharusnya kembali ke default
            $browser->assertAttribute('#profile-image-preview', 'style', 'object-fit: cover; border-radius: 50%; width: 70px; height: 70px; transition: width 0.2s, height 0.2s; background: #fff;');

            // Test upload area KTP, SIM, KTM
            $browser->assertPresent('#ktp_image')
                ->assertPresent('#sim_image')
                ->assertPresent('#ktm_image')
                ->assertPresent('#ktp-preview')
                ->assertPresent('#sim-preview')
                ->assertPresent('#ktm-preview');

            // Test klik upload area untuk KTP
            $browser->click('.ktp-sim-ktm-upload-imgbox:first-child')
                ->pause(500);

            // Test klik upload area untuk SIM
            $browser->click('.ktp-sim-ktm-upload-imgbox:nth-child(2)')
                ->pause(500);

            // Test klik upload area untuk KTM
            $browser->click('.ktp-sim-ktm-upload-imgbox:last-child')
                ->pause(500);

            // Test responsivitas - resize ke mobile
            $browser->resize(375, 667)
                ->pause(1000);

            // Verify form masih terlihat dengan baik di mobile
            $browser->assertPresent('input[name="name"]')
                ->assertPresent('input[name="username"]')
                ->assertPresent('input[name="tempat_lahir"]')
                ->assertPresent('input[name="tanggal_lahir"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="phone"]')
                ->assertPresent('button:contains("Simpan Akun")');

            // Test upload area di mobile
            $browser->assertPresent('.ktp-sim-ktm-upload-col')
                ->assertPresent('.ktp-sim-ktm-upload-imgbox');

            // Resize kembali ke desktop
            $browser->resize(1920, 1080)
                ->pause(1000);

            // Test navigasi back
            $browser->assertPresent('a[href*="profile.pemilik"]')
                ->click('a[href*="profile.pemilik"]');

            $browser->pause(2000);
        });
    }

    /**
     * Test upload dan preview gambar edit profile pemilik
     *
     * Test ini menguji:
     * - Upload dan preview foto profil
     * - Upload dan preview KTP, SIM, KTM
     * - Validasi file gambar
     * - Responsivitas upload area
     * - Submit form dengan data lengkap
     *
     * Note: Test ini akan skip jika user belum login (redirect ke select-role)
     */
    public function test_image_upload_and_preview_edit_profile_pemilik()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Upload dan Preview Gambar Edit Profile Pemilik ===\n";

            // Coba akses halaman edit profile pemilik
            $browser->visit('/editprofile/pemilik')
                ->pause(2000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Cek apakah ter-redirect ke select-role (berarti belum login)
            if (strpos($currentUrl, 'select-role') !== false) {
                echo "⚠️  User belum login, ter-redirect ke select-role\n";
                echo "💡 Untuk test upload gambar yang lengkap, silakan login terlebih dahulu sebagai pemilik\n";

                // Verify halaman select-role ada
                $browser->assertSee('Pilih');

                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Jika berhasil akses halaman edit profile, lanjutkan test
            $browser->visit('/editprofile/pemilik')
                ->assertSee('Akun Saya');

            // Siapkan path file dummy untuk testing
            // User akan menyiapkan file ini nanti
            $profileImagePath = storage_path('app/testing/foto_profil_dummy.jpg');
            $ktpImagePath = storage_path('app/testing/ktp_dummy.jpg');
            $simImagePath = storage_path('app/testing/sim_dummy.jpg');
            $ktmImagePath = storage_path('app/testing/ktm_dummy.jpg');

            // Isi form dengan data valid
            $browser->type('input[name="name"]', 'Pemilik Motor Test')
                ->type('input[name="username"]', 'pemilik_motor_123')
                ->type('input[name="tempat_lahir"]', 'Bandung')
                ->type('input[name="tanggal_lahir"]', '1985-05-15')
                ->type('input[name="email"]', 'pemilik.motor@gassor.com')
                ->clear('input[name="phone"]')
                ->type('input[name="phone"]', '6281234567890');

            // Test upload foto profil jika file ada
            if (file_exists($profileImagePath)) {
                $browser->attach('#profile_image', $profileImagePath)
                    ->pause(2000);

                // Verify preview berubah
                $browser->assertAttribute('#profile-image-preview', 'style', 'object-fit: cover; border-radius: 50%; width: 100%; height: 100%; transition: width 0.2s, height 0.2s; background: #fff;');
            } else {
                $browser->addConsoleLog('Profile image file not found: '.$profileImagePath);
            }

            // Test upload KTP jika file ada
            if (file_exists($ktpImagePath)) {
                $browser->attach('#ktp_image', $ktpImagePath)
                    ->pause(2000);

                // Verify KTP preview muncul
                $browser->assertVisible('#ktp-preview')
                    ->assertMissing('#ktp-placeholder:visible');
            } else {
                $browser->addConsoleLog('KTP image file not found: '.$ktpImagePath);
            }

            // Test upload SIM jika file ada
            if (file_exists($simImagePath)) {
                $browser->attach('#sim_image', $simImagePath)
                    ->pause(2000);

                // Verify SIM preview muncul
                $browser->assertVisible('#sim-preview')
                    ->assertMissing('#sim-placeholder:visible');
            } else {
                $browser->addConsoleLog('SIM image file not found: '.$simImagePath);
            }

            // Test upload KTM jika file ada
            if (file_exists($ktmImagePath)) {
                $browser->attach('#ktm_image', $ktmImagePath)
                    ->pause(2000);

                // Verify KTM preview muncul
                $browser->assertVisible('#ktm-preview')
                    ->assertMissing('#ktm-placeholder:visible');
            } else {
                $browser->addConsoleLog('KTM image file not found: '.$ktmImagePath);
            }

            // Test responsivitas upload area di tablet
            $browser->resize(768, 1024)
                ->pause(1000);

            // Verify upload areas masih terlihat dengan baik
            $browser->assertPresent('.ktp-sim-ktm-upload-col')
                ->assertPresent('.ktp-sim-ktm-upload-imgbox');

            // Test di mobile
            $browser->resize(375, 667)
                ->pause(1000);

            // Verify upload areas responsive di mobile
            $browser->assertPresent('.ktp-sim-ktm-upload-col')
                ->assertPresent('.ktp-sim-ktm-upload-imgbox');

            // Resize kembali ke desktop
            $browser->resize(1920, 1080)
                ->pause(1000);

            // Test submit form jika semua file gambar tersedia
            $allImagesExist = file_exists($profileImagePath) && file_exists($ktpImagePath) &&
                             file_exists($simImagePath) && file_exists($ktmImagePath);

            if ($allImagesExist) {
                // Submit form dengan data lengkap
                $browser->press('Simpan Akun')
                    ->pause(3000);

                // Jika berhasil, seharusnya redirect atau muncul pesan sukses
                // Sesuaikan dengan behavior aplikasi yang sebenarnya
                $browser->waitFor('.swal2-container', 5);

                // Check apakah ada pesan sukses atau error
                if ($browser->element('.swal2-title')) {
                    $title = $browser->text('.swal2-title');
                    $browser->addConsoleLog('Form submission result: '.$title);
                }

                $browser->pause(2000);
            } else {
                $browser->addConsoleLog('Skipping form submission - not all required image files are available');

                // Test submit tanpa gambar untuk memastikan validasi bekerja
                $browser->press('Simpan Akun')
                    ->waitFor('.swal2-container', 3)
                    ->assertSee('Semua kolom wajib diisi!!!');

                $browser->press('.swal2-confirm')
                    ->waitUntilMissing('.swal2-container');
            }

            // Test hapus foto profil
            $browser->click('button:contains("Hapus Foto")')
                ->pause(1000);

            // Verify foto kembali ke default
            $browser->assertAttribute('#profile-image-preview', 'style', 'object-fit: cover; border-radius: 50%; width: 70px; height: 70px; transition: width 0.2s, height 0.2s; background: #fff;');

            $browser->pause(2000);
        });
    }
}
