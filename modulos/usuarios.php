<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));


switch($tela):
case 'login':
    ?>
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