<?php
require_once(dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));
    
    class usuarios extends Base{
        public function __construct($campos = array()){
            parent::__construct();
            $this->tabela = "paineladm_usuarios";
            if(sizeof($campos)<=0):
                $this->campos_valores = array(
                                        "nome" => null,
                                        "email" => null,
                                        "login" => null,
                                        "ativo" => null,
                                        "administrador" => null,
                                        "datacad" => null
                );
            else:
                $this->campos_valores = $campos;
            endif;
            $this->campo_pk = "id";            
        }//fim construct
        
        public function doLogin($objeto){
            $objeto->extras_select = "WHERE login='".$objeto->getValor('login')."' AND senha ='".codificaSenha($objeto->getValor('senha'))."' AND ativo='s'";
            $this->selecionaTudo($objeto);
            
            $sessao = new sessao();
                        
            if($this->linhasafetadas==1):
                $usLogado = $objeto->retornaDados();
                $sessao->setVar('iduser', $usLogado->id);
                $sessao->setVar('nomeuser', $usLogado->nome);
                $sessao->setVar('loginuser', $usLogado->login);
                $sessao->setVar('logado', TRUE);
                $sessao->setVar('ip', $_SERVER['REMOTE_ADDR']);
                return TRUE;
            else:
                $sessao->destroy(TRUE);
                return FALSE;
            endif;
        }//doLogin()
        
        public function doLogout(){
            $sessao = new sessao();
            $sessao->destroy(TRUE);
            redireciona('?erro=1');
        }//doLogout()
    
    }//fim classe usuarios
?>