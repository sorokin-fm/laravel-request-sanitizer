<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SorokinFM\RequestSanitizerTrait;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest {

    use RequestSanitizerTrait;
    const SANITIZE_RULES = [
        'enabled' => 'checkbox',
        'active' => 'checkbox',
        'logo:img/news'  => 'file',
        'picture:img/news'  => 'file',
    ];
}

$request = new Request([
    'enabled' => 'on',
    'logo' => '...',
    'picture' => '...',
]);
var_dump($request->input());
