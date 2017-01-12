<?php

class ExeptionFileNotFound extends Exception { }

class ParseCSV
{
    public static function  parse($filename){

        if(!file_exists($filename)){
            throw new ExeptionFileNotFound("Not found file: ".$filename);
        }

        $contentList = file($filename);

        $contentList = array_map(function($item){
            return explode(',', $item);
        }, $contentList);

        return $contentList;

    }
}