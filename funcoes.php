<?php
inicializa();
function inicializa(){
    if(file_exists(dirname(__FILE__).'/config.php')):
        require_once(dirname(__FILE__).'/config.php');
    else:
        die(utf8_decode('O arquivo de configuração não foi localizado, contate o administrador.'));
    endif;
    if(!defined("BASEPATH") || !defined("BASEURL")):
        die(utf8_decode('Faltam configurações básicas do sistema, contate o administrador.'));
    endif;
    require_once(BASEPATH.CLASSESPATH.'autoload.php');
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


?>