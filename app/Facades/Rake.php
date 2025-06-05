<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Rake\RakeService;
use Illuminate\Support\Facades\Facade;

/**
 * @see RakeService
 */
class Rake extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RakeService::class;
    }
}
