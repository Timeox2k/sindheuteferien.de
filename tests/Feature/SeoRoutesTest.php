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
}
