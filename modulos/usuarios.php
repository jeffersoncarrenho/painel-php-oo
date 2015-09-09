<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));
loadJS('jquery-validate');
loadJS('jquery-validate-messages');

switch($tela):
case 'login':
    $sessao = new sessao();
    if($sessao->getNvars()>0 || $sessao->getVar('logado')==TRUE || $sessao->getVar('ip')==$_SERVER['REMOTE_ADDR']) redireciona('painel.php');


    if(isset($_POST['logar'])):
        $user = new usuarios();
        $user->setValor('login', $_POST['usuario']);
        $user->setValor('senha', $_POST['senha']);
        if($user->doLogin($user)):
            redireciona('painel.php');
        else:
            redireciona('?erro=2');
        endif;
    
    endif;
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

case 'incluir':
    echo '<h2>Cadastro de Usuários</h2>';
    if(isset($_POST['cadastrar'])):
        $user = new usuarios(array( 'nome' => $_POST['nome'],
                                    'email' => $_POST['email'],
                                    'login' => $_POST['login'],
                                    'senha' => codificaSenha($_POST['senha']),
                                    'administrador' => ($_POST['adm']=='on')?'s': 'n',
        ));
        
        if($user->existeRegistro('login', $_POST['login'])):
            printMSG('Este login já está cadastrado. Escolha outro nome de usuário', 'erro');
            $duplicado = true;
        endif;
        if($user->existeRegistro('email', $_POST['email'])):
            printMSG('Este email já está cadastrado. Escolha outro endereço', 'erro');
            $duplicado = true;
        endif;
        
        if($duplicado!=true):
            $user->inserir($user);
            if($user->linhasafetadas == 1):
                printMSG('Dados Inseridos com sucesso. <a href="'.ADMURL.'?m=usuarios&t=listar">Exibir Cadastros</a>');
                unset($_POST);
            endif;        
        endif;
    endif;
    ?>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $(".userform").validate({
                    rules:{
                        nome:{required:true, minlength:3},
                        email:{required:true, email:true},
                        login:{required:true, minlength:5},
                        senha:{required:true, rangelength:[4,10]},
                        senhaconf:{required:true, equalTo:'#senha'},
                        
                    }
                });
            });
        </script>                  
        <form action="" method="post" class="userform">
            <fieldset>
                <legend>Informe os dados para cadastro</legend>
                <ul>
                    <li>
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" size="50" value="<?php if(isset($_POST['nome'])) echo $_POST['nome'];?>" />
                    </li>
                    <li>
                        <label for="email">Email</label>
                        <input type="text" name="email" size="50" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" />
                    </li>
                    <li>
                        <label for="login">Login</label>
                        <input type="text" name="login" size="35" value="<?php if(isset($_POST['login'])) echo $_POST['login'];?>" />
                    </li>
                    <li>
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" size="25" id="senha" value="<?php if(isset($_POST['senha'])) echo $_POST['senha'];?>" />
                    </li>
                    <li>
                        <label for="senhaconf">Repita a Senha</label>
                        <input type="password" name="senhaconf" size="25" value="<?php if(isset($_POST['senhaconf'])) echo $_POST['senhaconf'];?>" />
                    </li>
                    <li>
                        <label for="adm">Administrador</label>
                        <input type="checkbox" name="adm" <?php if(!isAdmin()) echo 'disabled="dissabled"'; if(isset($_POST['adm'])) echo 'checked="checked"'; ?> /> Dar controle total ao usuário
                    </li>
                    <li class="center">
                        <input type="button" onClick="location.href='?m=usuarios&t=listar'" value="Cancelar" />
                        <input type="submit" name="cadastrar" value="Salvar Dados" />
                    </li>
                </ul>
            </fieldset>
        </form>
    
    <?php

break;
case 'listar':
    echo '<h2>Usuários cadastrados</h2>';
    loadCSS('data-tables', null, TRUE);
    loadJS('jquery-datatable');
    
    ?>
    
    <script>
        $(document).ready(function(){
            $("#listausers").dataTable({
                
                "language": {
                    "zeroRecords": "Nenhum dado encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "Sem registros disponíveis",
                    "infoFiltered": "(filtrado de _MAX_ registros no total)",
                    "search":"Pesquisar"
                },
                
                "sSrollY": "400px",
                "bPaginate": false,
                "aaSorting" : [[0, "asc"]]
            });
        });
    </script>
    
    <table class="display" id="listausers" cellpadding="0" border="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Login</th>
                <th>Ativo/Adm</th>
                <th>Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
                $user = new usuarios();
                $user->selecionaTudo($user);
                
                while($res=$user->retornaDados()):
                    echo '<tr>';
                        printf('<td>%s</td>', $res->nome);
                        printf('<td>%s</td>', $res->email);
                        printf('<td>%s</td>', $res->login);
                        printf('<td class="center">%s/%s</td>', strtoupper($res->ativo),strtoupper($res->administrador));
                        printf('<td class="center">%s</td>', date("d/m/Y", strtotime($res->datacad)));
                        printf('<td class="center"><a href="?m=usuarios&t=incluir" title="Novo Cadastro"><img src="images/add.png" alt="Novo Cadastro" /></a> <a href="?m=usuarios&t=editar?id=%s"><img src="images/edit.png" alt="Editar" /></a> <a href="?m=usuarios&t=senha?id=%s"><img src="images/pass.png" alt="Mudar Senha" /></a> <a href="?m=usuarios&t=excluir?id=%s"><img src="images/delete.png" alt="Excluir" /></a> </td>',$res->id,$res->id,$res->id);
                    echo '</tr>';
                endwhile;
            ?>
        </tbody>
    </table>
    
    
    
    <?php

break;

default:
    echo '<p>A tela solicitada não existe.</p>';
break;
endswitch;
?>