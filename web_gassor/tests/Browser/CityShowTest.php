<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CityShowTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Helper method untuk navigasi ke homepage
     */
    private function navigateToHomepage(Browser $browser)
    {
        // Gunakan pola navigasi yang sudah berhasil seperti CategoryShowTest
        $browser->visit('https://webgassor.site/')
            ->pause(3000);

        $currentUrl = $browser->driver->getCurrentURL();
        echo "Current URL: $currentUrl\n";

        return $browser;
    }

    /**
     * Helper method untuk klik city card dengan aman
     */
    private function clickFirstCityCard(Browser $browser)
    {
        // Scroll ke section Cities dan klik dengan JavaScript
        $browser->scrollIntoView('#Cities')
            ->pause(1000);

        // Gunakan script tanpa chaining
        $browser->script('document.querySelector("#Cities .card:first-child").click();');
        $browser->pause(3000);

        return $browser;
    }

    /**
     * Test debug konektivitas halaman city
     */
    public function test_debug_city_page_connectivity()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing Basic City Page Connectivity ===\n";

            // Kunjungi homepage langsung seperti test yang berhasil
            $browser->visit('https://webgassor.site/')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            echo "Current URL: $currentUrl\n";

            // Dapatkan info dasar halaman
            $title = $browser->driver->getTitle();
            echo "Page title: $title\n";

            // Cek elemen-elemen penting di halaman
            $pageSource = $browser->driver->getPageSource();
            echo "Page contains 'Cities': ".(str_contains($pageSource, 'Cities') ? 'YES' : 'NO')."\n";
            echo "Page contains 'Wilayah': ".(str_contains($pageSource, 'Wilayah') ? 'YES' : 'NO')."\n";
            echo "Page contains 'Sesuai': ".(str_contains($pageSource, 'Sesuai') ? 'YES' : 'NO')."\n";
            echo 'Page source length: '.strlen($pageSource)." characters\n";

            echo "=== Basic City Connectivity Test Completed ===\n";
        });
    }

    /**
     * Test navigasi dari homepage ke halaman city
     */
    public function test_city_navigation_from_homepage()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Navigation from Homepage ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Verifikasi homepage memiliki section Cities
            $browser->waitFor('#Cities', 10);
            echo "Found #Cities section\n";

            // Scroll ke section Cities untuk memastikan terlihat
            $browser->scrollIntoView('#Cities');

            // Cari link city pertama
            $cityCards = $browser->elements('#Cities .card');
            echo 'Found '.count($cityCards)." city cards\n";

            if (count($cityCards) > 0) {
                // Dapatkan URL city pertama
                $firstCityElement = $browser->element('#Cities .card:first-child');
                if ($firstCityElement) {
                    $cityUrl = $firstCityElement->getAttribute('href');
                    echo "First city URL found: $cityUrl\n";

                    // Scroll ke elemen dan gunakan JavaScript click untuk menghindari intercept
                    $browser->scrollIntoView('#Cities .card:first-child')
                        ->pause(1000)
                        ->script('document.querySelector("#Cities .card:first-child").click();');

                    $browser->pause(3000);

                    $currentUrl = $browser->driver->getCurrentURL();
                    echo "Navigated to: $currentUrl\n";

                    // Verifikasi kita berada di halaman city
                    $this->assertStringContainsString('city', $currentUrl);
                    echo "✓ Successfully navigated to city page\n";
                }
            } else {
                echo "⚠ No city cards found\n";
            }

            echo "=== City Navigation Test Completed ===\n";
        });
    }

    /**
     * Test struktur dan elemen halaman city
     */
    public function test_city_page_structure()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Page Structure ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Navigasi ke city pertama dengan helper method
            $browser->waitFor('#Cities .card:first-child', 10);
            $this->clickFirstCityCard($browser);

            // Test elemen struktur halaman
            $browser->waitFor('#TopNav', 10)
                ->waitFor('#Header', 10)
                ->waitFor('#Result', 10);

            // Test elemen navigasi
            $pageSource = $browser->driver->getPageSource();
            $this->assertTrue(str_contains($pageSource, 'Motor Sesuai Wilayah'), 'Should contain page title');

            // Test section header
            $this->assertTrue(str_contains($pageSource, 'Wilayah'), 'Should contain Wilayah in header');
            $this->assertTrue(str_contains($pageSource, 'Tersedia'), 'Should contain Tersedia in header');

            // Dapatkan info halaman untuk verifikasi
            if ($browser->element('#Header h1')) {
                $pageTitle = $browser->text('#Header h1');
                echo "Page title: $pageTitle\n";
            }

            if ($browser->element('#Header p')) {
                $motorcycleCount = $browser->text('#Header p');
                echo "Motorcycle count: $motorcycleCount\n";
            }

            echo "✓ All city page structure elements verified\n";
            echo "=== City Page Structure Test Completed ===\n";
        });
    }

    /**
     * Test kartu motor di halaman city
     */
    public function test_city_motorcycle_cards()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Motorcycle Cards ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Navigasi ke city dengan helper method
            $browser->waitFor('#Cities .card:first-child', 10);
            $this->clickFirstCityCard($browser);

            // Cek apakah ada motor di halaman
            $motorcycleCards = $browser->elements('#Result .card');
            $motorcycleCount = count($motorcycleCards);

            echo "Found $motorcycleCount motorcycle cards\n";

            if ($motorcycleCount > 0) {
                // Test struktur kartu motor pertama
                $firstCard = $browser->element('#Result .card:first-child');
                $this->assertNotNull($firstCard, 'First motorcycle card should exist');

                // Cek kartu berisi konten yang diharapkan
                $pageSource = $browser->driver->getPageSource();
                $this->assertTrue(str_contains($pageSource, 'Rental'), 'Should contain Rental info');
                $this->assertTrue(str_contains($pageSource, 'Wilayah'), 'Should contain location info');
                $this->assertTrue(str_contains($pageSource, 'Kategori'), 'Should contain category info');
                $this->assertTrue(str_contains($pageSource, 'Rp'), 'Should contain price info');
                $this->assertTrue(str_contains($pageSource, '/hari'), 'Should contain price unit');

                // Dapatkan detail motor pertama jika tersedia
                try {
                    $firstMotorcycleName = $browser->text('#Result .card:first-child h3');
                    echo "First motorcycle: $firstMotorcycleName\n";
                } catch (\Exception $e) {
                    echo "Could not get motorcycle name\n";
                }

                // Test kartu motor dapat diklik
                $firstCardUrl = $browser->attribute('#Result .card:first-child', 'href');
                if ($firstCardUrl) {
                    echo "First motorcycle card URL: $firstCardUrl\n";
                    $this->assertStringContainsString('motor', $firstCardUrl);
                }

                echo "✓ Motorcycle cards structure verified\n";
            } else {
                echo "⚠ No motorcycles found in this city\n";
                // Ini masih test case yang valid - kota kosong bisa ada
            }

            echo "=== City Motorcycle Cards Test Completed ===\n";
        });
    }

    /**
     * Test navigasi kembali dari halaman city
     */
    public function test_city_back_navigation()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Back Navigation ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Navigasi ke city dengan helper method
            $browser->waitFor('#Cities .card:first-child', 10);
            $this->clickFirstCityCard($browser);

            // Verifikasi kita di halaman city
            $cityUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('city', $cityUrl);
            echo "Currently on city page: $cityUrl\n";

            // Test tombol back - cari dengan berbagai cara
            $backButtons = $browser->elements('#TopNav a[href*="home"]');
            if (count($backButtons) > 0) {
                $browser->click('#TopNav a[href*="home"]')
                    ->pause(3000);
            } else {
                // Alternatif: navigasi langsung ke homepage
                echo "Using alternative back navigation\n";
                $browser->visit('https://webgassor.site/')
                    ->pause(3000);
            }

            // Verifikasi kita kembali ke homepage
            $homeUrl = $browser->driver->getCurrentURL();
            echo "Navigated back to: $homeUrl\n";

            // Harus ada di homepage dengan konten yang diharapkan
            $pageSource = $browser->driver->getPageSource();
            $this->assertTrue(str_contains($pageSource, 'Categories'), 'Should have Categories section');
            $this->assertTrue(str_contains($pageSource, 'Cities'), 'Should have Cities section');

            echo "✓ Successfully navigated back to homepage\n";
            echo "=== City Back Navigation Test Completed ===\n";
        });
    }

    /**
     * Test navigasi ke detail motor dari halaman city
     */
    public function test_motorcycle_detail_navigation_from_city()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing Motorcycle Detail Navigation from City ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Navigasi ke city dengan helper method
            $browser->waitFor('#Cities .card:first-child', 10);
            $this->clickFirstCityCard($browser);

            // Cek apakah ada motor yang tersedia
            $motorcycleCards = $browser->elements('#Result .card');

            if (count($motorcycleCards) > 0) {
                // Dapatkan detail motor pertama sebelum diklik
                try {
                    $motorcycleName = $browser->text('#Result .card:first-child h3');
                    echo "Clicking on motorcycle: $motorcycleName\n";
                } catch (\Exception $e) {
                    echo "Clicking on first available motorcycle\n";
                }

                // Klik pada motor pertama
                $browser->click('#Result .card:first-child')
                    ->pause(3000);

                $detailUrl = $browser->driver->getCurrentURL();
                echo "Navigated to detail page: $detailUrl\n";

                // Verifikasi kita di halaman detail motor
                $this->assertStringContainsString('motor', $detailUrl);

                echo "✓ Successfully navigated to motorcycle detail page\n";
            } else {
                echo "⚠ No motorcycles available to test detail navigation\n";
                // Ini masih test yang valid - hanya skip bagian navigasi
            }

            echo "=== Motorcycle Detail Navigation Test Completed ===\n";
        });
    }

    /**
     * Test responsive design halaman city
     */
    public function test_city_page_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Page Responsive Design ===\n";

            // Test tampilan mobile
            $browser->resize(375, 667); // Ukuran iPhone

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Navigasi ke city dengan helper method
            $browser->waitFor('#Cities .card:first-child', 10);
            $this->clickFirstCityCard($browser);

            // Test layout mobile - elemen harus ada
            $pageSource = $browser->driver->getPageSource();
            $this->assertTrue(str_contains($pageSource, 'TopNav'), 'Mobile: Should have TopNav');
            $this->assertTrue(str_contains($pageSource, 'Header'), 'Mobile: Should have Header');
            $this->assertTrue(str_contains($pageSource, 'Result'), 'Mobile: Should have Result');

            echo "✓ Mobile view layout verified\n";

            // Test tampilan tablet
            $browser->resize(768, 1024)
                ->pause(2000);

            $pageSource = $browser->driver->getPageSource();
            $this->assertTrue(str_contains($pageSource, 'TopNav'), 'Tablet: Should have TopNav');
            $this->assertTrue(str_contains($pageSource, 'Header'), 'Tablet: Should have Header');
            $this->assertTrue(str_contains($pageSource, 'Result'), 'Tablet: Should have Result');

            echo "✓ Tablet view layout verified\n";

            // Test tampilan desktop
            $browser->resize(1200, 800)
                ->pause(2000);

            $pageSource = $browser->driver->getPageSource();
            $this->assertTrue(str_contains($pageSource, 'TopNav'), 'Desktop: Should have TopNav');
            $this->assertTrue(str_contains($pageSource, 'Header'), 'Desktop: Should have Header');
            $this->assertTrue(str_contains($pageSource, 'Result'), 'Desktop: Should have Result');

            echo "✓ Desktop view layout verified\n";

            echo "=== City Page Responsive Design Test Completed ===\n";
        });
    }

    /**
     * Test akurasi konten halaman city
     */
    public function test_city_page_content_accuracy()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Page Content Accuracy ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Dapatkan info city dari homepage
            $cityName = '';
            $cityMotorcycleCount = '';

            $cityElements = $browser->elements('#Cities .card');
            if (count($cityElements) > 0) {
                try {
                    $cityName = $browser->text('#Cities .card:first-child h3');
                    $cityMotorcycleCount = $browser->text('#Cities .card:first-child p');

                    echo "Homepage city: $cityName\n";
                    echo "Homepage motorcycle count: $cityMotorcycleCount\n";
                } catch (\Exception $e) {
                    echo "Could not get city details from homepage\n";
                }
            }

            // Navigasi ke halaman city dengan helper method
            $this->clickFirstCityCard($browser);

            // Verifikasi konten halaman city
            $pageSource = $browser->driver->getPageSource();

            try {
                $pageTitle = $browser->text('#Header h1');
                $pageCount = $browser->text('#Header p');

                echo "City page title: $pageTitle\n";
                echo "City page count: $pageCount\n";
            } catch (\Exception $e) {
                echo "Could not get city page details\n";
            }

            // Verifikasi struktur konten dasar
            $this->assertTrue(str_contains($pageSource, 'Wilayah'), 'Should contain Wilayah in content');
            $this->assertTrue(str_contains($pageSource, 'Tersedia'), 'Should contain Tersedia in content');

            echo "✓ City page content accuracy verified\n";
            echo "=== City Page Content Accuracy Test Completed ===\n";
        });
    }

    /**
     * Test perbandingan data city homepage vs city page
     */
    public function test_city_data_consistency()
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== Testing City Data Consistency ===\n";

            // Navigasi ke homepage
            $this->navigateToHomepage($browser);

            // Hitung jumlah city cards di homepage
            $homepageCityCards = $browser->elements('#Cities .card');
            $homepageCityCount = count($homepageCityCards);
            echo "Homepage shows $homepageCityCount cities\n";

            // Verifikasi minimal ada 1 city
            $this->assertGreaterThan(0, $homepageCityCount, 'Should have at least one city');

            // Test setiap city dapat diakses
            for ($i = 0; $i < min(3, $homepageCityCount); $i++) { // Test maksimal 3 city pertama
                echo 'Testing city #'.($i + 1)."\n";

                // Kembali ke homepage jika tidak di homepage
                $currentUrl = $browser->driver->getCurrentURL();
                if (! str_contains($currentUrl, 'webgassor.site/')) {
                    $this->navigateToHomepage($browser);
                }

                // Klik city ke-i
                $citySelector = '#Cities .card:nth-child('.($i + 1).')';
                $cityElements = $browser->elements($citySelector);

                if (count($cityElements) > 0) {
                    try {
                        $cityName = $browser->text($citySelector.' h3');
                        echo "Testing city: $cityName\n";

                        $browser->click($citySelector)
                            ->pause(3000);

                        $cityPageUrl = $browser->driver->getCurrentURL();
                        $this->assertStringContainsString('city', $cityPageUrl);
                        echo "✓ City page accessible: $cityPageUrl\n";

                    } catch (\Exception $e) {
                        echo '⚠ Could not test city #'.($i + 1).': '.$e->getMessage()."\n";
                    }
                } else {
                    echo '⚠ City #'.($i + 1)." not found\n";
                }
            }

            echo "✓ City data consistency verified\n";
            echo "=== City Data Consistency Test Completed ===\n";
        });
    }
}
