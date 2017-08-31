<?php

namespace BadChoice\Reports\DataTransformers;

class ReportDataTransformer{

    public static function transform($row, $field, $value, $transformation, $transformData = null){
        $transformed = static::applyTransformation($row, $field, $value, $transformation, $transformData);
        return $transformed;
        //return static::applyLabel($transformed, $object, $field);
    }

    /*public static function applyLabel($transformed,$object,$field){
        if( isset($field["label"]) ){
            $labelClass = object_get($object, $field['label']);
            return "<span class='label$labelClass'> $transformed </span>";
        }
        return $transformed;
    }*/

    public static function applyTransformation($row, $field, $value, $transformation, $transformData){
        $transformer = static::getTransformer( ucFirst($transformation) );

        if( ! class_exists($transformer) ) {
            return $value;
        }

        if ( static::doesImplement("TransformsRowInterface", $transformer) ){
            return (new $transformer)->transformRow($field, $row, $value, $transformData);
        }

        if ( static::doesImplement("TransformsValueInterface", $transformer) ){
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