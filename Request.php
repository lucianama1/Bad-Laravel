<?php

class Request{

    public function __construct(array $arr){
        foreach($arr as $k =>$v){ //k = key, v = value
            $this->$k = $v;
        }
    }
}