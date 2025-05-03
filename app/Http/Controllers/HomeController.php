<?php
/**
 * File: HomeController.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Http\Controllers;

use App\Facades\Page;

class HomeController
{
    public function __invoke()
    {
        Page::setDescription("Sind heute Ferien? Finde heraus, in welchen deutschen Bundesländern heute Schulferien sind. Einfach, aktuell und übersichtlich.");
        return view('welcome');
    }
}
