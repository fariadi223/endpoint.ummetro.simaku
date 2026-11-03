<?php

namespace App\Helpers;

class RequestFilterHelper {
    
    public static function fieldKey($allowKey = null, $params = null){

        if(empty($allowKey))
            return null;
            
        $params    = collect($params);
    
        $setToKey  = collect($allowKey)->keys();
        $recordKey = $params->only($setToKey);
        $valuesKey = [];
        foreach ($recordKey as $key => $value) {
            $valuesKey[] = [$allowKey[$key], '=', $value];
        }

        return $valuesKey;
    }
}