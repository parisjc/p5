<?php
namespace Lib\BDD;

use Lib\Manager\ExceptionsManager;

class BDDBuilder
{
    private string $table;

    private array $rows = [];
    private array $values = [];
    private string $type;

    private string $idField;
    private int $id;

    public function __construct($type)
    {
        if(isset($type) && gettype($type) == "string"){
            $this->type = $type;
        }else{
            ExceptionsManager::addException(new BDDBuilder('Type manquant ou incorrect pour le BDDBuilder'));
        }
    }

    public function addRow()
    {
        $row = new BDDBuilderRow();
        $this->rows[] = $row;
        return $row;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function addValue($index, $value){
        $this->values[$index] = $value;
    }

    public function setId($field, $id){
        $this->idField = $field;
        $this->id = $id;
        return $this;
    }

    public function getInsertQuery(){
        $query = "";
        $val = "";
        $basic = "INSERT INTO ".$this->table;
        $ind = join(',', $this->rows[0]->getIndex());
        foreach ($this->rows as $row) {
            $val = '';
            if($query != ""){
                $query .= ", ";
            }
            foreach ($row->getValues() as $insert) {
                if($insert == null || $insert == ""){
                    $val .= "null,";
                }elseif (gettype($insert) == "string") {
                    $val .= "'$insert',";
                } else {
                    $val .= "$insert,";
                }
            }
            $val = substr($val, 0, -1);
            $query .= "($val)";
        }
        return "$basic ($ind) VALUES $query;";
    }

    public function getUpdateQuery(){
        $sql = "";
        $basic = "UPDATE ".$this->table." SET";
        $where = "WHERE ".$this->idField."=".$this->id;
        foreach($this->values as $index => $insert){
            $sql .= "$index=";
            if($insert == null || $insert == ""){
                $sql .= "null,";
            }else if (gettype($insert) == "string") {
                $sql .= "'$insert', ";
            } else {
                $sql .= "$insert, ";
            }
        }
        $sql = substr($sql, 0, -2);
        return "$basic $sql $where;";
    }

    public function getQuery(){
        return $this->type=="update"?$this->getUpdateQuery():$this->getInsertQuery();
    }

    public function exec(){
        return BDD::query($this->getQuery());
    }
}