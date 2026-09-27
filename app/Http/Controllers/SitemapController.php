<?php

namespace App\Http\Controllers;

use App\Services\HolidayService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(HolidayService $holidayService): Response
    {
        $years = $holidayService->getAvailableYears();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $xml .= '  <sitemap>' . "\n";
        $xml .= '    <loc>' . route('sitemap.section', ['section' => 'main']) . '</loc>' . "\n";
        $xml .= '  </sitemap>' . "\n";

        foreach ($years as $year) {
            $xml .= '  <sitemap>' . "\n";
            $xml .= '    <loc>' . route('sitemap.section', ['section' => $year]) . '</loc>' . "\n";
            $xml .= '  </sitemap>' . "\n";
        }

        $xml .= '</sitemapindex>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    public function section(string $section, HolidayService $holidayService): Response
    {
        $states = $holidayService->getAllStates();

        if ($section === 'main') {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route('home') . '</loc>' . "\n";
            $xml .= '    <changefreq>daily</changefreq>' . "\n";
            $xml .= '    <priority>1.0</priority>' . "\n";
            $xml .= '  </url>' . "\n";

            foreach ($states as $state) {
                $xml .= '  <url>' . "\n";
                $xml .= '    <loc>' . route('bundesland', ['bundesland' => $state['slug']]) . '</loc>' . "\n";
                $xml .= '    <changefreq>daily</changefreq>' . "\n";
                $xml .= '    <priority>0.9</priority>' . "\n";
                $xml .= '  </url>' . "\n";
            }

            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route('impressum') . '</loc>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <priority>0.3</priority>' . "\n";
            $xml .= '  </url>' . "\n";

            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route('datenschutz') . '</loc>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <priority>0.3</priority>' . "\n";
            $xml .= '  </url>' . "\n";

            $xml .= '</urlset>';

            return response($xml, 200, [
                'Content-Type' => 'application/xml; charset=utf-8',
            ]);
        }

        if (is_numeric($section)) {
            $year = (int)$section;
            $holidayUrls = $holidayService->getHolidaySlugsForYear($year);

            if (empty($holidayUrls)) {
                abort(404);
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            foreach ($holidayUrls as $item) {
                $xml .= '  <url>' . "\n";
                $xml .= '    <loc>' . route('holiday.detail', [
                    'bundesland' => $item['state_slug'],
                    'ferien'     => $item['holiday_slug'],
                ]) . '</loc>' . "\n";
                $xml .= '    <changefreq>weekly</changefreq>' . "\n";
                $xml .= '    <priority>0.8</priority>' . "\n";
                $xml .= '  </url>' . "\n";
            }

            $xml .= '</urlset>';

            return response($xml, 200, [
                'Content-Type' => 'application/xml; charset=utf-8',
            ]);
        }

        abort(404);
    }
}
