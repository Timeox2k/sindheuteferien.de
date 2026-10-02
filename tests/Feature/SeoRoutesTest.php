<?php

namespace Tests\Feature;

use App\Models\SchoolHoliday;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoRoutesTest extends TestCase
{
    public function test_home_page_contains_seo_tags(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<title>', false);
        $response->assertSee('Sind heute Ferien?', false);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('FAQPage', false);
    }

    public function test_state_page_contains_state_specific_seo(): void
    {
        $response = $this->get('/nordrhein-westfalen');

        $response->assertStatus(200);
        $response->assertSee('Ferien Nordrhein-Westfalen (NRW)', false);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('nordrhein-westfalen', false);
        $response->assertSee('FAQPage', false);
        $response->assertSee('BreadcrumbList', false);
    }

    public function test_holiday_detail_page_loads_with_exact_keyword_targeting(): void
    {
        SchoolHoliday::firstOrCreate(
            ['uuid' => 'test-herbstferien-nrw-2026'],
            [
                'name'           => 'Herbstferien',
                'start_date'     => '2026-10-17',
                'end_date'       => '2026-10-31',
                'regional_scope' => 'subdivision',
                'temporal_scope' => '2026',
                'nationwide'     => false,
                'subdivisions'   => [['code' => 'DE-NW', 'shortName' => 'NW']],
            ]
        );

        $response = $this->get('/nordrhein-westfalen/herbstferien-2026');

        $response->assertStatus(200);
        $response->assertSee('Herbstferien NRW 2026 (Nordrhein-Westfalen)', false);
        $response->assertSee('17.10.2026', false);
        $response->assertSee('31.10.2026', false);
        $response->assertSee('BreadcrumbList', false);
        $response->assertSee('FAQPage', false);
    }

    public function test_sitemap_index_and_year_sections(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<sitemapindex', false);
        $response->assertSee('/sitemap-main.xml', false);
        $response->assertSee('/sitemap-2026.xml', false);

        $mainResponse = $this->get('/sitemap-main.xml');
        $mainResponse->assertStatus(200);
        $mainResponse->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $mainResponse->assertSee('<urlset', false);
        $mainResponse->assertSee('/nordrhein-westfalen', false);
        $mainResponse->assertSee('/bayern', false);

        $yearResponse = $this->get('/sitemap-2026.xml');
        $yearResponse->assertStatus(200);
        $yearResponse->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $yearResponse->assertSee('<urlset', false);
    }

    public function test_robots_txt_returns_text_with_sitemap_link(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Sitemap:', false);
    }

    public function test_impressum_loads_with_legal_information(): void
    {
        $response = $this->get('/impressum');

        $response->assertStatus(200);
        $response->assertSee('Impressum', false);
        $response->assertSee('Angaben gemäß § 5 DDG', false);
        $response->assertSee('Tiziano Santo Metzler', false);
    }

    public function test_datenschutz_loads_with_adsense_and_gdpr_clauses(): void
    {
        $response = $this->get('/datenschutz');

        $response->assertStatus(200);
        $response->assertSee('Datenschutzerklärung', false);
        $response->assertSee('Google AdSense', false);
        $response->assertSee('adssettings.google.com', false);
        $response->assertSee('Cloudflare', false);
    }

    public function test_adsense_script_renders_when_client_id_configured(): void
    {
        config(['services.adsense.client_id' => 'ca-pub-1234567890']);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890', false);
    }

    public function test_navigation_bar_renders_across_all_pages(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('SindHeuteFerien', false);
        $responseHome->assertSee('favicon.svg', false);
        $responseHome->assertSee('title="Ferien in Bayern"', false);

        $responseState = $this->get('/bayern');
        $responseState->assertStatus(200);
        $responseState->assertSee('SindHeuteFerien', false);
        $responseState->assertSee('class="active"', false);
        $responseState->assertSee('Übersicht', false);

        $responseLegal = $this->get('/impressum');
        $responseLegal->assertStatus(200);
        $responseLegal->assertSee('SindHeuteFerien', false);
        $responseLegal->assertSee('favicon.svg', false);
    }

    public function test_pwa_manifest_and_service_worker_configured(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('rel="manifest"', false);
        $response->assertSee('/manifest.webmanifest', false);
        $response->assertSee('/sw.js', false);
        $response->assertSee('name="theme-color"', false);

        $this->assertFileExists(public_path('manifest.webmanifest'));
        $this->assertFileExists(public_path('sw.js'));
        $this->assertFileExists(public_path('icon-192.png'));
        $this->assertFileExists(public_path('icon-512.png'));
    }
}
