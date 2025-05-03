<?php
/**
 * File: PageService.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\PageService
 */
class Page extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\PageService::class;
    }
}
