<?php

namespace App\Http\Controllers;

use App\Services\HolidayService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(HolidayService $holidayService): Response
    {
        $states = $holidayService->getAllStates();
        $holidayUrls = $holidayService->getAllHolidaySlugsForSitemap();

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
}
