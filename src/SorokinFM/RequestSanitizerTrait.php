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
     * Retrieve a sanitized input from the request.
     *
     */
    public function sanitize()
    {
        $values = parent::input();
        $files = $this->files->keys();

        foreach( static::SANITIZE_RULES ?? [] as $fieldName => $rule ) {
            list($ruleName,$ruleParams) = explode(":",$rule . ":");

            $sanitizer = SanitizerFactory::create($ruleName);
            if( strpos($fieldName,'*' ) !== false ) {
                foreach( $values as $key => $value ) {
                    if( fnmatch($fieldName, $key) ) {
                        $sanitizer->sanitize($values, $key, $this, $ruleParams);
                    }
                }
                foreach( $files as $fileName ) {
                    if( fnmatch($fieldName, $fileName) ) {
                        $sanitizer->sanitize($values, $fileName, $this, $ruleParams);
                    }
                }
            } else {
                if( isset($files[$fieldName] ) ) {
                    $sanitizer->sanitize($values, $fieldName, $this, $ruleParams);
                } else {
                    $sanitizer->sanitize($values, $fieldName, $this, $ruleParams);
                }
            }
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
