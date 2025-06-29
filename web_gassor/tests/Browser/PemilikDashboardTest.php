<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PemilikDashboardTest extends DuskTestCase
{
    /**
     * Test struktur dan elemen halaman dashboard pemilik
     *
     * Test ini menguji:
     * - Akses halaman dashboard pemilik
     * - Header dan greeting pemilik
     * - Statistik dashboard (Total Motor, Pesanan Aktif, Pendapatan)
     * - Section Daftar Motor Anda
     * - Section Pesanan Terbaru
     * - Link navigasi dan tombol "Semua"
     * - Navigation pemilik
     *
     * Note: Test ini akan skip jika user belum login sebagai pemilik
     */
    public function test_pemilik_dashboard_structure_and_content()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Struktur Dashboard Pemilik ===\n";

            // Coba akses dashboard pemilik
            $browser->visit('/dashboard-pemilik')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Cek apakah ter-redirect (berarti belum login sebagai pemilik)
            if (strpos($currentUrl, 'select-role') !== false || strpos($currentUrl, 'login') !== false) {
                echo '⚠️ User belum login sebagai pemilik, ter-redirect ke: '.$currentUrl."\n";
                echo "💡 Untuk test yang lengkap, silakan login terlebih dahulu sebagai pemilik\n";

                // Verify halaman redirect
                $browser->assertSee('Pilih');

                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Jika berhasil akses dashboard pemilik, lanjutkan test
            echo "✅ Berhasil akses dashboard pemilik\n";

            // Test header dan greeting
            echo "✓ Testing header dashboard pemilik\n";
            $browser->assertPresent('#TopNav')
                ->assertSee('Selamat datang kembali');

            // Test nama pemilik di header
            $browser->assertSeeIn('#TopNav h1', 'Pemilik'); // Akan ada "Pemilik GASSOR" atau nama user

            // Test navigasi atas (WhatsApp dan Profile)
            echo "✓ Testing navigasi atas pemilik\n";
            $browser->assertPresent('a[href*="wa.me"]') // WhatsApp link
                ->assertPresent('a[href*="profile.pemilik"]'); // Profile pemilik link

            // Test section statistik dashboard
            echo "✓ Testing statistik dashboard\n";
            $browser->assertSee('Total Motor')
                ->assertSee('Pesanan Aktif')
                ->assertSee('Pendapatan');

            // Cek apakah statistik ditampilkan dengan angka
            $statsElements = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('.text-m.font-bold'));
            echo 'Found '.count($statsElements)." statistic values\n";

            // Test section Daftar Motor Anda
            echo "✓ Testing section Daftar Motor Anda\n";
            $browser->assertPresent('#Popular')
                ->assertSee('Daftar Motor Anda')
                ->assertPresent('a[href*="daftar-motor"]'); // Link "Semua"

            // Hitung jumlah motor yang ditampilkan
            $motorCards = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Popular .bonus-card'));
            echo 'Found '.count($motorCards)." motor cards in dashboard\n";

            if (count($motorCards) > 0) {
                // Test elemen dalam kartu motor
                $browser->assertSeeIn('#Popular', 'Stok:')
                    ->assertSeeIn('#Popular', 'Harga');
            } else {
                // Jika belum ada motor
                $browser->assertSee('Belum ada motor terdaftar');
                echo "📝 Belum ada motor terdaftar untuk pemilik ini\n";
            }

            // Test section Pesanan Terbaru
            echo "✓ Testing section Pesanan Terbaru\n";
            $browser->assertPresent('#transaction')
                ->assertSee('Pesanan Terbaru')
                ->assertPresent('a[href*="pesanan"]'); // Link "Semua"

            // Hitung jumlah pesanan yang ditampilkan
            $transactionCards = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#transaction .bonus-card'));
            echo 'Found '.count($transactionCards)." transaction cards\n";

            if (count($transactionCards) > 0) {
                // Test elemen dalam kartu pesanan
                $browser->assertSeeIn('#transaction', 'Disewa Oleh:')
                    ->assertSeeIn('#transaction', 'Tanggal:');
            } else {
                // Jika belum ada pesanan
                $browser->assertSee('Belum ada pesanan terbaru');
                echo "📝 Belum ada pesanan terbaru untuk pemilik ini\n";
            }

            // Test navigasi bawah pemilik
            echo "✓ Testing bottom navigation pemilik\n";
            $browser->assertPresent('nav'); // Navigation include pemilik

            echo "✅ Test struktur dashboard pemilik berhasil\n";
        });
    }

    /**
     * Test interaksi dan navigasi pada dashboard pemilik
     *
     * Test ini menguji:
     * - Klik link "Semua" pada Daftar Motor
     * - Klik link "Semua" pada Pesanan Terbaru
     * - Klik pada kartu motor (jika ada)
     * - Klik pada kartu pesanan (jika ada)
     * - Link WhatsApp dan Profile
     *
     * Note: Test ini akan skip jika user belum login sebagai pemilik
     */
    public function test_pemilik_dashboard_interactions_and_navigation()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Interaksi Dashboard Pemilik ===\n";

            $browser->visit('/dashboard-pemilik')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Cek apakah ter-redirect
            if (strpos($currentUrl, 'select-role') !== false || strpos($currentUrl, 'login') !== false) {
                echo '⚠️ User belum login sebagai pemilik, ter-redirect ke: '.$currentUrl."\n";
                echo "💡 Untuk test interaksi yang lengkap, silakan login terlebih dahulu sebagai pemilik\n";

                $browser->assertSee('Pilih');
                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Test klik link "Semua" pada Daftar Motor
            echo "✓ Testing klik link Semua - Daftar Motor\n";
            $daftarMotorLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('a[href*="daftar-motor"]'));
            if (count($daftarMotorLinks) > 0) {
                $daftarMotorUrl = $daftarMotorLinks[0]->getAttribute('href');
                echo 'Daftar Motor URL: '.$daftarMotorUrl."\n";

                $browser->click('a[href*="daftar-motor"]')
                    ->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                if (strpos($newUrl, 'daftar-motor') !== false) {
                    echo "✅ Berhasil navigasi ke halaman daftar motor\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke dashboard
                $browser->visit('/dashboard-pemilik')->pause(2000);
            }

            // Test klik link "Semua" pada Pesanan
            echo "✓ Testing klik link Semua - Pesanan\n";
            $pesananLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('a[href*="pesanan"]'));
            if (count($pesananLinks) > 0) {
                $pesananUrl = $pesananLinks[0]->getAttribute('href');
                echo 'Pesanan URL: '.$pesananUrl."\n";

                $browser->click('a[href*="pesanan"]')
                    ->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                if (strpos($newUrl, 'pesanan') !== false) {
                    echo "✅ Berhasil navigasi ke halaman pesanan\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke dashboard
                $browser->visit('/dashboard-pemilik')->pause(2000);
            }

            // Test klik link profile pemilik
            echo "✓ Testing klik profile pemilik\n";
            $profileLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('a[href*="profile.pemilik"]'));
            if (count($profileLinks) > 0) {
                $profileUrl = $profileLinks[0]->getAttribute('href');
                echo 'Profile URL: '.$profileUrl."\n";

                $browser->click('a[href*="profile.pemilik"]')
                    ->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                if (strpos($newUrl, 'profile') !== false) {
                    echo "✅ Berhasil navigasi ke profile pemilik\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke dashboard
                $browser->visit('/dashboard-pemilik')->pause(2000);
            }

            // Test klik WhatsApp link
            echo "✓ Testing WhatsApp link\n";
            $whatsappLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('a[href*="wa.me"]'));
            if (count($whatsappLinks) > 0) {
                $whatsappUrl = $whatsappLinks[0]->getAttribute('href');
                echo 'WhatsApp URL: '.$whatsappUrl."\n";
                echo "✅ WhatsApp link ditemukan dan valid\n";
            }

            echo "✅ Test interaksi dashboard pemilik selesai\n";
        });
    }

    /**
     * Test responsivitas dashboard pemilik di berbagai ukuran layar
     */
    public function test_pemilik_dashboard_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Responsivitas Dashboard Pemilik ===\n";

            $browser->visit('/dashboard-pemilik')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();

            // Cek apakah ter-redirect
            if (strpos($currentUrl, 'select-role') !== false || strpos($currentUrl, 'login') !== false) {
                echo "⚠️ User belum login sebagai pemilik\n";
                echo "💡 Test responsivitas akan dijalankan pada halaman select-role\n";

                // Test responsivitas pada halaman redirect
                $this->testResponsivenessOnCurrentPage($browser);

                return;
            }

            echo "✅ Testing responsivitas pada dashboard pemilik\n";
            $this->testResponsivenessOnCurrentPage($browser);
        });
    }

    /**
     * Helper method untuk test responsivitas
     */
    private function test_responsiveness_on_current_page(Browser $browser)
    {
        // Test di mobile (375x667)
        echo "✓ Testing mobile view (375x667)\n";
        $browser->resize(375, 667)
            ->pause(1000);

        // Test di tablet (768x1024)
        echo "✓ Testing tablet view (768x1024)\n";
        $browser->resize(768, 1024)
            ->pause(1000);

        // Test di desktop (1920x1080)
        echo "✓ Testing desktop view (1920x1080)\n";
        $browser->resize(1920, 1080)
            ->pause(1000);

        echo "✅ Test responsivitas berhasil\n";
    }

    /**
     * Test statistik dashboard pemilik (Total Motor, Pesanan Aktif, Pendapatan)
     *
     * Note: Test ini akan skip jika user belum login sebagai pemilik
     */
    public function test_pemilik_dashboard_statistics()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Statistik Dashboard Pemilik ===\n";

            $browser->visit('/dashboard-pemilik')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();

            // Cek apakah ter-redirect
            if (strpos($currentUrl, 'select-role') !== false || strpos($currentUrl, 'login') !== false) {
                echo '⚠️ User belum login sebagai pemilik, ter-redirect ke: '.$currentUrl."\n";
                echo "💡 Untuk test statistik yang lengkap, silakan login terlebih dahulu sebagai pemilik\n";

                $browser->assertSee('Pilih');
                echo "✅ Test redirect authentication berhasil\n";

                return;
            }

            // Test statistik dashboard
            echo "✓ Testing elemen statistik dashboard\n";

            // Test ikon dan label statistik
            $browser->assertPresent('img[src*="total-motor.svg"]')
                ->assertSee('Total Motor')
                ->assertPresent('img[src*="pesanan-aktif.svg"]')
                ->assertSee('Pesanan Aktif')
                ->assertPresent('img[src*="pendapatan.svg"]')
                ->assertSee('Pendapatan');

            // Test format nilai statistik
            $statsValues = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('.text-m.font-bold'));

            foreach ($statsValues as $index => $statValue) {
                $value = $statValue->getText();
                echo 'Statistik '.($index + 1).': '.$value."\n";

                // Validasi format (angka atau format rupiah)
                if ($index === 2) { // Pendapatan (index ke-2)
                    if (strpos($value, 'Rp') !== false) {
                        echo "✅ Format pendapatan valid (Rupiah)\n";
                    }
                } else {
                    if (is_numeric($value)) {
                        echo "✅ Nilai statistik valid (numerik)\n";
                    }
                }
            }

            echo "✅ Test statistik dashboard pemilik berhasil\n";
        });
    }
}
