<?php


namespace App\Traits;


trait StringParsing
{
    public function stringParsing($string): array
    {
        $strings = explode(' ',$string);
        $queryStrings = [];
        for($i = 0; $i<count($strings); $i++){
            if(!in_array($strings, $queryStrings)){
                $queryStrings[] = $strings[$i];
            }

            if(preg_match('/[0-9]$/',$strings[$i], $matches) != false){
                $part = $matches[0];
                $queryStrings[] = str_replace($part, ' '.$part,$strings[$i]);
            }

            if(isset($strings[$i + 1])){
                $queryStrings[] = $strings[$i].$strings[$i + 1];
                $queryStrings[] = $strings[$i].' '.$strings[$i + 1];
            }
        }

        return $queryStrings;
    }
}