<?php


namespace Lib\BDD;


class BDDBuilderRow
{
    private array $values = [];

    public function addValue($index, $value){
        $this->values[$index] = $value;
        return $this;
    }

    public function removeValue($index){
        unset($this->values[$index]);
        return $this;
    }

    public function getValues(){
        return $this->values;
    }

    public function getIndex(){
        return array_keys($this->values);
    }
}