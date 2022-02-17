<?php
namespace Lib\Abstracts;


use Lib\Exceptions\EntityException;
use Lib\Manager\ExceptionsManager;

abstract class AbstractEntite implements \JsonSerializable {

    public function keyFK(){

        $ret=null;
        $attrib = get_class_vars(get_class($this));
        foreach($attrib as $key=>$val)
        {
            if(strpos($key, 'id_'))
            {
                list($a, $r) =explode('_',$key);
                $ret[$r]=$key;
            }

        }
        return $ret;
    }
	public function hydrate(array $datas=NULL) 
	{
	    $attrib = get_class_vars(get_class($this));
	    foreach($attrib as $key=>$val)
	    {

            if(isset($datas[$key]))
            {
//                if(strpos($key, 'id_')===false) {
                    $mutateur = 'set' . $key;
                    $this->$mutateur($datas[$key]);
//                }
            }
	    }
	    return $this;
	}


	protected function set($attribut, $valeur)
	{
		$this->$attribut = $valeur;
		return $this;
	}

	protected function get($attribut)
	{
		return $this->$attribut;
	}

	public function __call($methode, $attribValeur)
	{
	    $attribName = NULL;

	    $prefix = substr($methode, 0, 3);
	    $suffix = substr($methode, 3);
	    $cattrs = count($attribValeur);

	    if(property_exists($this, $suffix))
		    $attribName = $suffix;
	    else if(property_exists($this, lcfirst($suffix)))	
		    $attribName = lcfirst($suffix);
	    if($attribName != NULL)
	    {
            if($prefix == 'set' && $cattrs == 1)
                return $this->set ($attribName, $attribValeur[0]);
            if($prefix == 'get' && $cattrs == 0)
                return $this->get($attribName);
	    }
	    else
	        ExceptionsManager::addException(new EntityException("La méthode $methode n'existe pas..."));
	}

	public function jsonSerialize()
	{
	    $array = array();
	    $attrib = get_class_vars(get_class($this));

	    foreach($attrib as $key=>$val)
	    {
		    $array[$key] = $this->get($key);
	    }
	    return $array;
	}

	public function __toString()
	{
	    $string = "";
	    $attrib = get_class_vars(get_class($this));

	    foreach($attrib as $key=>$val)
	    {
		    $string .= $this->get($key) .';';
	    }
	    return $string;
	}
	
	public function __toArray()
	{
	    return $this->jsonSerialize();
	}
}