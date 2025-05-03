<?php
/**
 * File: ImpressumController.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Http\Controllers;

use App\Facades\Page;

class ImpressumController
{
    public function __invoke()
    {
        Page::setTitle("Impressum");
        return view('impressum');
    }
}
