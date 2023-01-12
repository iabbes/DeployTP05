<?php
class Avion{

    public $id;
    public $airplane;
    public $type;
    public $price;

    public function setId($id){
        $this->id = $id;
    }
    public function setAirplane($airplane){
        $this->airplane = $airplane;
    }
    public function setType($type){
        $this->type = $type;
    }
    public function setPrice($price){
        $this->price = $price;
    }
}