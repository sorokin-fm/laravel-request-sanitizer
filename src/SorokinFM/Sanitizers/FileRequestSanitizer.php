<?php

namespace SorokinFM\Sanitizers;

use Illuminate\Foundation\Http\FormRequest;
use ElForastero\Transliterate\Transliteration;
use SorokinFM\SanitizerInterface;

class FileRequestSanitizer implements SanitizerInterface {
    public function sanitize(array & $values, string $key, FormRequest $request) {

        $tmp = explode(":",$key);
        $fileName = $tmp[0];
        $filePath = $tmp[1];

        $file = $request->file($fileName);
        if (!$file) {
            return;
        }

        $filename = Transliteration::make(
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ['type' => 'filename', 'lowercase' => true]);

        $filename .= '.' . $file->getClientOriginalExtension();

        $file->move($filePath, $filename);

        $values[$fileName] = $filename;
    }
}
