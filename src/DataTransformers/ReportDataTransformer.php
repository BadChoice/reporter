<?php

namespace BadChoice\Reports\DataTransformer;

class ReportDataTransformer{

    public static function transform($object, $field){
        $transformed = static::applyTransformation($object,$field);
        return static::applyLabel($transformed, $object, $field);
    }

    public static function applyLabel($transformed,$object,$field){
        if( isset($field["label"]) ){
            $labelClass = object_get($object, $field['label']);
            return "<span class='label$labelClass'> $transformed </span>";
        }
        return $transformed;
    }

    public static function applyTransformation($object, $field){
        $transformer = static::getTransformer( ucFirst($field['type']) );

        if( ! class_exists($transformer) ) {
            return object_get($object, $field['field']);
        }

        if ( static::doesImplement("TransformsRowInterface", $transformer) ){
            return (new $transformer)->transformRow($object, $field);
        }

        if ( static::doesImplement("TransformsValueInterface", $transformer) ){
            $value = object_get($object, $field['field']);
            return (new $transformer)->transform($value);
        }

        throw new \Exception("No valid transformer for this type");
    }

    private static function getTransformer($type){
        return __NAMESPACE__ ."\\Transformers\\" . ucFirst( $type );
    }

    private static function doesImplement($interface, $transformer){
        return  ( in_array( __NAMESPACE__ ."\\" . $interface, class_implements($transformer)) );
    }

}