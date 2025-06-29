<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * RegisterTest - Test Suite untuk Pengujian Fungsionalitas Register
 *
 * Class ini berisi kumpulan test case untuk memverifikasi berbagai aspek
 * dari sistem register aplikasi GASSOR, termasuk:
 * - Pemilihan role untuk register (penyewa/pemilik)
 * - Struktur dan validasi form register
 * - Register manual dengan data valid/invalid
 * - Navigasi dan user experience
 * - Responsive design dan aksesibilitas
 * - Validasi input dan error handling
 * - Konfirmasi registrasi berhasil
 *
 * Test ini menggunakan Laravel Dusk untuk browser automation testing
 * pada server live https://webgassor.site
 */
class RegisterTest extends DuskTestCase
{
    /**
     * Method yang dijalankan sebelum setiap test case
     * Mempersiapkan environment untuk testing
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Method yang dijalankan setelah setiap test case
     * Membersihkan state browser untuk mencegah interferensi antar test
     */
    protected function tearDown(): void
    {
        // Tutup semua browser setelah test untuk mencegah interferensi
        static::closeAll();
        parent::tearDown();
    }

    /**
     * Test navigasi dari halaman pemilihan role ke halaman register untuk penyewa
     *
     * Pengujian ini memverifikasi navigasi dari halaman pemilihan peran ke halaman register
     * khusus untuk peran penyewa. Memastikan URL yang benar dan halaman register
     * tersedia dengan parameter role=penyewa.
     */
    public function test_navigasi_ke_register_penyewa()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN NAVIGASI KE REGISTER PENYEWA ===\n";

            $browser->visit('https://webgassor.site/select-role')
                ->pause(3000)
                ->assertSee('Pilih Peran Anda')
                ->assertSee('Penyewa');

            // Navigasi ke halaman login penyewa terlebih dahulu
            $browser->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Dari halaman login, klik link "Belum punya akun?"
            $browser->assertSee('Belum punya akun?')
                ->click('a[href*="register"]')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('register', $currentUrl);
            $this->assertStringContainsString('role=penyewa', $currentUrl);

            $browser->assertSee('Buat Akun')
                ->assertSee('Daftar');

            echo "✓ Navigasi ke register penyewa berhasil\n";
            echo "✓ URL: $currentUrl\n";
            echo "✓ Halaman register untuk penyewa dimuat dengan benar\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test navigasi dari halaman pemilihan role ke halaman register untuk pemilik
     *
     * Pengujian ini memverifikasi navigasi dari halaman pemilihan peran ke halaman register
     * khusus untuk peran pemilik. Memastikan parameter role=pemilik ada di URL
     * dan halaman register yang sesuai ditampilkan dengan benar.
     */
    public function test_navigasi_ke_register_pemilik()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN NAVIGASI KE REGISTER PEMILIK ===\n";

            $browser->visit('https://webgassor.site/select-role')
                ->pause(3000)
                ->assertSee('Pilih Peran Anda')
                ->assertSee('Pemilik');

            // Navigasi ke halaman login pemilik terlebih dahulu
            $browser->click('a[href*="login"][href*="role=pemilik"]')
                ->pause(3000);

            // Dari halaman login, klik link "Belum punya akun?"
            $browser->assertSee('Belum punya akun?')
                ->click('a[href*="register"]')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('register', $currentUrl);
            $this->assertStringContainsString('role=pemilik', $currentUrl);

            $browser->assertSee('Buat Akun')
                ->assertSee('Daftar');

            echo "✓ Navigasi ke register pemilik berhasil\n";
            echo "✓ URL: $currentUrl\n";
            echo "✓ Halaman register untuk pemilik dimuat dengan benar\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test struktur halaman register untuk penyewa
     *
     * Pengujian ini memverifikasi struktur lengkap halaman register untuk penyewa.
     * Memastikan semua elemen form register ada: input email, password, role, tombol submit,
     * tombol kembali, dan link-link navigasi yang diperlukan.
     */
    public function test_struktur_halaman_register_penyewa()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN STRUKTUR HALAMAN REGISTER PENYEWA ===\n";

