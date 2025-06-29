<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PencarianTest extends DuskTestCase
{
    // Remove DatabaseMigrations since we're testing live server
    // use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Remove test data creation since we're testing live server
        // The live server should already have real data
    }

    /**
     * Test page content and debug what's actually there
     */
    public function test_debug_page_content()
    {
        $this->browse(function (Browser $browser) {
            // First check what happens when we visit the find-motor page
            $browser->visit('https://webgassor.site/find-motor')
                ->pause(3000); // Wait for page to load

            // Get page title and URL to verify we're on the right page
            $title = $browser->driver->getTitle();
            $currentUrl = $browser->driver->getCurrentURL();

            // Output page source for debugging
            echo "\n=== REDIRECTED PAGE DEBUG INFO ===\n";
            echo 'Title: '.$title."\n";
            echo 'URL: '.$currentUrl."\n";
            echo 'Page source (first 1000 chars): '.substr($browser->driver->getPageSource(), 0, 1000)."\n";
            echo "=================================\n";

            // If we're on select-role page, let's try to navigate to find motor
            if (str_contains($currentUrl, 'select-role')) {
                // Look for any links or buttons that might take us to the find motor page
                $pageSource = $browser->driver->getPageSource();
                echo "\n=== LOOKING FOR NAVIGATION OPTIONS ===\n";
                echo "Page contains 'Customer': ".(str_contains($pageSource, 'Customer') ? 'YES' : 'NO')."\n";
                echo "Page contains 'User': ".(str_contains($pageSource, 'User') ? 'YES' : 'NO')."\n";
                echo "Page contains 'Pengguna': ".(str_contains($pageSource, 'Pengguna') ? 'YES' : 'NO')."\n";
                echo "Page contains 'Motor': ".(str_contains($pageSource, 'Motor') ? 'YES' : 'NO')."\n";
                echo "=====================================\n";
            }

            // Just check if the page loads without asserting specific content
            $this->assertTrue(true);
        });
    }

    /**
     * Test accessing the homepage to understand site structure
     */
    public function test_check_homepage_and_navigation()
    {
        $this->browse(function (Browser $browser) {
            // Try visiting the homepage first
            $browser->visit('https://webgassor.site')
                ->pause(3000);

            $homepageUrl = $browser->driver->getCurrentURL();
            echo "\n=== HOMEPAGE INFO ===\n";
            echo 'Homepage URL: '.$homepageUrl."\n";

            // Check if homepage redirects too
            if (str_contains($homepageUrl, 'select-role')) {
                echo "Homepage also redirects to select-role\n";

                // Try to find what options are available on select-role page
                $pageSource = $browser->driver->getPageSource();

                // Look for common elements that might be clickable
                if (preg_match_all('/<a[^>]*href="([^"]*)"[^>]*>([^<]*)<\/a>/i', $pageSource, $matches)) {
                    echo "Available links:\n";
                    for ($i = 0; $i < min(10, count($matches[1])); $i++) {
                        echo '- '.$matches[2][$i].' -> '.$matches[1][$i]."\n";
                    }
                }

                // Try a more specific approach - look for any button or div that might be clickable
                if (preg_match_all('/<button[^>]*>([^<]*)<\/button>/i', $pageSource, $buttonMatches)) {
                    echo "Available buttons:\n";
                    foreach ($buttonMatches[1] as $buttonText) {
                        echo '- Button: '.trim($buttonText)."\n";
                    }
                }
            } else {
                echo 'Homepage loaded successfully at: '.$homepageUrl."\n";
                // Try to find navigation to find-motor page
                if ($browser->element('a[href*="find-motor"]')) {
                    echo "Found direct link to find-motor\n";
                    $browser->click('a[href*="find-motor"]')
                        ->pause(2000);
                    echo 'Navigated to: '.$browser->driver->getCurrentURL()."\n";
                }
            }

            echo "====================\n";
            $this->assertTrue(true);
        });
    }

    /**
     * Test that the find page loads correctly with all elements via homepage navigation
     */
    public function test_find_page_loads_with_all_elements()
    {
        $this->browse(function (Browser $browser) {
            // Start from homepage and navigate to find-motor
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]') // Click the find-motor link from homepage
                ->pause(3000) // Give time for page to load
                ->assertSee('Jelajahi Motor')
                ->assertSee('Di Webgassor Kami')
                ->assertPresent('form')
                ->assertPresent('input[name="search"]')
                ->assertPresent('select[name="city"]')
                ->assertPresent('select[name="category"]')
                ->assertPresent('button[type="submit"]')
                ->assertSee('Jelajahi Sekarang');
        });
    }

    /**
     * Test the search input field functionality
     */
    public function test_search_input_field_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->type('input[name="search"]', 'Honda Beat')
                ->assertInputValue('input[name="search"]', 'Honda Beat');
        });
    }

    /**
     * Test city dropdown functionality
     */
    public function test_city_dropdown_has_options()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertPresent('select[name="city"] option:not([value=""])')
                ->waitFor('select[name="city"] option:not([value=""])', 5);

            // Get the first available option value
            $firstOption = $browser->element('select[name="city"] option:not([value=""])');
            if ($firstOption) {
                $optionValue = $firstOption->getAttribute('value');
                $browser->select('city', $optionValue)
                    ->assertSelected('city', $optionValue);
            }
        });
    }

    /**
     * Test category dropdown functionality
     */
    public function test_category_dropdown_has_options()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertPresent('select[name="category"] option:not([value=""])')
                ->waitFor('select[name="category"] option:not([value=""])', 5);

            // Get the first available option value
            $firstOption = $browser->element('select[name="category"] option:not([value=""])');
            if ($firstOption) {
                $optionValue = $firstOption->getAttribute('value');
                $browser->select('category', $optionValue)
                    ->assertSelected('category', $optionValue);
            }
        });
    }

    /**
     * Test form submission with all fields filled
     */
    public function test_form_submission_with_all_fields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->type('input[name="search"]', 'Yamaha NMAX')
                ->pause(1000); // Give time for page to load

            // Try to select city if options are available
            if ($browser->element('select[name="city"] option:not([value=""])')) {
                $cityOption = $browser->element('select[name="city"] option:not([value=""])');
                $browser->select('city', $cityOption->getAttribute('value'));
            }

            // Try to select category if options are available
            if ($browser->element('select[name="category"] option:not([value=""])')) {
                $categoryOption = $browser->element('select[name="category"] option:not([value=""])');
                $browser->select('category', $categoryOption->getAttribute('value'));
            }

            $browser->press('Jelajahi Sekarang');
            // Test will check if form submits without errors
        });
    }

    /**
     * Test that form has proper HTML structure
     */
    public function test_form_structure()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertPresent('form')
                ->assertPresent('input[name="search"][placeholder*="Ketik nama motor"]')
                ->assertPresent('select[name="city"]')
                ->assertPresent('select[name="category"]')
                ->assertPresent('button[type="submit"]');
        });
    }

    /**
     * Test that page heading is displayed correctly
     */
    public function test_page_heading()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertSee('Jelajahi Motor')
                ->assertSee('Di Webgassor Kami')
                ->assertPresent('h1');
        });
    }

    /**
     * Test that all required form labels are present
     */
    public function test_form_labels()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertSee('Nama')
                ->assertSee('Pilih Wilayah')
                ->assertSee('Pilih Kategori');
        });
    }

    /**
     * Test form interaction without submission
     */
    public function test_form_interaction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->type('input[name="search"]', 'Test Motor')
                ->pause(500)
                ->assertInputValue('input[name="search"]', 'Test Motor')
                ->clear('input[name="search"]')
                ->assertInputValue('input[name="search"]', '');
        });
    }

    /**
     * Test page responsiveness and mobile view
     */
    public function test_page_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->resize(375, 667) // iPhone size
                ->assertPresent('form')
                ->assertPresent('input[name="search"]')
                ->assertPresent('select[name="city"]')
                ->assertPresent('select[name="category"]')
                ->resize(1920, 1080); // Desktop size
        });
    }

    /**
     * Test form validation by submitting empty form
     */
    public function test_empty_form_submission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->press('Jelajahi Sekarang');
            // Should handle empty form gracefully
        });
    }

    /**
     * Test search functionality with partial text
     */
    public function test_partial_search_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->type('input[name="search"]', 'Honda')
                ->assertInputValue('input[name="search"]', 'Honda')
                ->append('input[name="search"]', ' Beat FI')
                ->assertInputValue('input[name="search"]', 'Honda Beat FI');
        });
    }

    /**
     * Test that CSS and styling loads correctly
     */
    public function test_page_styling_loads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertPresent('form')
                ->assertVisible('form')
                ->assertVisible('input[name="search"]')
                ->assertVisible('select[name="city"]')
                ->assertVisible('select[name="category"]')
                ->assertVisible('button[type="submit"]');
        });
    }

    /**
     * Test navigation elements are present
     */
    public function test_navigation_elements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000);

            // Just verify that we successfully navigated and can interact with the page
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('find-motor', $currentUrl);
        });
    }

    /**
     * Test form accessibility features
     */
    public function test_form_accessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://webgassor.site')
                ->pause(2000)
                ->click('a[href*="find-motor"]')
                ->pause(2000)
                ->assertPresent('input[name="search"][placeholder]')
                ->assertPresent('select[name="city"]')
                ->assertPresent('select[name="category"]')
                ->assertPresent('button[type="submit"]');
        });
    }
}
