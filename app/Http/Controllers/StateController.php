<?php

namespace App\Http\Controllers;

use App\Facades\Page;
use App\Services\HolidayService;
use App\Services\PageService;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __invoke(Request $request, PageService $page)
    {
        $routeId = $request->route('route');
        $routeToKuerzel = [
            'baden-wuerttemberg'     => 'bw',
            'bayern'                 => 'by',
            'berlin'                 => 'be',
            'brandenburg'            => 'bb',
            'bremen'                 => 'hb',
            'hamburg'                => 'hh',
            'hessen'                 => 'he',
            'mecklenburg-vorpommern' => 'mv',
            'niedersachsen'          => 'ni',
            'nordrhein-westfalen'    => 'nw',
            'rheinland-pfalz'        => 'rp',
            'saarland'               => 'sl',
            'sachsen'                => 'sn',
            'sachsen-anhalt'         => 'st',
            'schleswig-holstein'     => 'sh',
            'thueringen'             => 'th'
        ];

        if (isset($routeToKuerzel[$routeId])) {
            return view('sind-heute-ferien-in', ['bundesland' => $routeToKuerzel[$routeId]]);
        }

        abort(404);
    }
}
