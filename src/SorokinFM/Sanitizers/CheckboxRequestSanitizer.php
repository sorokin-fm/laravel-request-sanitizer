<?php

namespace SorokinFM\Sanitizers;

use Illuminate\Foundation\Http\FormRequest;
use SorokinFM\SanitizerInterface;

class CheckboxRequestSanitizer implements SanitizerInterface {
    public function sanitize(array & $values, string $key, FormRequest $request, string $params) {
        $values[$key] = isset($values[$key]) ? 1 : 0;
    }
}
