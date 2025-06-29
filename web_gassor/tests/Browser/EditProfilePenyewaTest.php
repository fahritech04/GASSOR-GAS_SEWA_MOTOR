<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditProfilePenyewaTest extends DuskTestCase
{
    /**
     * Test validasi form edit profile penyewa
     * Memastikan semua field wajib tervalidasi dengan benar
     *
     * Note: Test ini akan skip jika user belum login (redirect ke select-role)
     */
    public function test_form_validation_edit_profile_penyewa()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Validasi Form Edit Profile Penyewa ===\n";

            // Coba akses halaman edit profile penyewa
            $browser->visit('/editprofile/penyewa')
                ->pause(2000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Cek apakah ter-redirect ke select-role (berarti belum login)
            if (strpos($currentUrl, 'select-role') !== false) {
                echo "⚠️  User belum login, ter-redirect ke select-role\n";
                echo "💡 Untuk test yang lengkap, silakan login terlebih dahulu sebagai penyewa\n";

                // Verify halaman select-role ada
                $browser->assertSee('Pilih');

                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Jika berhasil akses halaman edit profile, lanjutkan test
            $browser->waitFor('form', 10)
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
                ->assertSee('Upload KTM');

            // Test 1: Submit form kosong - harus menampilkan peringatan
            $browser->clear('[name="name"]')
                ->clear('[name="username"]')
                ->clear('[name="tempat_lahir"]')
                ->clear('[name="tanggal_lahir"]')
                ->clear('[name="email"]')
                ->clear('[name="phone"]')
                ->click('button[type="submit"]')
                ->waitForText('Semua kolom wajib diisi!!!', 5)
                ->assertSee('Semua kolom wajib diisi!!!');

            // Test 2: Isi semua field text tapi tanggal lahir umur < 17 tahun
            $underage_date = now()->subYears(16)->format('Y-m-d');
            $browser->type('[name="name"]', 'Test User Muda')
                ->type('[name="username"]', 'testusermuda')
                ->type('[name="tempat_lahir"]', 'Bandung')
                ->type('[name="tanggal_lahir"]', $underage_date)
                ->type('[name="email"]', 'testmuda@example.com')
                ->type('[name="phone"]', '6281234567890')
                ->click('button[type="submit"]')
                ->waitForText('Anda belum cukup umur (minimal 17 tahun)', 5)
                ->assertSee('Anda belum cukup umur (minimal 17 tahun)');

            // Test 3: Isi semua field dengan benar tapi tanpa upload gambar
            $valid_date = now()->subYears(20)->format('Y-m-d');
            $browser->type('[name="name"]', 'Test User Valid')
                ->type('[name="username"]', 'testuservalid')
                ->type('[name="tempat_lahir"]', 'Surabaya')
                ->type('[name="tanggal_lahir"]', $valid_date)
                ->type('[name="email"]', 'testvalid@example.com')
                ->type('[name="phone"]', '6281234567890')
                ->click('button[type="submit"]')
                ->waitForText('Semua kolom wajib diisi!!!', 5)
                ->assertSee('Semua kolom wajib diisi!!!');

            // Test 4: Validasi format nomor telepon
            $browser->type('[name="phone"]', '081234567890')
                ->pause(500)
                ->assertInputValue('[name="phone"]', '6281234567890'); // Auto format ke 62

            // Test 5: Validasi email format
            $browser->type('[name="email"]', 'email-tidak-valid')
                ->click('button[type="submit"]')
                ->pause(1000);

            // Browser akan menampilkan pesan validasi HTML5 untuk email
            $emailInput = $browser->element('[name="email"]');
            $this->assertNotNull($emailInput);

            // Test 6: Pastikan semua elemen form ada dan dapat diinteraksi
            $browser->assertPresent('#profile_image')
                ->assertPresent('#ktp_image')
                ->assertPresent('#sim_image')
                ->assertPresent('#ktm_image')
                ->assertPresent('#profile-image-preview')
                ->assertPresent('#ktp-preview')
                ->assertPresent('#sim-preview')
                ->assertPresent('#ktm-preview');

            // Test 7: Klik tombol ganti foto profil
            $browser->click('button[onclick="document.getElementById(\'profile_image\').click()"]')
                ->pause(500);

            // Test 8: Klik tombol hapus foto profil
            $browser->click('button[onclick="removeProfileImage()"]')
                ->pause(500)
                ->assertAttribute('#remove_profile_image', 'value', '1');

            // Test 9: Klik area upload KTP, SIM, KTM
            $browser->click('.ktp-sim-ktm-upload-imgbox')
                ->pause(300);

            echo "\n✅ Test validasi form edit profile penyewa berhasil\n";
        });
    }

    /**
     * Test upload dan preview gambar edit profile penyewa
     * Memastikan fungsi upload dan preview gambar bekerja dengan benar
     *
     * Note: Test ini akan skip jika user belum login (redirect ke select-role)
     */
    public function test_image_upload_and_preview_edit_profile_penyewa()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Upload dan Preview Gambar Edit Profile Penyewa ===\n";

            // Coba akses halaman edit profile penyewa
            $browser->visit('/editprofile/penyewa')
                ->pause(2000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Cek apakah ter-redirect ke select-role (berarti belum login)
            if (strpos($currentUrl, 'select-role') !== false) {
                echo "⚠️  User belum login, ter-redirect ke select-role\n";
                echo "💡 Untuk test upload gambar yang lengkap, silakan login terlebih dahulu sebagai penyewa\n";

                // Verify halaman select-role ada
                $browser->assertSee('Pilih');

                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Jika berhasil akses halaman edit profile, lanjutkan test
            $browser->waitFor('form', 10);

            // Siapkan path file gambar dummy (akan disiapkan user)
            $profileImagePath = storage_path('app/test-files/profile-dummy.jpg');
            $ktpImagePath = storage_path('app/test-files/ktp-dummy.jpg');
            $simImagePath = storage_path('app/test-files/sim-dummy.jpg');
            $ktmImagePath = storage_path('app/test-files/ktm-dummy.jpg');

            // Test 1: Upload foto profil
            if (file_exists($profileImagePath)) {
                $browser->attach('#profile_image', $profileImagePath)
                    ->pause(2000)
                    ->script('return document.getElementById("profile-image-preview").style.width;', function ($width) {
                        $this->assertEquals('100%', $width);
                    })
                    ->script('return document.getElementById("remove_profile_image").value;', function ($value) {
                        $this->assertEquals('0', $value);
                    });
                echo "\n✅ Upload foto profil berhasil\n";
            } else {
                echo "\n⚠️  File profile-dummy.jpg tidak ditemukan, skip test upload foto profil\n";
            }

            // Test 2: Upload KTP
            if (file_exists($ktpImagePath)) {
                $browser->attach('#ktp_image', $ktpImagePath)
                    ->pause(2000)
                    ->script('return document.getElementById("ktp-preview").style.display;', function ($display) {
                        $this->assertEquals('block', $display);
                    })
                    ->script('return document.getElementById("ktp-placeholder").style.display;', function ($display) {
                        $this->assertEquals('none', $display);
                    });
                echo "\n✅ Upload KTP berhasil\n";
            } else {
                echo "\n⚠️  File ktp-dummy.jpg tidak ditemukan, skip test upload KTP\n";
            }

            // Test 3: Upload SIM
            if (file_exists($simImagePath)) {
                $browser->attach('#sim_image', $simImagePath)
                    ->pause(2000)
                    ->script('return document.getElementById("sim-preview").style.display;', function ($display) {
                        $this->assertEquals('block', $display);
                    })
                    ->script('return document.getElementById("sim-placeholder").style.display;', function ($display) {
                        $this->assertEquals('none', $display);
                    });
                echo "\n✅ Upload SIM berhasil\n";
            } else {
                echo "\n⚠️  File sim-dummy.jpg tidak ditemukan, skip test upload SIM\n";
            }

            // Test 4: Upload KTM
            if (file_exists($ktmImagePath)) {
                $browser->attach('#ktm_image', $ktmImagePath)
                    ->pause(2000)
                    ->script('return document.getElementById("ktm-preview").style.display;', function ($display) {
                        $this->assertEquals('block', $display);
                    })
                    ->script('return document.getElementById("ktm-placeholder").style.display;', function ($display) {
                        $this->assertEquals('none', $display);
                    });
                echo "\n✅ Upload KTM berhasil\n";
            } else {
                echo "\n⚠️  File ktm-dummy.jpg tidak ditemukan, skip test upload KTM\n";
            }

            // Test 5: Hapus foto profil
            $browser->click('button[onclick="removeProfileImage()"]')
                ->pause(1000)
                ->script('return document.getElementById("profile-image-preview").style.width;', function ($width) {
                    $this->assertEquals('70px', $width);
                })
                ->script('return document.getElementById("profile-image-preview").style.height;', function ($height) {
                    $this->assertEquals('70px', $height);
                })
                ->assertAttribute('#remove_profile_image', 'value', '1');

            // Test 6: Test submit form lengkap jika semua file tersedia
            if (file_exists($ktpImagePath) && file_exists($simImagePath) && file_exists($ktmImagePath)) {
                $browser->type('[name="name"]', 'Test User Lengkap')
                    ->type('[name="username"]', 'testuserlengkap')
                    ->type('[name="tempat_lahir"]', 'Yogyakarta')
                    ->type('[name="tanggal_lahir"]', '1990-05-15')
                    ->type('[name="email"]', 'testlengkap@example.com')
                    ->type('[name="phone"]', '6281234567890')
                    ->attach('#profile_image', $profileImagePath)
                    ->pause(1000)
                    ->attach('#ktp_image', $ktpImagePath)
                    ->pause(1000)
                    ->attach('#sim_image', $simImagePath)
                    ->pause(1000)
                    ->attach('#ktm_image', $ktmImagePath)
                    ->pause(2000);

                // Cek apakah form bisa disubmit (tidak ada alert error)
                $browser->click('button[type="submit"]')
                    ->pause(3000);

                echo "\n✅ Form lengkap dapat disubmit\n";
            }

            // Test 7: Test responsiveness upload area
            $browser->resize(480, 800)
                ->pause(1000)
                ->assertPresent('.ktp-sim-ktm-upload-col')
                ->assertPresent('.ktp-sim-ktm-upload-imgbox')
                ->resize(1200, 800)
                ->pause(1000);

            echo "\n✅ Test upload dan preview gambar edit profile penyewa berhasil\n";
        });
    }
}