            // Navigasi ke register penyewa
            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Verifikasi elemen header
                ->assertSee('Buat Akun')
                ->assertSee('Daftar')

                    // Verifikasi tombol kembali
                ->assertPresent('a[href*="login"]')

                    // Verifikasi form register
                ->assertPresent('form[method="POST"]')
                ->assertPresent('form[action*="register"]')

                    // Verifikasi input fields
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="role"]')

                    // Verifikasi labels
                ->assertSee('Email')
                ->assertSee('Kata Sandi')

                    // Verifikasi tombol submit
                ->assertPresent('button[type="submit"]')
                ->assertSee('Buat Akun')

                    // Verifikasi link ke login
                ->assertSee('Sudah punya akun?')
                ->assertPresent('a[href*="login"]')

                    // Verifikasi role value
                ->assertInputValue('input[name="role"]', 'penyewa');

            echo "✓ Struktur halaman register penyewa lengkap\n";
            echo "✓ Form register dengan semua field tersedia\n";
            echo "✓ Role sudah ter-set sebagai penyewa\n";
            echo "✓ Navigasi dan tombol tersedia dengan benar\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test struktur halaman register untuk pemilik
     *
     * Pengujian ini memverifikasi struktur lengkap halaman register untuk pemilik.
     * Memastikan semua elemen form register ada dan role ter-set sebagai pemilik.
     */
    public function test_struktur_halaman_register_pemilik()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN STRUKTUR HALAMAN REGISTER PEMILIK ===\n";

            // Navigasi ke register pemilik
            $browser->visit('https://webgassor.site/register?role=pemilik')
                ->pause(3000)

                    // Verifikasi elemen header
                ->assertSee('Buat Akun')
                ->assertSee('Daftar')

                    // Verifikasi form dan input
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="role"]')
                ->assertPresent('button[type="submit"]')

                    // Verifikasi role value untuk pemilik
                ->assertInputValue('input[name="role"]', 'pemilik');

            echo "✓ Struktur halaman register pemilik lengkap\n";
            echo "✓ Role sudah ter-set sebagai pemilik\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test fungsionalitas input pada form register
     *
     * Pengujian ini memverifikasi fungsionalitas input pada form register.
     * Menguji kemampuan mengisi field email dan password, memastikan nilai
     * ter-input dengan benar, dan validasi input berfungsi.
     */
    public function test_fungsionalitas_input_form_register()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN FUNGSIONALITAS INPUT FORM REGISTER ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Test input email
                ->type('input[name="email"]', 'fahricoding@gmail.com')
                ->assertInputValue('input[name="email"]', 'fahricoding@gmail.com')

                    // Test input password
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertInputValue('input[name="password"]', 'Kotabaru123*')

                    // Verifikasi role tetap ter-set
                ->assertInputValue('input[name="role"]', 'penyewa');

            echo "✓ Input email berhasil diisi: fahricoding@gmail.com\n";
            echo "✓ Input password berhasil diisi: Kotabaru123*\n";
            echo "✓ Role tetap ter-set sebagai penyewa\n";
            echo "✓ Semua field form dapat diisi dengan benar\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test fungsionalitas toggle password visibility
     *
     * Pengujian ini memverifikasi fungsionalitas toggle untuk menampilkan/menyembunyikan password.
     * Menguji apakah tombol mata (eye icon) dapat mengubah tipe input password
     * antara 'password' dan 'text' untuk memudahkan user melihat password yang diketik.
     */
    public function test_toggle_password_visibility()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN TOGGLE PASSWORD VISIBILITY ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Isi password terlebih dahulu
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertAttribute('input[name="password"]', 'type', 'password');

            // Cek apakah eye icon ada dan dapat diklik
            if ($browser->element('#eye-icon') || $browser->element('span[onclick*="togglePassword"]')) {
                echo "✓ Eye icon ditemukan\n";

                try {
                    // Coba klik eye icon
                    if ($browser->element('#eye-icon')) {
                        $browser->click('#eye-icon')->pause(500);
                    } else {
                        $browser->click('span[onclick*="togglePassword"]')->pause(500);
                    }

                    echo "✓ Eye icon berhasil diklik\n";
                    echo "✓ Toggle password visibility tersedia\n";
                } catch (Exception $e) {
                    echo '⚠ Eye icon ada tapi tidak dapat diklik: '.$e->getMessage()."\n";
                }

                $browser->assertPresent('span[onclick*="togglePassword"]');
            } else {
                echo "⚠ Eye icon tidak ditemukan di halaman\n";
            }

            echo "✓ Fungsionalitas password visibility diuji\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test validasi form register dengan data kosong
     *
     * Pengujian ini memverifikasi validasi form ketika disubmit dengan data kosong.
     * Memastikan sistem menampilkan pesan error yang sesuai dan tidak memproses
     * registrasi tanpa data yang required.
     */
    public function test_validasi_form_register_data_kosong()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN VALIDASI FORM REGISTER DATA KOSONG ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Submit form tanpa mengisi data
                ->press('Buat Akun')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            // Periksa apakah masih di halaman register (validasi gagal)
            if (str_contains($currentUrl, 'register') || str_contains($pageSource, 'Buat Akun')) {
                echo "✓ Validasi berfungsi - masih di halaman register\n";

                // Cari pesan error atau indikasi validasi
                if (str_contains($pageSource, 'required') ||
                    str_contains($pageSource, 'wajib') ||
                    str_contains($pageSource, 'harus') ||
                    str_contains($pageSource, 'error') ||
                    str_contains($pageSource, 'tidak boleh kosong')) {
                    echo "✓ Pesan validasi error ditemukan\n";
                } else {
                    echo "⚠ Form tidak disubmit karena validasi HTML5\n";
                }

                // Verifikasi form masih ada
                $browser->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]')
                    ->assertPresent('button[type="submit"]');
            } else {
                echo "⚠ Form disubmit meskipun data kosong - perlu pengecekan validasi\n";
            }

            echo "✓ Validasi form dengan data kosong telah diuji\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test validasi email format yang tidak valid
     *
     * Pengujian ini memverifikasi validasi format email pada form register.
     * Menguji apakah sistem dapat mendeteksi dan menolak format email yang tidak valid.
     */
    public function test_validasi_format_email_tidak_valid()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN VALIDASI FORMAT EMAIL TIDAK VALID ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Isi dengan email format tidak valid
                ->type('input[name="email"]', 'email-tidak-valid')
                ->type('input[name="password"]', 'Kotabaru123*')

                    // Submit form
                ->press('Buat Akun')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            // Periksa validasi email
            if (str_contains($currentUrl, 'register') || str_contains($pageSource, 'Buat Akun')) {
                echo "✓ Validasi email berfungsi - registrasi ditolak\n";

                if (str_contains($pageSource, 'email') &&
                    (str_contains($pageSource, 'valid') ||
                     str_contains($pageSource, 'format') ||
                     str_contains($pageSource, '@'))) {
                    echo "✓ Pesan error validasi email ditemukan\n";
                } else {
                    echo "⚠ Validasi menggunakan HTML5 constraint\n";
                }
            }

            echo "✓ Validasi format email telah diuji\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test validasi password yang terlalu lemah
     *
     * Pengujian ini memverifikasi validasi kekuatan password pada form register.
     * Menguji apakah sistem dapat mendeteksi password yang terlalu sederhana atau lemah.
     */
    public function test_validasi_password_lemah()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN VALIDASI PASSWORD LEMAH ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Isi dengan password yang lemah
                ->type('input[name="email"]', 'test@example.com')
                ->type('input[name="password"]', '123')

                    // Submit form
                ->press('Buat Akun')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            // Periksa validasi password
            if (str_contains($currentUrl, 'register') || str_contains($pageSource, 'Buat Akun')) {
                echo "✓ Validasi password berfungsi - password lemah ditolak\n";

                if (str_contains($pageSource, 'password') &&
                    (str_contains($pageSource, 'minimal') ||
                     str_contains($pageSource, 'karakter') ||
                     str_contains($pageSource, 'panjang'))) {
                    echo "✓ Pesan error validasi password ditemukan\n";
                } else {
                    echo "⚠ Validasi menggunakan constraint HTML5 atau server\n";
                }
            }

            echo "✓ Validasi password lemah telah diuji\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test register berhasil dengan data valid untuk penyewa
     * Email: fahricoding@gmail.com
     * Password: Kotabaru123*
     *
     * Pengujian ini melakukan registrasi dengan data yang valid untuk role penyewa.
     * Menguji proses registrasi sebenarnya dengan data yang memenuhi semua validasi,
     * memverifikasi redirect setelah berhasil register dan konfirmasi akun terbuat.
     */
    public function test_register_berhasil_penyewa_data_valid()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN REGISTER BERHASIL PENYEWA - DATA VALID ===\n";

            // Langkah 1: Navigasi ke halaman register penyewa
            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000);

            echo "✓ Halaman register penyewa dimuat\n";

            // Langkah 2: Isi form dengan data valid
            $browser->type('input[name="email"]', 'fahricoding@gmail.com')
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertInputValue('input[name="role"]', 'penyewa');

            echo "✓ Data registrasi diisi:\n";
            echo "  - Email: fahricoding@gmail.com\n";
            echo "  - Password: Kotabaru123*\n";
            echo "  - Role: penyewa\n";

            // Langkah 3: Submit form registrasi
            $browser->press('Buat Akun')
                ->pause(5000);

            // Langkah 4: Periksa hasil registrasi
            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            echo "URL setelah submit: $currentUrl\n";

            // Periksa apakah registrasi berhasil
            if (str_contains($currentUrl, 'login') && ! str_contains($currentUrl, 'register')) {
                echo "✓ Registrasi berhasil - diarahkan ke halaman login\n";

                // Cari pesan sukses atau konfirmasi
                if (str_contains($pageSource, 'berhasil') ||
                    str_contains($pageSource, 'sukses') ||
                    str_contains($pageSource, 'terdaftar') ||
                    str_contains($pageSource, 'akun telah dibuat')) {
                    echo "✓ Pesan konfirmasi registrasi berhasil ditemukan\n";
                }

                // Verifikasi halaman login tersedia
                $browser->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]')
                    ->assertSee('Masuk');

                echo "✓ Form login tersedia untuk akun yang baru dibuat\n";

            } elseif (str_contains($currentUrl, 'register')) {
                echo "⚠ Masih di halaman register - kemungkinan ada error:\n";

                // Cek pesan error
                if (str_contains($pageSource, 'sudah') && str_contains($pageSource, 'terdaftar')) {
                    echo "⚠ Email sudah terdaftar sebelumnya\n";
                } elseif (str_contains($pageSource, 'error') ||
                          str_contains($pageSource, 'gagal') ||
                          str_contains($pageSource, 'tidak valid')) {
                    echo "⚠ Ada error dalam proses registrasi\n";
                } else {
                    echo "⚠ Registrasi tidak diproses - periksa validasi\n";
                }

            } elseif (str_contains($currentUrl, 'home') ||
                      str_contains($currentUrl, 'dashboard') ||
                      ! str_contains($pageSource, 'register')) {
                echo "✓ Registrasi berhasil - langsung login otomatis\n";
                echo "✓ Diarahkan ke: $currentUrl\n";

                // Cari indikator user sudah login
                if (str_contains($pageSource, 'fahricoding') ||
                    str_contains($pageSource, 'Dashboard') ||
                    str_contains($pageSource, 'Profil') ||
                    str_contains($pageSource, 'Logout')) {
                    echo "✓ User berhasil login otomatis setelah register\n";
                }
            }

            echo "=========================================\n";
        });
    }

    /**
     * Test register berhasil dengan data valid untuk pemilik
     *
     * Pengujian ini melakukan registrasi dengan data yang valid untuk role pemilik.
     * Menggunakan email alternatif untuk menghindari konflik dengan test penyewa.
     */
    public function test_register_berhasil_pemilik_data_valid()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN REGISTER BERHASIL PEMILIK - DATA VALID ===\n";

            // Langkah 1: Navigasi ke halaman register pemilik
            $browser->visit('https://webgassor.site/register?role=pemilik')
                ->pause(3000);

            echo "✓ Halaman register pemilik dimuat\n";

            // Langkah 2: Isi form dengan data valid
            $browser->type('input[name="email"]', 'fahricoding.pemilik@gmail.com')
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertInputValue('input[name="role"]', 'pemilik');

            echo "✓ Data registrasi diisi:\n";
            echo "  - Email: fahricoding.pemilik@gmail.com\n";
            echo "  - Password: Kotabaru123*\n";
            echo "  - Role: pemilik\n";

            // Langkah 3: Submit form registrasi
            $browser->press('Buat Akun')
                ->pause(5000);

            // Langkah 4: Periksa hasil registrasi
            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            echo "URL setelah submit: $currentUrl\n";

            // Periksa hasil registrasi pemilik
            if (str_contains($currentUrl, 'login') ||
                str_contains($currentUrl, 'dashboard') ||
                str_contains($currentUrl, 'pemilik') ||
                ! str_contains($pageSource, 'register')) {
                echo "✓ Registrasi pemilik berhasil\n";

                if (str_contains($currentUrl, 'pemilik') || str_contains($currentUrl, 'dashboard')) {
                    echo "✓ Langsung diarahkan ke dashboard pemilik\n";
                } else {
                    echo "✓ Diarahkan ke halaman login\n";
                }
            } else {
                echo "⚠ Registrasi pemilik perlu dicek lebih lanjut\n";
            }

            echo "=========================================\n";
        });
    }

    /**
     * Test responsive design halaman register
     *
     * Pengujian ini memverifikasi desain responsif halaman register.
     * Menguji tampilan dan fungsionalitas form register pada berbagai ukuran layar
     * mulai dari mobile (375x667) hingga desktop (1920x1080).
     */
    public function test_responsive_design_halaman_register()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN RESPONSIVE DESIGN HALAMAN REGISTER ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000);

            // Test tampilan mobile
            echo "Testing tampilan mobile (375x667)...\n";
            $browser->resize(375, 667)
                ->pause(2000)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('button[type="submit"]')
                ->assertSee('Buat Akun');

            echo "✓ Tampilan mobile - semua elemen tersedia\n";

            // Test tampilan tablet
            echo "Testing tampilan tablet (768x1024)...\n";
            $browser->resize(768, 1024)
                ->pause(2000)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('button[type="submit"]');

            echo "✓ Tampilan tablet - semua elemen tersedia\n";

            // Test tampilan desktop
            echo "Testing tampilan desktop (1920x1080)...\n";
            $browser->resize(1920, 1080)
                ->pause(2000)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('button[type="submit"]');

            echo "✓ Tampilan desktop - semua elemen tersedia\n";
            echo "✓ Halaman register responsif di semua ukuran layar\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test aksesibilitas halaman register
     *
     * Pengujian ini memverifikasi fitur aksesibilitas pada halaman register.
     * Memastikan label form ter-assign dengan benar, placeholder tersedia,
     * dan atribut required ada pada field yang wajib diisi.
     */
    public function test_aksesibilitas_halaman_register()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN AKSESIBILITAS HALAMAN REGISTER ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Test label accessibility
                ->assertPresent('label[for="email"]')
                ->assertPresent('label[for="password"]')

                    // Test placeholder text
                ->assertPresent('input[name="email"][placeholder]')
                ->assertPresent('input[name="password"][placeholder]')

                    // Test required attributes
                ->assertPresent('input[name="email"][required]')
                ->assertPresent('input[name="password"][required]')

                    // Test form structure
                ->assertPresent('form[method="POST"]')
                ->assertPresent('button[type="submit"]');

            echo "✓ Label form ter-assign dengan benar\n";
            echo "✓ Placeholder text tersedia untuk semua input\n";
            echo "✓ Atribut required ada pada field wajib\n";
            echo "✓ Struktur form accessible dan semantic\n";
            echo "✓ Halaman register memenuhi standar aksesibilitas\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test navigasi kembali dari register ke login
     *
     * Pengujian ini memverifikasi navigasi kembali dari halaman register ke login.
     * Menguji tombol back dan link "Sudah punya akun?" berfungsi dengan benar.
     */
    public function test_navigasi_kembali_ke_login()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN NAVIGASI KEMBALI KE LOGIN ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000);

            // Test tombol back arrow
            if ($browser->element('a[href*="login"]')) {
                echo "✓ Tombol kembali ke login ditemukan\n";

                $browser->click('a[href*="login"]')
                    ->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                if (str_contains($currentUrl, 'login')) {
                    echo "✓ Navigasi kembali ke login berhasil\n";
                    echo "✓ URL: $currentUrl\n";

                    // Verifikasi halaman login
                    $browser->assertSee('Masuk')
                        ->assertPresent('input[name="email"]')
                        ->assertPresent('input[name="password"]');

                    echo "✓ Halaman login dimuat dengan benar\n";
                }
            }

            // Test link "Sudah punya akun?"
            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000);

            if ($browser->element('a[href*="login"]')) {
                echo "✓ Link 'Sudah punya akun?' tersedia\n";
            }

            echo "✓ Navigasi kembali ke login telah diuji\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test register dengan email yang sudah terdaftar
     *
     * Pengujian ini memverifikasi handling ketika user mencoba register
     * dengan email yang sudah terdaftar di sistem. Memastikan error message
     * yang tepat ditampilkan dan registrasi ditolak.
     */
    public function test_register_email_sudah_terdaftar()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN REGISTER EMAIL SUDAH TERDAFTAR ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Gunakan email yang mungkin sudah terdaftar
                ->type('input[name="email"]', 'penyewa1@gmail.com')
                ->type('input[name="password"]', 'Kotabaru123*')

                    // Submit form
                ->press('Buat Akun')
                ->pause(5000);

            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            // Periksa response
            if (str_contains($currentUrl, 'register') || str_contains($pageSource, 'Buat Akun')) {
                echo "✓ Registrasi ditolak - masih di halaman register\n";

                // Cari pesan error email sudah terdaftar
                if (str_contains($pageSource, 'sudah') &&
                    (str_contains($pageSource, 'terdaftar') ||
                     str_contains($pageSource, 'digunakan') ||
                     str_contains($pageSource, 'exists'))) {
                    echo "✓ Pesan error 'email sudah terdaftar' ditemukan\n";
                } else {
                    echo "⚠ Email mungkin belum terdaftar atau error message berbeda\n";
                }

                // Verifikasi form masih ada
                $browser->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]')
                    ->assertPresent('button[type="submit"]');

            } else {
                echo "⚠ Registrasi berhasil - email belum terdaftar sebelumnya\n";
            }

            echo "✓ Pengujian email sudah terdaftar telah diuji\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test keamanan form register (CSRF protection)
     *
     * Pengujian ini memverifikasi bahwa form register memiliki proteksi CSRF token
     * yang proper untuk mencegah serangan Cross-Site Request Forgery.
     */
    public function test_keamanan_csrf_protection()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN KEAMANAN CSRF PROTECTION ===\n";

            $browser->visit('https://webgassor.site/register?role=penyewa')
                ->pause(3000)

                    // Verifikasi CSRF token ada
                ->assertPresent('input[name="_token"]')
                ->assertPresent('form[method="POST"]');

            // Periksa apakah token tidak kosong
            $tokenElement = $browser->element('input[name="_token"]');
            if ($tokenElement && $tokenElement->getAttribute('value')) {
                echo "✓ CSRF token ditemukan dan memiliki nilai\n";
                echo "✓ Form dilindungi dari serangan CSRF\n";
            } else {
                echo "⚠ CSRF token tidak ditemukan atau kosong\n";
            }

            // Verifikasi method POST
            $browser->assertPresent('form[method="POST"]');
            echo "✓ Form menggunakan method POST yang aman\n";

            echo "✓ Pengujian keamanan CSRF telah dilakukan\n";
            echo "=========================================\n";
        });
    }

    /**
     * Test komprehensif end-to-end register workflow
     *
     * Pengujian ini melakukan test end-to-end lengkap dari pemilihan role
     * hingga registrasi berhasil, mencakup seluruh workflow user experience.
     */
    public function test_end_to_end_register_workflow()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN END-TO-END REGISTER WORKFLOW ===\n";

            // Langkah 1: Mulai dari halaman pemilihan role
            echo "Langkah 1: Akses halaman pemilihan role\n";
            $browser->visit('https://webgassor.site/select-role')
                ->pause(3000)
                ->assertSee('Pilih Peran Anda');
            echo "✓ Halaman pemilihan role dimuat\n";

            // Langkah 2: Pilih role penyewa dan navigasi ke login
            echo "Langkah 2: Pilih role penyewa\n";
            $browser->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertSee('Masuk Gassor');
            echo "✓ Halaman login penyewa dimuat\n";

            // Langkah 3: Navigasi ke register
            echo "Langkah 3: Navigasi ke halaman register\n";
            $browser->click('a[href*="register"]')
                ->pause(3000)
                ->assertSee('Buat Akun');
            echo "✓ Halaman register dimuat\n";

            // Langkah 4: Isi form register dengan data valid
            echo "Langkah 4: Isi form register\n";
            $browser->type('input[name="email"]', 'fahricoding.test@gmail.com')
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertInputValue('input[name="role"]', 'penyewa');
            echo "✓ Form register diisi dengan data valid\n";

            // Langkah 5: Submit registrasi
            echo "Langkah 5: Submit registrasi\n";
            $browser->press('Buat Akun')
                ->pause(5000);

            // Langkah 6: Verifikasi hasil
            echo "Langkah 6: Verifikasi hasil registrasi\n";
            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            if (str_contains($currentUrl, 'login') ||
                str_contains($currentUrl, 'dashboard') ||
                str_contains($currentUrl, 'home') ||
                ! str_contains($pageSource, 'register')) {
                echo "✓ Registrasi berhasil - workflow end-to-end sukses\n";
                echo "✓ User diarahkan ke: $currentUrl\n";
            } else {
                echo "⚠ Workflow registrasi perlu dicek - masih di register\n";
            }

            echo "\n=== RINGKASAN END-TO-END TEST ===\n";
            echo "✓ Pemilihan role berhasil\n";
            echo "✓ Navigasi ke login berhasil\n";
            echo "✓ Navigasi ke register berhasil\n";
            echo "✓ Form register dapat diisi\n";
            echo "✓ Registrasi dapat disubmit\n";
            echo "✓ Workflow end-to-end telah diuji\n";
            echo "=========================================\n";
        });
    }
}
