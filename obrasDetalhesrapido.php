<?php session_start(); ?>

<?php 
// Arquivo com as novas funções
//require_once "../../system/functions.php"; 

require_once('../../system/linkController.php');
$_SESSION['id'] = $_GET['id'];

require_once(''.$reqPuxaController.'');
$puxa = new Puxar();

error_reporting(0);

?>

<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Detalhes Obras</title>

        <?php
        //verifica qual o tipo de usuário que está logado 
        if($_SESSION['loginGerenciador'] == 0  && $_SESSION["loginPortaria"] == 0 && $_SESSION["loginFornecedor"] == 0){
            if($_SESSION['loginObra'] == 1){
            ?>
                <script type="text/javascript">
                    window.location = "../../obra/view/obras.php";
                </script>
            <?php } else { ?>
                <script type="text/javascript">
                    window.location = "../../login/view/loginView.php";
                </script>
                <?php 
            }
        }
        ?>

        <!-- css -->
        <link rel="stylesheet" href="<?php echo $cssLinkUi; ?>" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo $cssEstilo; ?>" />

        <!-- js --> 
        <script type="text/javascript" src="<?php echo $jLinkJquery; ?>"></script>
        <script type="text/javascript" src="<?php echo $jLinkMascara; ?>"></script>
        <script type="text/javascript" src="<?php echo $jLinkControla; ?>"></script>
        <script type="text/javascript" src="<?php echo $jLinkJqueryUi; ?>"></script>

        <!-- Formee -->
        <script type="text/javascript" src="<?php echo $jLinkformee; ?>"></script>
        <link rel="stylesheet" href="<?php echo $cssLinkFormeeEstrutura; ?>" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo $cssLinkFormeeEstilo; ?>" type="text/css" media="screen" />

        <!-- Table Sorter -->
        <link rel="stylesheet" href="../../css/blue/style.css">
        <script src="../../js/jquery.tablesorter.min.js"></script>
        <script src="../../js/scripts.js"></script>


    </head>

    <body>

        <!-- MENU -->
        <?php include('../../system/menuController.php'); ?>
        <!-- MENU -->
            
        <div id="main">   
            <div class="container_12"> 
            
                <div class="grid_12">  
                        
                    <div class="title">
                        <h3>Relatórios</h3>
                    </div><!-- .title -->

                    <div class="user">
                        <h3>
                            <?php

                            if($_SESSION['loginFornecedor'] == 1)
                                echo "Fornecedor"; 
                            elseif($_SESSION['loginGerenciador'] == 1)
                                echo "Gerenciador";
                            elseif($_SESSION['loginPortaria'] == 1)
                                echo "Portaria"            

                            ?>
                        </h3>
                    </div><!-- .user -->

                    <div class="formee fundo-chapado">
                    
                            <?php
                            $tudo = $puxa->puxaObraSelecionada();

                            foreach ($tudo as $o => $dadoObra) {
                            ?>
                            
                                <div class="title_form" style="margin-bottom: 10px;">
                                    <h2><?php echo $dadoObra[2]; ?></h2>
                                </div>
                            
                            <?php   
                            ?>

                            <div id="posicaoBtIniciar"><a href="obras.php" id="btVoltar" class="right"><label id="FormPocket" class="tituloDetalhesObras">Voltar</label></a></div>

                                <div class="d-tabela">
                                    <table>
                                        <tr>
                                            <td>
                                                <table class="titleTable">
                                                    <tr>
                                                        <td>
                                                            <label>Fornecedores:
                                                            <a href="../../relatorios/view/fornecedores.php?obra=<?php echo $_SESSION['id'];?>" alt="Histórico" title="Histórico">
                                                                <img src="../../images/icon-schedule-verde.png" class="icon-schedule-verde">
                                                            </a></label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                                                                
                                        <tr>
                                            <td>
                                                <table class="customTable sub">
                                                    <thead>
                                                        <tr>
                                                            <th><span>Nome</span></th>
                                                            <th class="nowrap" align="center"><span>Em obra</span></th>
                                                            <th class="nowrap" align="center"><span>No dia</span></th>
                                                        </tr>
                                                    </thead>
                                                  
                                                    <tbody>
                                                        <?php 
                                                        $pdo = conecta();

                                                        $qt_fornecedores = $pdo->query("SELECT nm_fantasia_fornecedor, 
                                                            COUNT(qd) as qt_online,
                                                            COUNT(qo) as qt_dia
                                                            FROM (
                                                                    SELECT fo.nm_fantasia_fornecedor, e2.cd_entrada as qo ,e.cd_entrada as qd
                                                                    FROM funcionario f
                                                                    INNER JOIN entradas_obras e2 ON (e2.cd_funcionario = f.cd_funcionario) AND DATE_FORMAT(e2.dt_horario, '%Y-%m-%d') = '2014-12-10' AND e2.cd_obra = 27
                                                                    INNER JOIN fornecedor fo ON (f.cd_fornecedor = fo.cd_fornecedor)
                                                            LEFT JOIN entradas_obras e ON (e.cd_entrada = e2.cd_entrada) AND e.id_tipo_entrada = 1
                                                            GROUP BY f.cd_funcionario 
                                                            ORDER BY fo.nm_fantasia_fornecedor) a GROUP BY a.nm_fantasia_fornecedor;");
                                                        
                                                        while ($qt_fornecedor = $qt_fornecedores->fetch(PDO::FETCH_OBJ)) {
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <span>
                                                                        <?php echo $qt_fornecedor->nm_fantasia_fornecedor; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span>
                                                                        <?php echo $qt_fornecedor->qt_online; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span><?php echo $qt_fornecedor->qt_dia; ?></span>
                                                                </td>
                                                            </tr>

                                                        <?php 
                                                        }
                                                        ?>

                                                    </tbody>

                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="d-tabela">
                                    <table>
                                        <tr>
                                            <td>
                                                <table class="titleTable">
                                                    <tr>
                                                        <td>
                                                            <label>Cargos:
                                                            <a href="../../relatorios/view/cargos.php?obra=<?php echo $_SESSION['id'];?>" alt="Histórico" title="Histórico">
                                                                <img src="../../images/icon-schedule-verde.png" class="icon-schedule-verde">
                                                            </a></label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <table class="customTable sub">
                                                    <thead>
                                                        <tr>
                                                            <th><span>Nome</span></th>
                                                            <th align="center" class="nowrap"><span>EM OBRA</span></th>
                                                            <th align="center" class="nowrap"><span>NO DIA</span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $qt_cargos = $pdo->query("SELECT nm_cargo, 
                                                        COUNT(qd) as qt_online,
                                                        COUNT(qo) as qt_dia
                                                        FROM (
                                                            SELECT c.nm_cargo, e2.cd_entrada as qo ,e.cd_entrada as qd
                                                                        FROM funcionario f
                                                                        INNER JOIN entradas_obras e2 ON (e2.cd_funcionario = f.cd_funcionario) AND DATE_FORMAT(e2.dt_horario, '%Y-%m-%d') = '2014-12-10' AND e2.cd_obra = 27
                                                                                        INNER JOIN fornecedor fo ON (f.cd_fornecedor = fo.cd_fornecedor)
                                                                                        INNER JOIN cargo c ON (f.cd_cargo = c.cd_cargo)
                                                                LEFT JOIN entradas_obras e ON (e.cd_entrada = e2.cd_entrada) AND e.id_tipo_entrada = 1
                                                                GROUP BY f.cd_funcionario 
                                                                ORDER BY c.nm_cargo 
                                                        ) 
                                                        a GROUP BY a.nm_cargo;");
                                                            
                                                        while($qt_cargo = $qt_cargos->fetch(PDO::FETCH_OBJ)) {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <span>
                                                                        <?php echo $qt_cargo->nm_cargo; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span>
                                                                        <?php echo $qt_cargo->qt_online; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span>
                                                                        <?php echo $qt_cargo->qt_dia; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                        }
                                                        ?>


                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                    </table>
                                </div>


                                <table class="table-big">
                                    <tr>
                                        <td>
                                            <table class="titleTable big tableTituloProfissionais">
                                                <tr>
                                                    <td>
                                                        <label>Profissionais</label>
                                                        
                                                        <label id="lbl-relatorios">Relatórios:</label>
                                                        
                                                        <div class="icons-relatorios">
                                                            <a href="../../relatorios/view/funcionarios_consolidado.php?obra=<?php echo $_SESSION['id']; ?>" alt="Relatório consolidado" title="Relatório consolidado">
                                                                <img src="../../images/icon-consolidado.png">
                                                            </a>

                                                            <a href="../../relatorios/view/detalhado_funcionarios.php?obra=<?php echo $_SESSION['id']; ?>" alt="Relatório detalhado" title="Relatório detalhado">
                                                                <img src="../../images/icon-detalhado.png" >
                                                            </a>

                                                            <a href="../../relatorios/view/cartaodeponto.php?obra=<?php echo $_SESSION['id']; ?>" alt="Folha de ponto" title="Folha de ponto" class="i-cartaoponto">
                                                                <img src="../../images/icon-folha-de-ponto.png">
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <table class="legendaTable" align="center">
                                                <tr>
                                                    <td><img src="../../images/led-on-on.png" /></td>
                                                    <td>Em obra</td>
                                                    <td><img src="../../images/led-off-on.png" /></td>
                                                    <td>Fora da obra</td>
                                                </tr>
                                                <tr>
                                                    
                                                </tr>
                                            </table>   
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <table class="customTable tablesorter tablesorterdefault">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 125px;"><span>Status:</span></th>
                                                        <th><span>Nome:</span></th>
                                                        <th><span>Cargo:</span></th>
                                                        <th><span>Empresa:</span></th>
                                                    </tr>
                                                </thead>
                                                          
                                                <tbody>                                 
                                                    <?php
                                                    $entradas = entradasFuncionariosPorObra();

                                                    while($entrada = $entradas->fetch(PDO::FETCH_OBJ)) {
                                                    ?>
                                            
                                                    <tr id="FormPocket" class="abreModal" data-id="<?php echo $entrada->cd_funcionario; ?>">
                                                        <td align="center">
                                                            <?php  
                                                            $pdo = conecta();
                                                            $dados = $pdo->query("SELECT * 
                                                            FROM entradas_obras 
                                                            WHERE cd_funcionario = {$entrada->cd_funcionario} AND cd_obra = {$_GET['id']} 
                                                            ORDER BY dt_horario DESC
                                                            LIMIT 1");

                                                            $dado = $dados->fetch( PDO::FETCH_OBJ );

                                                            if($dado->id_tipo_entrada == 1) {
                                                            ?>
                                                                <span class="hide">online</span>
                                                                <div class="status">
                                                                    <div class="estadoLigadoOn"></div>
                                                                </div><!-- .status2 -->
                                                            <?php           
                                                            }
                                                            else {
                                                            ?>
                                                                <span class="hide">offline</span>
                                                                <div class="status2">
                                                                    <div class="estadoDesligadoOn"></div>
                                                                </div><!-- .status -->
                                                            <?php 
                                                            }
                                                            ?>
                                                            
                                                        </td>
                                                        <td>
                                                            <span>
                                                                <?php echo $entrada->nm_funcionario; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span>
                                                                <?php echo utf8_encode($entrada->nm_cargo); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span>
                                                                <?php echo $entrada->nm_fantasia_fornecedor; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    
                                                    <?php 
                                                    } 
                                                } 
                                                ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                        <script type="text/javascript"> 

                        //abre o modal com o relatório individual
                        $(".abreModal").click(function(){
                            $(".dados").html("");

                            var idFuncionario = $(this).attr("data-id");

                            $(function() {
                                $("#dadosFun").dialog({
                                    autoOpen: false,
                                    show: {
                                        effect: "blind",
                                        duration: 500
                                    },
                                        hide: {
                                        effect: "explode",
                                        duration: 1000
                                    }, 
                                    width: 750,
                                    height: 500
                                });
                                $("#dadosFun").dialog("open");
                                
                            });

                            //busca os dados por ajax 
                            $.ajax({ 
                                type: "POST", 
                                url: "../../relatorio/index.php", 
                                data: {
                                    cdFun: idFuncionario,
                                    obra: <?php echo $_GET['id'] ?>
                                }, 
                                success: function(retorno) {
                                    $(".dados").html(retorno);
                                } 
                            });

                        });
                       </script>

                    </div>
                     
                </div><!-- .grid_12 -->               
                       
            </div><!-- .container_12 -->
        </div><!-- #main -->


        <div id="footer">
            <div class="container_12"> 
            
                <div class="grid_12">
                    <p>produzido por: <a href="http://www.marcasite.com.br" target="_blank"> marcasite!</a></p>
                </div><!-- .grid_12 -->
            
            </div><!-- .container_12 -->    
        </div><!-- #footer -->
        <div id="dadosFun" class="dados" title="Informações"></div>

        <div class="fundo-opacidade"></div>

    </body>
</html>