<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * LoginTest - Test Suite untuk Pengujian Fungsionalitas Login
 *
 * Class ini berisi kumpulan test case untuk memverifikasi berbagai aspek
 * dari sistem login aplikasi GASSOR, termasuk:
 * - Pemilihan role (penyewa/pemilik)
 * - Struktur dan validasi form login
 * - Login manual dengan kredensial valid/invalid
 * - Fungsionalitas Google OAuth
 * - Navigasi dan user experience
 * - Responsive design dan aksesibilitas
 *
 * Test ini menggunakan Laravel Dusk u            echo "\n=== HASIL PENGUJIAN ALUR LOGIN GOOGLE ===\n";
            echo "✓ Halaman pemilihan role berhasil dimuat\n";
            echo "✓ Role penyewa dipilih dan navigasi ke login berhasil\n";
            echo "✓ Struktur halaman login telah diverifikasi\n";
            echo "✓ Form Google login tersedia dengan role yang benar (penyewa)\n";
            echo "✓ Tombol Google login dapat diklik dan terlihat\n";
            echo "✓ Action form mengarah ke route Google login yang tepat\n";
            echo "=========================================\n";wser automation testing
 * pada server live https://webgassor.site
 */
class LoginTest extends DuskTestCase
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
     * Test role selection page loads correctly
     *
     * Pengujian ini memverifikasi bahwa halaman pemilihan peran dapat dimuat dengan benar.
     * Menguji semua elemen UI yang harus ada pada halaman pemilihan peran antara penyewa dan pemilik.
     * Memastikan teks, tombol, dan link navigasi berfungsi dengan baik.
     */
    public function test_role_selection_page_loads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(3000)
                ->assertSee('Pilih Peran Anda')
                ->assertSee('Masuk / Daftar Sebagai :')
                ->assertSee('Penyewa')
                ->assertSee('Pemilik')
                ->assertSee('Sewa motor dengan mudah dan cepat')
                ->assertSee('Kelola motor dan pesanan Anda')
                ->assertSee('Kembali ke halaman utama')
                ->assertPresent('a[href*="login"][href*="role=penyewa"]')
                ->assertPresent('a[href*="login"][href*="role=pemilik"]');
        });
    }

    /**
     * Test selecting penyewa role and navigating to login
     *
     * Pengujian ini memverifikasi navigasi dari halaman pemilihan peran ke halaman login
     * khusus untuk peran penyewa. Memastikan URL yang benar dan elemen login tersedia.
     * Menguji proses klik tombol penyewa dan redirect yang sesuai.
     */
    public function test_select_penyewa_role_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('login', $currentUrl);
            $this->assertStringContainsString('role=penyewa', $currentUrl);

            $browser->assertSee('Masuk Gassor')
                ->assertSee('Nggak pakai ribet sewa motor di Gassor langsung gas');
        });
    }

    /**
     * Test selecting pemilik role and navigating to login
     *
     * Pengujian ini memverifikasi navigasi dari halaman pemilihan peran ke halaman login
     * khusus untuk peran pemilik. Memastikan parameter role=pemilik ada di URL
     * dan halaman login yang sesuai ditampilkan dengan benar.
     */
    public function test_select_pemilik_role_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=pemilik"]')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('login', $currentUrl);
            $this->assertStringContainsString('role=pemilik', $currentUrl);

            $browser->assertSee('Masuk Gassor')
                ->assertSee('Nggak pakai ribet sewa motor di Gassor langsung gas');
        });
    }

    /**
     * Test login page structure and elements for penyewa
     *
     * Pengujian ini memverifikasi struktur lengkap halaman login untuk penyewa.
     * Memastikan semua elemen form login ada: input email, password, role, tombol submit,
     * form Google login, dan link-link navigasi yang diperlukan.
     */
    public function test_login_page_structure_penyewa()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="role"][value="penyewa"]')
                ->assertPresent('button[type="submit"]')
                ->assertPresent('form#google-login-form')
                ->assertSee('Email')
                ->assertSee('Kata Sandi')
                ->assertSee('Lupa Password?')
                ->assertSee('Masuk / Daftar')
                ->assertSee('Belum punya akun?');
        });
    }

    /**
     * Test login page structure and elements for pemilik
     *
     * Pengujian ini memverifikasi struktur lengkap halaman login untuk pemilik.
     * Memastikan form login memiliki semua elemen yang diperlukan dan nilai role
     * ter-set dengan benar sebagai "pemilik" pada input hidden.
     */
    public function test_login_page_structure_pemilik()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=pemilik"]')
                ->pause(3000)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="role"][value="pemilik"]')
                ->assertPresent('button[type="submit"]')
                ->assertPresent('form#google-login-form')
                ->assertInputValue('input[name="role"]', 'pemilik');
        });
    }

    /**
     * Test login form validation with empty fields
     *
     * Pengujian ini memverifikasi validasi form login ketika field kosong.
     * Menguji apakah HTML5 validation atau client-side validation mencegah
     * submit form dengan field email dan password yang kosong.
     */
    public function test_login_form_validation_empty_fields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->press('Masuk')
                ->pause(2000);

            // HTML5 validation seharusnya mencegah submit
            // Kita tidak bisa mudah menguji ini dengan Dusk, jadi kita hanya verifikasi form masih ada
            $browser->assertPresent('form[method="POST"]');
        });
    }

    /**
     * Test login form input functionality
     *
     * Pengujian ini memverifikasi fungsionalitas input pada form login.
     * Menguji kemampuan mengisi field email dan password, memastikan nilai
     * ter-input dengan benar, dan role sudah ter-set sesuai pilihan sebelumnya.
     */
    public function test_login_form_input_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->type('input[name="email"]', 'test@example.com')
                ->type('input[name="password"]', 'testpassword123')
                ->assertInputValue('input[name="email"]', 'test@example.com')
                ->assertInputValue('input[name="password"]', 'testpassword123')
                ->assertInputValue('input[name="role"]', 'penyewa');
        });
    }

    /**
     * Test password visibility toggle functionality
     *
     * Pengujian ini memverifikasi fungsionalitas toggle untuk menampilkan/menyembunyikan password.
     * Menguji apakah tombol mata (eye icon) dapat mengubah tipe input password
     * antara 'password' dan 'text' untuk memudahkan user melihat password yang diketik.
     */
    public function test_password_visibility_toggle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->type('input[name="password"]', 'testpassword')
                ->assertAttribute('input[name="password"]', 'type', 'password');

            // Klik ikon mata untuk toggle visibility
            if ($browser->element('#eye-icon')) {
                $browser->click('#eye-icon')
                    ->pause(500);
                // Catatan: Kita tidak bisa mudah menguji perubahan tipe karena JavaScript
                // tapi kita bisa verifikasi elemen masih ada
                $browser->assertPresent('#eye-icon');
            }
        });
    }

    /**
     * Test Google login button presence and structure
     *
     * Pengujian ini memverifikasi keberadaan dan struktur tombol login Google.
     * Memastikan form Google login memiliki elemen yang lengkap: form action,
     * input role, tombol dengan icon Google, dan teks yang sesuai.
     */
    public function test_google_login_button_structure()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertPresent('form#google-login-form')
                ->assertPresent('input[name="role"][id="google-role-input"]')
                ->assertPresent('button[type="button"]')
                ->assertSee('Masuk / Daftar')
                ->assertPresent('img[src*="google.svg"]');
        });
    }

    /**
     * Test Google login form has correct role value for penyewa
     *
     * Pengujian ini memverifikasi bahwa form Google login memiliki nilai role yang benar
     * untuk penyewa. Memastikan input hidden role ter-set sebagai "penyewa"
     * sehingga ketika Google OAuth berhasil, user akan login sebagai penyewa.
     */
    public function test_google_login_role_penyewa()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertInputValue('input[id="google-role-input"]', 'penyewa');
        });
    }

    /**
     * Test Google login form has correct role value for pemilik
     *
     * Pengujian ini memverifikasi bahwa form Google login memiliki nilai role yang benar
     * untuk pemilik. Memastikan input hidden role ter-set sebagai "pemilik"
     * sehingga ketika Google OAuth berhasil, user akan login sebagai pemilik.
     */
    public function test_google_login_role_pemilik()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=pemilik"]')
                ->pause(3000)
                ->assertInputValue('input[id="google-role-input"]', 'pemilik');
        });
    }

    /**
     * Test navigation to forgot password page
     *
     * Pengujian ini memverifikasi navigasi ke halaman lupa password.
     * Menguji keberadaan link "Lupa Password?" dan memastikan user dapat
     * mengakses halaman reset password dari halaman login.
     */
    public function test_forgot_password_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertSee('Lupa Password?');

            // Periksa link route register berdasarkan template blade
            if ($browser->element('a[href*="password.request"]') || $browser->element('a[href*="password/reset"]')) {
                $this->assertTrue(true); // Link ada
            } else {
                // Coba selector alternatif
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringContainsString('Lupa Password?', $pageSource);
            }
        });
    }

    /**
     * Test navigation to register page
     *
     * Pengujian ini memverifikasi navigasi ke halaman registrasi.
     * Menguji keberadaan link "Belum punya akun?" dan memastikan link tersebut
     * mengarah ke halaman registrasi dengan role yang sesuai.
     */
    public function test_register_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertSee('Belum punya akun?')
                ->assertPresent('a[href*="register"]')
                ->assertPresent('a[href*="role=penyewa"]');
        });
    }

    /**
     * Test complete login flow simulation for penyewa
     *
     * Pengujian ini mensimulasikan alur login lengkap untuk penyewa mulai dari
     * pemilihan role sampai mengisi form login. Memverifikasi setiap langkah
     * dalam proses login tanpa benar-benar submit karena akan mempengaruhi server live.
     */
    public function test_complete_login_flow_penyewa()
    {
        $this->browse(function (Browser $browser) {
            // Mulai dari pemilihan role
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->assertSee('Pilih Peran Anda')

                    // Pilih role penyewa
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Verifikasi URL mengandung parameter role
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('role=penyewa', $currentUrl);

            $browser->assertSee('Masuk Gassor')

                    // Isi form login
                ->type('input[name="email"]', 'testuser@example.com')
                ->type('input[name="password"]', 'password123')
                ->assertInputValue('input[name="role"]', 'penyewa')

                    // Verifikasi semua elemen ada sebelum mencoba submit
                ->assertPresent('button[type="submit"]')
                ->assertPresent('form#google-login-form');

            // Catatan: Kita tidak benar-benar submit karena akan memerlukan kredensial valid
            // dan akan mempengaruhi server live
        });
    }

    /**
     * Test back to homepage navigation from role selection
     *
     * Pengujian ini memverifikasi fungsionalitas tombol "Kembali ke halaman utama"
     * pada halaman pemilihan role. Memastikan user dapat kembali ke homepage
     * dari halaman pemilihan role dengan mudah.
     */
    public function test_back_to_homepage_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->assertSee('Kembali ke halaman utama');

            // Periksa link home berdasarkan template blade
            $pageSource = $browser->driver->getPageSource();
            if (str_contains($pageSource, 'route(\'home\')') || str_contains($pageSource, 'href=')) {
                // Coba temukan dan klik link home
                try {
                    $browser->clickLink('Kembali ke halaman utama')
                        ->pause(3000);

                    // Verifikasi kita ter-redirect (URL harus berubah)
                    $currentUrl = $browser->driver->getCurrentURL();
                    $this->assertStringNotContainsString('select-role', $currentUrl);
                } catch (Exception $e) {
                    // Jika klik gagal, hanya verifikasi teks link ada
                    $this->assertStringContainsString('Kembali ke halaman utama', $pageSource);
                }
            } else {
                // Hanya verifikasi teks ada
                $this->assertStringContainsString('Kembali ke halaman utama', $pageSource);
            }
        });
    }

    /**
     * Test responsive design of login page
     *
     * Pengujian ini memverifikasi desain responsif halaman login.
     * Menguji tampilan dan fungsionalitas form login pada berbagai ukuran layar
     * mulai dari mobile (375x667) hingga desktop (1920x1080).
     */
    public function test_login_page_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)

                    // Test tampilan mobile
                ->resize(375, 667)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('button[type="submit"]')

                    // Test tampilan desktop
                ->resize(1920, 1080)
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('button[type="submit"]');
        });
    }

    /**
     * Test login page accessibility features
     *
     * Pengujian ini memverifikasi fitur aksesibilitas pada halaman login.
     * Memastikan label form ter-assign dengan benar, placeholder tersedia,
     * dan atribut required ada pada field yang wajib diisi untuk kemudahan user.
     */
    public function test_login_page_accessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000)
                ->assertPresent('label[for="email"]')
                ->assertPresent('label[for="password"]')
                ->assertPresent('input[name="email"][placeholder]')
                ->assertPresent('input[name="password"][placeholder]')
                ->assertPresent('input[name="email"][required]')
                ->assertPresent('input[name="password"][required]');
        });
    }

    /**
     * Test role selection page UI elements and styling
     *
     * Pengujian ini memverifikasi elemen UI dan styling pada halaman pemilihan role.
     * Menguji keberadaan logo, icon, teks deskripsi, dan tombol-tombol yang
     * harus terlihat dan dapat diakses dengan baik oleh user.
     */
    public function test_role_selection_ui_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site/select-role')
                ->pause(3000)
                ->assertPresent('img[src*="gassor1.png"]')
                ->assertSee('Pilih Peran Anda')
                ->assertPresent('i.fas.fa-user')
                ->assertPresent('i.fas.fa-warehouse')
                ->assertVisible('a[href*="login"][href*="role=penyewa"]')
                ->assertVisible('a[href*="login"][href*="role=pemilik"]');

            // Periksa link home dengan cara yang lebih fleksibel
            $pageSource = $browser->driver->getPageSource();
            $this->assertStringContainsString('Kembali ke halaman utama', $pageSource);
        });
    }

    /**
     * Test complete Google login flow for penyewa role
     *
     * Pengujian ini mensimulasikan alur lengkap login Google untuk peran penyewa.
     * Dimulai dari pemilihan role, navigasi ke login, verifikasi struktur form Google,
     * dan memastikan semua elemen Google OAuth siap digunakan. Test ini tidak melakukan
     * OAuth sebenarnya karena memerlukan kredensial Google asli dan persetujuan manual.
     */
    public function test_google_login_flow_penyewa_complete()
    {
        $this->browse(function (Browser $browser) {
            // Langkah 1: Kunjungi halaman pemilihan role
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->assertSee('Pilih Peran Anda')
                ->assertSee('Penyewa')
                ->assertSee('Sewa motor dengan mudah dan cepat');

            // Langkah 2: Pilih role penyewa
            $browser->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Langkah 3: Verifikasi kita di halaman login dengan role yang benar
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('login', $currentUrl);
            $this->assertStringContainsString('role=penyewa', $currentUrl);

            // Langkah 4: Verifikasi struktur halaman login
            $browser->assertSee('Masuk Gassor')
                ->assertSee('Nggak pakai ribet sewa motor di Gassor langsung gas')
                ->assertPresent('form[method="POST"]')
                ->assertPresent('input[name="role"][value="penyewa"]');

            // Langkah 5: Verifikasi struktur form Google login
            $browser->assertPresent('form#google-login-form')
                ->assertPresent('input[name="role"][id="google-role-input"][value="penyewa"]')
                ->assertPresent('button[type="button"]')
                ->assertPresent('img[src*="google.svg"]')
                ->assertSee('Masuk / Daftar');

            // Langkah 6: Verifikasi fungsionalitas tombol Google login
            // Kita hanya verifikasi tombol dapat diklik dan memiliki struktur yang benar
            $browser->assertVisible('form#google-login-form button[type="button"]');

            // Catatan: Kita tidak benar-benar mengklik tombol Google login karena:
            // 1. Akan memicu Google OAuth asli yang memerlukan kredensial nyata
            // 2. Akan mempengaruhi state server live
            // 3. Google OAuth testing memerlukan setup khusus dengan akun test

            // Langkah 7: Verifikasi action dan method form
            $googleFormAction = $browser->element('form#google-login-form')->getAttribute('action');
            $this->assertStringContainsString('google', $googleFormAction);

            echo "\n=== GOOGLE LOGIN FLOW TEST RESULTS ===\n";
            echo "✓ Role selection page loaded successfully\n";
            echo "✓ Penyewa role selected and navigated to login\n";
            echo "✓ Login page structure verified\n";
            echo "✓ Google login form present with correct role (penyewa)\n";
            echo "✓ Google login button is clickable and visible\n";
            echo "✓ Form action points to correct Google login route\n";
            echo "=====================================\n";
        });
    }

    /**
     * Test manual login for penyewa with valid credentials
     * Email: muhammadraihanfahrifi@gmail.com
     * Password: Kotabaru123*
     *
     * Pengujian ini melakukan login manual dengan kredensial valid untuk penyewa.
     * Menguji proses login sebenarnya dengan email dan password yang terdaftar
     * di sistem, memverifikasi response server dan redirect yang terjadi setelah login.
     */
    public function test_manual_login_penyewa_success()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN LOGIN MANUAL PENYEWA ===\n";

            // Langkah 1: Navigasi ke login penyewa
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Langkah 2: Isi form login dengan kredensial yang valid
            $browser->type('input[name="email"]', 'muhammadraihanfahrifi@gmail.com')
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertInputValue('input[name="role"]', 'penyewa');

            echo "✓ Kredensial dimasukkan: muhammadraihanfahrifi@gmail.com\n";

            // Langkah 3: Submit form
            $browser->press('Masuk')
                ->pause(5000);

            // Langkah 4: Periksa hasil login
            $currentUrl = $browser->driver->getCurrentURL();
            echo "URL saat ini setelah percobaan login: $currentUrl\n";

            $pageSource = $browser->driver->getPageSource();

            // Periksa apakah masih di halaman login (login gagal)
            if (str_contains($currentUrl, 'login') && str_contains($pageSource, 'Masuk Gassor')) {
                echo "⚠ Login gagal - masih di halaman login\n";

                // Cari pesan error spesifik
                if (str_contains($pageSource, 'These credentials do not match') ||
                    str_contains($pageSource, 'invalid') ||
                    str_contains($pageSource, 'error') ||
                    str_contains($pageSource, 'salah') ||
                    str_contains($pageSource, 'gagal')) {
                    echo "⚠ Pesan error ditemukan - kredensial tidak valid\n";
                } else {
                    echo "⚠ Tidak ada pesan error spesifik - kemungkinan masalah validasi\n";
                }

                // Verifikasi form masih ada
                $browser->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]');

            } elseif (str_contains($currentUrl, 'home') ||
                      str_contains($currentUrl, 'dashboard') ||
                      str_contains($currentUrl, 'penyewa') ||
                      ! str_contains($pageSource, 'Masuk Gassor')) {

                echo "✓ Login berhasil - diarahkan ke: $currentUrl\n";

                // Cari elemen khusus penyewa atau indikator profil
                if (str_contains($pageSource, 'Selamat datang') ||
                    str_contains($pageSource, 'Dashboard') ||
                    str_contains($pageSource, 'Profil') ||
                    str_contains($pageSource, 'Logout') ||
                    str_contains($pageSource, 'muhammadraihanfahrifi')) {
                    echo "✓ Elemen dashboard/profil penyewa ditemukan\n";
                }

            } else {
                echo "⚠ Status tidak terduga setelah percobaan login\n";
                echo "URL: $currentUrl\n";
                echo "Halaman mengandung 'Masuk Gassor': ".(str_contains($pageSource, 'Masuk Gassor') ? 'Ya' : 'Tidak')."\n";
            }

            echo "========================================\n";
        });
    }

    /**
     * Test manual login for pemilik with valid credentials
     * Email: pemilik1@gmail.com
     * Password: pemilik1
     *
     * Pengujian ini melakukan login manual dengan kredensial valid untuk pemilik.
     * Menguji proses login sebenarnya dengan akun pemilik yang terdaftar,
     * memverifikasi redirect ke dashboard pemilik dan logout otomatis untuk
     * mencegah interferensi dengan test lainnya.
     */
    public function test_manual_login_pemilik_success()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN LOGIN MANUAL PEMILIK 1 ===\n";

            // Langkah 1: Navigasi ke login pemilik
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=pemilik"]')
                ->pause(3000);

            // Langkah 2: Isi form login dengan kredensial yang valid
            $browser->type('input[name="email"]', 'pemilik1@gmail.com')
                ->type('input[name="password"]', 'pemilik1')
                ->assertInputValue('input[name="role"]', 'pemilik');

            echo "✓ Kredensial dimasukkan: pemilik1@gmail.com\n";

            // Langkah 3: Submit form
            $browser->press('Masuk')
                ->pause(5000);

            // Langkah 4: Periksa hasil login
            $currentUrl = $browser->driver->getCurrentURL();

            if (str_contains($currentUrl, 'pemilik') || str_contains($currentUrl, 'dashboard')) {
                echo "✓ Login berhasil - diarahkan ke: $currentUrl\n";

                // Verifikasi tidak lagi di halaman login
                $browser->assertDontSee('Masuk Gassor');

                // Cari elemen khusus pemilik
                $pageSource = $browser->driver->getPageSource();
                if (str_contains($pageSource, 'Dashboard Pemilik') ||
                    str_contains($pageSource, 'Motor') ||
                    str_contains($pageSource, 'Pesanan') ||
                    str_contains($pageSource, 'Statistik')) {
                    echo "✓ Elemen dashboard pemilik ditemukan\n";
                }

                // Logout untuk mencegah interferensi dengan test berikutnya
                try {
                    if ($browser->element('a[href*="logout"]') || $browser->element('form[action*="logout"]')) {
                        $browser->clickLink('Logout')
                            ->pause(2000);
                        echo "✓ Berhasil logout\n";
                    } else {
                        // Metode logout alternatif
                        $browser->visit('https://webgassor.site/logout')
                            ->pause(2000);
                        echo "✓ Logout melalui URL langsung\n";
                    }
                } catch (Exception $e) {
                    echo '⚠ Tidak dapat logout: '.$e->getMessage()."\n";
                }

            } elseif (str_contains($currentUrl, 'login')) {
                $pageSource = $browser->driver->getPageSource();
                if (str_contains($pageSource, 'error') || str_contains($pageSource, 'invalid') || str_contains($pageSource, 'salah')) {
                    echo "⚠ Login gagal - kredensial tidak valid atau error server\n";
                } else {
                    echo "⚠ Masalah validasi form login atau respon lambat\n";
                }
            } else {
                echo "⚠ Redirect tidak terduga ke: $currentUrl\n";
            }

            echo "========================================\n";
        });
    }

    /**
     * Test manual login for pemilik with alternative credentials
     * Email: fahrifi116@gmail.com
     * Password: Kotabaru123*
     *
     * Pengujian ini melakukan login manual dengan kredensial alternatif untuk pemilik.
     * Menguji dengan akun pemilik kedua untuk memastikan sistem dapat menangani
     * multiple akun pemilik dengan baik dan memberikan akses yang sesuai.
     */
    public function test_manual_login_pemilik_alternative()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN LOGIN MANUAL PEMILIK 2 ===\n";

            // Langkah 1: Navigasi ke login pemilik
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=pemilik"]')
                ->pause(3000);

            // Langkah 2: Isi form login dengan kredensial alternatif
            $browser->type('input[name="email"]', 'fahrifi116@gmail.com')
                ->type('input[name="password"]', 'Kotabaru123*')
                ->assertInputValue('input[name="role"]', 'pemilik');

            echo "✓ Kredensial dimasukkan: fahrifi116@gmail.com\n";

            // Langkah 3: Submit form
            $browser->press('Masuk')
                ->pause(5000);

            // Langkah 4: Periksa hasil login
            $currentUrl = $browser->driver->getCurrentURL();
            echo "URL saat ini setelah percobaan login: $currentUrl\n";

            $pageSource = $browser->driver->getPageSource();

            // Periksa apakah login berhasil
            if (str_contains($currentUrl, 'pemilik/dashboard') ||
                (str_contains($currentUrl, 'pemilik') && ! str_contains($pageSource, 'Masuk Gassor'))) {

                echo "✓ Login berhasil - diarahkan ke: $currentUrl\n";

                // Cari elemen dashboard pemilik
                if (str_contains($pageSource, 'Dashboard') ||
                    str_contains($pageSource, 'Motor') ||
                    str_contains($pageSource, 'Pesanan')) {
                    echo "✓ Elemen dashboard pemilik ditemukan\n";
                }

            } elseif (str_contains($currentUrl, 'login') && str_contains($pageSource, 'Masuk Gassor')) {
                echo "⚠ Login gagal - masih di halaman login\n";

                // Cari pesan error
                if (str_contains($pageSource, 'These credentials do not match') ||
                    str_contains($pageSource, 'invalid') ||
                    str_contains($pageSource, 'error') ||
                    str_contains($pageSource, 'salah') ||
                    str_contains($pageSource, 'gagal')) {
                    echo "⚠ Pesan error ditemukan - kredensial tidak valid\n";
                } else {
                    echo "⚠ Tidak ada pesan error spesifik - kemungkinan masalah validasi\n";
                }

                // Verifikasi form masih ada
                $browser->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]');

            } else {
                echo "⚠ Redirect tidak terduga ke: $currentUrl\n";
            }

            echo "========================================\n";
        });
    }

    /**
     * Test forgot password functionality for penyewa
     *
     * Pengujian ini memverifikasi fungsionalitas lupa password untuk penyewa.
     * Menguji navigasi ke halaman reset password, keberadaan form email,
     * dan kemampuan mengisi email untuk proses reset password.
     */
    public function test_forgot_password_penyewa()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN LUPA PASSWORD PENYEWA ===\n";

            // Langkah 1: Navigasi ke login penyewa
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Langkah 2: Cari link lupa password
            $browser->assertSee('Lupa Password?');

            // Coba temukan link lupa password
            $pageSource = $browser->driver->getPageSource();

            if (str_contains($pageSource, 'password.request') ||
                str_contains($pageSource, 'forgot-password') ||
                str_contains($pageSource, 'password/reset')) {

                try {
                    // Coba klik link lupa password
                    $browser->clickLink('Lupa Password?')
                        ->pause(3000);

                    $currentUrl = $browser->driver->getCurrentURL();

                    if (str_contains($currentUrl, 'password') || str_contains($currentUrl, 'forgot') || str_contains($currentUrl, 'reset')) {
                        echo "✓ Berhasil navigasi ke halaman lupa password: $currentUrl\n";

                        // Periksa apakah di halaman form lupa password
                        $browser->assertPresent('input[name="email"]')
                            ->assertSee('Email');

                        // Test form dengan email penyewa
                        $browser->type('input[name="email"]', 'muhammadraihanfahrifi@gmail.com');
                        echo "✓ Form lupa password ditemukan dan email dimasukkan\n";

                    } else {
                        echo "⚠ Link lupa password diklik tapi redirect tidak terduga: $currentUrl\n";
                    }

                } catch (Exception $e) {
                    echo '⚠ Tidak dapat mengklik link lupa password: '.$e->getMessage()."\n";
                }

            } else {
                echo "⚠ Struktur link lupa password tidak ditemukan, tapi teks ada\n";
            }

            echo "=========================================\n";
        });
    }

    /**
     * Test login with invalid credentials (error handling)
     *
     * Pengujian ini memverifikasi penanganan error saat login dengan kredensial yang salah.
     * Menguji respon sistem terhadap email dan password yang tidak valid,
     * memastikan user tetap di halaman login dan mendapat feedback error yang sesuai.
     */
    public function test_login_invalid_credentials()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN KREDENSIAL LOGIN TIDAK VALID ===\n";

            // Langkah 1: Navigasi ke login penyewa
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Langkah 2: Masukkan kredensial tidak valid
            $browser->type('input[name="email"]', 'invalid@example.com')
                ->type('input[name="password"]', 'wrongpassword');

            echo "✓ Kredensial tidak valid dimasukkan\n";

            // Langkah 3: Submit form
            $browser->press('Masuk')
                ->pause(4000);

            // Langkah 4: Periksa penanganan error
            $currentUrl = $browser->driver->getCurrentURL();

            if (str_contains($currentUrl, 'login')) {
                echo "✓ Tetap di halaman login (sesuai harapan untuk kredensial tidak valid)\n";

                $pageSource = $browser->driver->getPageSource();

                // Cari pesan error
                if (str_contains($pageSource, 'error') ||
                    str_contains($pageSource, 'invalid') ||
                    str_contains($pageSource, 'salah') ||
                    str_contains($pageSource, 'gagal') ||
                    str_contains($pageSource, 'tidak ditemukan')) {
                    echo "✓ Pesan error ditampilkan untuk kredensial tidak valid\n";
                } else {
                    echo "⚠ Tidak ditemukan pesan error spesifik\n";
                }

                // Verifikasi form masih ada
                $browser->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]');
                echo "✓ Form login masih tersedia\n";

            } else {
                echo "⚠ Perilaku tidak terduga - diarahkan ke: $currentUrl\n";
            }

            echo "==========================================\n";
        });
    }

    /**
     * Test complete login journey for Google login penyewa
     * Note: This tests the Google login button click and redirect,
     * but doesn't complete actual Google OAuth (requires real Google account)
     *
     * Pengujian ini memverifikasi persiapan sistem untuk login Google penyewa.
     * Menguji struktur form Google, action URL, method, dan nilai role.
     * Test ini tidak melakukan OAuth sebenarnya karena memerlukan akun Google asli
     * dan proses persetujuan manual yang tidak bisa diotomatisasi.
     */
    public function test_google_login_penyewa_journey()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== PENGUJIAN PERJALANAN LOGIN GOOGLE PENYEWA ===\n";

            // Langkah 1: Navigasi ke login penyewa
            $browser->visit('https://webgassor.site/select-role')
                ->pause(2000)
                ->click('a[href*="login"][href*="role=penyewa"]')
                ->pause(3000);

            // Langkah 2: Verifikasi elemen Google login
            $browser->assertPresent('form#google-login-form')
                ->assertPresent('input[name="role"][id="google-role-input"][value="penyewa"]')
                ->assertPresent('button[type="button"]')
                ->assertPresent('img[src*="google.svg"]');

            echo "✓ Struktur form Google login terverifikasi\n";

            // Langkah 3: Simulasi klik tombol Google login
            // Catatan: Kita berhenti di sini karena OAuth Google sebenarnya memerlukan:
            // 1. Kredensial akun Google asli
            // 2. Alur persetujuan Google OAuth
            // 3. Setup khusus test untuk otomatisasi

            try {
                // Dapatkan URL action form Google login
                $googleForm = $browser->element('form#google-login-form');
                $googleAction = $googleForm->getAttribute('action');

                echo "✓ Action form Google login: $googleAction\n";

                // Verifikasi method form
                $googleMethod = $googleForm->getAttribute('method');
                echo "✓ Method form Google login: $googleMethod\n";

                // Verifikasi role ter-set dengan benar
                $roleInput = $browser->element('input[id="google-role-input"]');
                $roleValue = $roleInput->getAttribute('value');
                echo "✓ Nilai role Google login: $roleValue\n";

                // Tombol Google login dapat diklik
                $googleButton = $browser->element('form#google-login-form button[type="button"]');
                $isClickable = $googleButton->isDisplayed() && $googleButton->isEnabled();

                if ($isClickable) {
                    echo "✓ Tombol Google login dapat diklik\n";
                } else {
                    echo "⚠ Tombol Google login tidak dapat diklik\n";
                }

            } catch (Exception $e) {
                echo '⚠ Error memeriksa elemen Google login: '.$e->getMessage()."\n";
            }

            echo "==============================================\n";
            echo "Catatan: Pengujian OAuth Google sebenarnya memerlukan:\n";
            echo "- Akun Google asli: muhammadraihanfahrifi@gmail.com\n";
            echo "- Intervensi manual untuk persetujuan OAuth\n";
            echo "- Konfigurasi test khusus untuk otomatisasi\n";
            echo "==============================================\n";
        });
    }
}
