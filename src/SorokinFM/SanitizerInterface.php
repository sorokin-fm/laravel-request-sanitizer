<?php

namespace SorokinFM;

use Illuminate\Foundation\Http\FormRequest;

interface SanitizerInterface {
    public function sanitize(array & $values, string $key, FormRequest $request);
}
