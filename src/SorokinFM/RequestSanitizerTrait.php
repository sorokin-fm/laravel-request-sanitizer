<?php

namespace SorokinFM;


/**
 * Class RequestSanitizerTrait
 *
 * Additional sanitizer for input values
 */
trait RequestSanitizerTrait
{
    /**
     * Retrieve an input item from the request.
     *
     * @param  string|null  $key
     * @param  string|array|null  $default
     * @return string|array|null
     */
    public function input($key = null, $default = null)
    {
        $values = parent::input($key, $default);

        foreach( static::SANITIZE_RULES ?? [] as $fieldName => $ruleName ) {
            $sanitizer = SanitizerFactory::create($ruleName);
            $sanitizer->sanitize($values,$fieldName, $this);
        }

        return $this->processNulls($values);
    }


    /**
     * @param array $values
     *
     * @return array
     */
    private function processNulls(array $values){
        foreach( $values as $key => $value ) {
            if( $value === 'null') {
                $values[$key] = null;
            }
        }
        return $values;
    }


}
