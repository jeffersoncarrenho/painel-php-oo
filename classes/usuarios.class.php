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
        
        public function existeRegistro($campo=null,$valor=null){
            if($campo!=null && $valor!=null):
                is_numeric($valor)? $valor = $valor: $valor ="'".$valor ."'";
                $this->extras_select = " WHERE $campo=$valor";
                $this->selecionaTudo($this);
                if($this->linhasafetadas>0):
                    return TRUE;
                else:
                    return FALSE;
                endif;
            else:
                $this->trataerro(__FILE__,__FUNCTION__,NULL, 'Faltam parâmetros para executar a função', true);
            endif;
        }//existeRegistro()
    }//fim classe usuarios
?>