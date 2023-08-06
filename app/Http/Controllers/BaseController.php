<?php

namespace App\Http\Controllers;

use App\Services\Service;
use App\Traits\ApiResponse;

class BaseController extends Controller
{
    use ApiResponse;
    
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
