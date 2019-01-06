<?php

namespace SorokinFM;

class SanitizerFactory {

    private static $_sanitizers = [];

    /**
     * @param string $name
     *
     * @return SanitizerInterface
     */
    public static function create(string $name): SanitizerInterface
    {
        if( isset(self::$_sanitizers[$name])) {
            return self::$_sanitizers[$name];
        }

        if( strpos($name,'\\') === false) {
            $className = "\\SorokinFM\\Sanitizers\\" . ucfirst($name) . "RequestSanitizer";
        } else if( class_exists($name)) {
            $className = $name;
        } else {
            throw new \UnexpectedValueException("Sanitizer class {$name} is not defined");
        }

        self::$_sanitizers[$name] = new $className;

        return self::$_sanitizers[$name];
    }
}
