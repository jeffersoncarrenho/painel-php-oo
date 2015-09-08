<?php 
require_once('header.php');
if(isset($_GET['m'])) $modulo = $_GET['m'];
if(isset($_GET['t'])) $tela = $_GET['t'];
?>

<div id="content">
    <?php
        if($modulo && $tela):
            loadmodulo($modulo, $tela);
        else:
            echo '<p>Escolha uma opção de menu ao lado.</p>';
        endif;
    ?>
</div><!-- content -->

<?php require_once('sidebar.php');?>
<?php require_once('footer.php');?>