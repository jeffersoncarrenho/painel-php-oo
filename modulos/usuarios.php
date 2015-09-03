<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));
loadJS('jquery-validate');
loadJS('jquery-validate-messages');

switch($tela):
case 'login':
    ?>
       
        <script type="text/javascript">
            $(document).ready(function(){
                $(".userform").validate({
                    rules:{
                        usuario:{required:true, minlength:3},
                        senha:{required:true, rangelength:[4,10]}
                    }
                });
            });
        </script>
       
       
       
        <div id="loginform">
            <form action="" method="post" class="userform">
                <fieldset>
                    <legend>Acesso Restrito, identifique-se</legend>
                    <ul>
                        <li>
                            <label for="usuario">Usuário</label>
                            <input type="text" size="35" name="usuario" value="<?php if(isset($_POST['usuario'])) echo $_POST['usuario'];?>">
                        </li>
                        <li>
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" size="35" value="<?php if(isset($_POST['senha'])) echo $_POST['senha'];?>">
                        </li>
                        <li class="center">
                            <input type="submit" name="logar" value="Login">
                        </li>
                    </ul>
                    <?php
                        if(isset($_GET['erro'])):
                            $erro = $_GET['erro'];
                            switch ($erro):
                                case 1:
                                    echo '<div class="sucesso">Você fez Logoff do sistema</div>';
                                break;                            
                                case 2:
                                    echo '<div class="erro">Dados incorretos ou usuário Inativo </div>';
                                break;                            
                                case 3:
                                    echo '<div class="erro">Faça login antes de acessar a página solicitada</div>';
                                break;              
                            endswitch;
                        endif;
                    ?>
                </fieldset>
            </form> 
        </div>
    <?php
break;
default:
    echo '<p>A tela solicitada não existe.</p>';
break;
endswitch;
?>