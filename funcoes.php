<?php
inicializa();
protegeArquivo(basename(__FILE__));

function inicializa(){
    if(file_exists(dirname(__FILE__).'/config.php')):
        require_once(dirname(__FILE__).'/config.php');
    else:
        die(utf8_decode('O arquivo de configuração não foi localizado, contate o administrador.'));
    endif;
    $constantes = array('BASEPATH', 'BASEURL', 'ADMURL', 'CLASSESPATH', 'MODULOSPATH', 'CSSPATH', 'JSPATH', 'DBHOST', 'DBUSER', 'DBPASS', 'DBNAME');
    foreach($constantes as $valor):
        if(!defined($valor)):
            die(utf8_decode('Faltam configurações básicas do sistema, contate o administrador.'));
        endif;           
    endforeach;
    require_once(BASEPATH.CLASSESPATH.'autoload.php');
    
    if(isset($_GET['logoff'])):
        if($_GET['logoff'] == true):
            //faz o logoff do sistema
            $user = new usuarios();
            $user->doLogout();
            exit;
        endif;
    endif;
           
}//inicializa()

function loadCSS($arquivo=null, $media='screen', $import=false){
    if($arquivo!=null):
        if($import==true):
            echo '<style type="text/css">@import url("'.BASEURL.CSSPATH.$arquivo.'.css");</style>'."\n";
        else:
            echo '<link rel="stylesheet" type="text/css" href="'.BASEURL.CSSPATH.$arquivo.'.css" media="'.$media.'">'."\n";
        endif;
    endif;
}//loadCSS()


function loadJS($arquivo=null, $remoto=false){
    if($arquivo != null):
        if($remoto==false) $arquivo = BASEURL.JSPATH.$arquivo.'.js';
        echo '<script type="text/javascript" src="'.$arquivo.'"></script>'."\n";
    endif;
}//loadJS


function loadModulo($modulo=null, $tela=null){
    if($modulo == null || $tela == null):
        echo '<p>Erro na função <strong>'. __FUNCTION__ . '</strong>: faltam parâmetros para execução.</p>';
    else:
        if(file_exists(MODULOSPATH."$modulo.php")): 
            include_once(MODULOSPATH."$modulo.php");
        else:
            echo 'Módulo inexistente neste sistema';
        endif;
    endif;
            
}//loadModulo()

function protegeArquivo($nomeArquivo, $redirPara='index.php?erro=3'){
    $url = $_SERVER["PHP_SELF"];
    if(preg_match("/$nomeArquivo/i", $url)):
        //redireciona para outra url
        redireciona($redirPara);
    endif;
}//protegeArquivo()

function redireciona($url=''){
    header("Location: ". BASEURL.$url);
}//redireciona()

function codificaSenha($senha){
    return md5($senha);
}//codificaSenha()

function verificaLogin(){
    $sessao = new sessao();
    if ($sessao->getNvars()<=0 || $sessao->getVar('logado')!=TRUE || $sessao->getVar('ip')!=$_SERVER['REMOTE_ADDR']):
        redireciona('?erro=3');
    endif;
}//verificaLogin()

function printMSG($msg=null, $tipo=null){
    if($msg!=null):
        switch($tipo):
            case 'erro':
                echo '<div class="erro">'. $msg .'</div>';
            break;
            case 'alerta':
                echo '<div class="alerta">'. $msg .'</div>';
            break;
            case 'pergunta':
                echo '<div class="pergunta">'. $msg .'</div>';
            break;
            case 'sucesso':
                echo '<div class="sucesso">'. $msg .'</div>';
            break;
            default:
                echo '<div class="sucesso">'. $msg .'</div>';
            break;
        endswitch;
    endif;

}//printMSG()

function isAdmin(){
    verificaLogin();
    $sessao = new sessao();
    $user = new usuarios(array('administrador' => NULL,));
    $iduser = $sessao->getVar('iduser');
    $user->extras_select = "WHERE id=$iduser";
    $user->selecionaCampos($user);
    $res = $user->retornaDados();
    if(strtolower($res->administrador)=='s'):
        return TRUE;
    else:
        return FALSE;
    endif;
}//isAdmin()

function antiInject($string){
    // remove palavras que contenham sintaxe sql
    $string = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i","",$string);
    $string = trim($string);//limpa espacos vazios
    $string = strip_tags($string);//tira tags html e php
    if(!get_magic_quotes_gpc())
    $string = addslashes($string);//Adiciona barras invertidas a uma string
    return $string;
}//antiInject()
?>