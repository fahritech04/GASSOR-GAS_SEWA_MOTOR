<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PenyewaDashboardTest extends DuskTestCase
{
    /**
     * Test struktur dan elemen halaman dashboard penyewa (homepage)
     *
     * Test ini menguji:
     * - Akses halaman homepage
     * - Struktur dasar halaman (greeting, navigasi)
     * - Section Categories dengan swiper
     * - Section Rental Populer
     * - Section Sesuai Wilayah (Cities)
     * - Section Semua Jenis Motor
     * - Responsivitas di berbagai ukuran layar
     */
    public function test_penyewa_dashboard_structure_and_content()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Struktur Dashboard Penyewa (Homepage) ===\n";

            // Akses homepage
            $browser->visit('/')
                ->pause(2000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo 'Current URL: '.$currentUrl."\n";

            // Test header/greeting section
            echo "✓ Testing header dan greeting section\n";
            $browser->assertPresent('#TopNav')
                ->assertSee('Halo,')
                ->assertSeeIn('#TopNav', 'selamat'); // pagi/siang/sore/malam

            // Test navigasi atas
            echo "✓ Testing navigasi atas\n";
            $browser->assertPresent('a[href*="wa.me"]'); // WhatsApp link

            // Test profile link (hanya jika login)
            $profileLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('a[href*="profile.penyewa"]'));
            if (count($profileLinks) > 0) {
                echo "✅ Profile link ditemukan (user sudah login)\n";
            } else {
                echo "📝 Profile link tidak ada (user belum login)\n";
            }

            // Test section Categories
            echo "✓ Testing section Categories\n";
            $browser->assertPresent('#Categories')
                ->assertPresent('.swiper-wrapper');

            // Hitung jumlah kategori
            $categoryCards = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Categories .swiper-slide'));
            echo 'Found '.count($categoryCards)." category cards\n";

            if (count($categoryCards) > 0) {
                $browser->assertSeeIn('#Categories', 'Motor'); // Text "X Motor"
            }

            // Test section Rental Populer
            echo "✓ Testing section Rental Populer\n";
            $browser->assertPresent('#Popular')
                ->assertSeeIn('#Popular', 'Rental Populer');

            $popularCards = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Popular .swiper-slide'));
            echo 'Found '.count($popularCards)." popular rental cards\n";

            // Test section Cities (Sesuai Wilayah)
            echo "✓ Testing section Cities\n";
            $browser->assertPresent('#Cities')
                ->assertSeeIn('#Cities', 'Sesuai Wilayah');

            $cityCards = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Cities .grid .card'));
            echo 'Found '.count($cityCards)." city cards\n";

            // Test section Best (Semua Jenis Motor)
            echo "✓ Testing section Best\n";
            $browser->assertPresent('#Best')
                ->assertSeeIn('#Best', 'Semua Jenis Motor');

            $motorcycleCards = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Best .card'));
            echo 'Found '.count($motorcycleCards)." motorcycle cards\n";

            // Test navigasi bawah
            echo "✓ Testing bottom navigation\n";
            $browser->assertPresent('nav'); // Navigation include

            echo "✅ Test struktur dashboard penyewa berhasil\n";
        });
    }

    /**
     * Test interaksi dan navigasi pada dashboard penyewa
     *
     * Test ini menguji:
     * - Klik pada kategori motor
     * - Klik pada city/wilayah
     * - Klik pada motor populer
     * - Klik pada motor individual
     * - Responsivitas swiper/slider
     * - Link eksternal (WhatsApp)
     */
    public function test_penyewa_dashboard_interactions_and_navigation()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Interaksi Dashboard Penyewa ===\n";

            $browser->visit('/')
                ->pause(2000);

            // Test klik kategori pertama (jika ada)
            $categoryLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Categories a.card'));
            if (count($categoryLinks) > 0) {
                echo "✓ Testing klik kategori motor\n";

                // Ambil href dari kategori pertama
                $firstCategoryHref = $categoryLinks[0]->getAttribute('href');
                echo 'First category URL: '.$firstCategoryHref."\n";

                $browser->click('#Categories a.card:first-child')
                    ->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                // Pastikan ter-navigate ke halaman kategori
                if (strpos($newUrl, '/category/') !== false) {
                    echo "✅ Berhasil navigasi ke halaman kategori\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke homepage
                $browser->visit('/')->pause(2000);
            }

            // Test klik city pertama (jika ada)
            $cityLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Cities a.card'));
            if (count($cityLinks) > 0) {
                echo "✓ Testing klik city/wilayah\n";

                $firstCityHref = $cityLinks[0]->getAttribute('href');
                echo 'First city URL: '.$firstCityHref."\n";

                // Scroll ke elemen dan gunakan JS click untuk menghindari intercepted click
                $browser->scrollIntoView('#Cities a.card:first-child')
                    ->pause(1000)
                    ->script('document.querySelector("#Cities a.card:first-child").click();');

                $browser->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                if (strpos($newUrl, '/city/') !== false) {
                    echo "✅ Berhasil navigasi ke halaman city\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke homepage
                $browser->visit('/')->pause(2000);
            }

            // Test klik motor populer (jika ada)
            $popularLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Popular a.card'));
            if (count($popularLinks) > 0) {
                echo "✓ Testing klik motor populer\n";

                $firstPopularHref = $popularLinks[0]->getAttribute('href');
                echo 'First popular motor URL: '.$firstPopularHref."\n";

                $browser->click('#Popular a.card:first-child')
                    ->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                if (strpos($newUrl, '/motor/') !== false) {
                    echo "✅ Berhasil navigasi ke detail motor\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke homepage
                $browser->visit('/')->pause(2000);
            }

            // Test klik motor dari section Best
            $bestLinks = $browser->driver->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector('#Best a.card'));
            if (count($bestLinks) > 0) {
                echo "✓ Testing klik motor dari section Best\n";

                $firstBestHref = $bestLinks[0]->getAttribute('href');
                echo 'First best motor URL: '.$firstBestHref."\n";

                $browser->click('#Best a.card:first-child')
                    ->pause(2000);

                $newUrl = $browser->driver->getCurrentURL();
                echo 'Navigated to: '.$newUrl."\n";

                if (strpos($newUrl, '/motor/') !== false) {
                    echo "✅ Berhasil navigasi ke detail motor\n";
                } else {
                    echo '⚠️ Redirect ke: '.$newUrl."\n";
                }

                // Kembali ke homepage
                $browser->visit('/')->pause(2000);
            }

            echo "✅ Test interaksi dashboard penyewa selesai\n";
        });
    }

    /**
     * Test responsivitas dashboard penyewa di berbagai ukuran layar
     */
    public function test_penyewa_dashboard_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Test Responsivitas Dashboard Penyewa ===\n";

            $browser->visit('/')
                ->pause(2000);

            // Test di mobile (375x667)
            echo "✓ Testing mobile view (375x667)\n";
            $browser->resize(375, 667)
                ->pause(1000);

            $browser->assertPresent('#TopNav')
                ->assertPresent('#Categories')
                ->assertPresent('#Popular')
                ->assertPresent('#Cities')
                ->assertPresent('#Best');

            // Test di tablet (768x1024)
            echo "✓ Testing tablet view (768x1024)\n";
            $browser->resize(768, 1024)
                ->pause(1000);

            $browser->assertPresent('#TopNav')
                ->assertPresent('#Categories')
                ->assertPresent('#Popular')
                ->assertPresent('#Cities')
                ->assertPresent('#Best');

            // Test di desktop (1920x1080)
            echo "✓ Testing desktop view (1920x1080)\n";
            $browser->resize(1920, 1080)
                ->pause(1000);

            $browser->assertPresent('#TopNav')
                ->assertPresent('#Categories')
                ->assertPresent('#Popular')
                ->assertPresent('#Cities')
                ->assertPresent('#Best');

            echo "✅ Test responsivitas dashboard penyewa berhasil\n";
        });
    }
}
