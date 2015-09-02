<?php
require_once("base.class.php");
    
    class Clientes extends Base{
        public function __construct($campos = array()){
            parent::__construct();
            $this->tabela = "clientes";
            if(sizeof($campos)<=0):
                $this->campos_valores = array(
                                        "nome" => null,
                                        "sobrenome" => null
                );
            else:
                $this->campos_valores = $campos;
            endif;
            $this->campo_pk = "id";
        }//fim construct
    }//fim classe Clientes
?>