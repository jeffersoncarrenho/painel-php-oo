<?php
require_once("banco.class.php");

abstract class Base extends Banco{
    //propriedades
    public $tabela = "";
    public $campos_valores = array();
    public $campo_pk = null;
    public $valor_pk = null;
    public $extras_select = "";
    
    //métodos
    public function addCampo($campo=null, $valor=null){
        if($campo != null):
            $this->campos_valores[$campo] = $valor;
        endif;
    }//addCampo
    
    public function delCampo($campo){
        if(array_key_exists($campo, $this->campos_valores)):
            unset($this->campos_valores[$campo]);
        endif;
    }//delCampo
    
    public function setValor($campo=null, $valor=null){
        if($campo != null && $valor != null):
            $this->campos_valores[$campo] = $valor;
        endif;
    }//SetValor
    
    public function getValor($campo=null){
        if($campo != null && array_key_exists($campo, $this->campos_valores)):
            return $this->campos_valores[$campo];
        else:
            return FALSE;
        endif;
    }//getValor
    
    
}//Fim Classe Base
?>