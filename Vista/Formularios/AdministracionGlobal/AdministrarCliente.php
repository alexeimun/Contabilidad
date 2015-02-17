<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Clientes.php';
    include '../../../Clases/cls_Empresas.php';
    session_start();
    if (isset($_SESSION['login']) != '' && $_SESSION['login'][0]["NIVEL"] == 2) {

        if ($_GET['id'] == "") {
            echo '<script > self.location = "Vendedores.php" </script>';
        }

        $Master = new Master();
        $menu = $Master->Menu();
        $Cliente = new cls_Clientes();
        $Empresas = new cls_Empresas();

        $txtNombre = '';
        $txtDoc = '';
        $txtTelefono = '';
        $txtEmail = '';
        $Logo = '';
        $Estado = '';
        $CantidadEmpresas = '';

        foreach ($Cliente->TraeInfoCliente($_GET['id']) as $llave => $valor) {

            $txtNombre = $valor['NOMBRE'];
            $txtDoc = $valor['DOCUMENTO'];
            $txtEmail = $valor['EMAIL'];
            $txtTelefono = $valor['TELEFONO'];
            $Logo = $valor['LOGO'];
            $Estado = $valor['ESTADO'];
            $CantidadEmpresas = $valor['CANT_EMPRESAS'];
        }

        if ($Estado == 1) {
            $a = "'d'";
            $Boton = ' <input type="button" class="btnRojo" onclick="ActivaOdesactiva(' . $a . ',' . $_GET['id'] . ')" id="btnDesactivar" name="btnDesactivar" value="DESACTIVAR"  style="width:200px;"/>';
        } else {
            $a = "'a'";
            $Boton = ' <input type="button" class="btnVerde" onclick="ActivaOdesactiva(' . $a . ',' . $_GET['id'] . ')"  id="btnActivar" name="btnActivar" value="ACTIVAR"  style="width:200px;"/>';
        }
        if (isset($_POST['btnGuardar']) != '' && isset($_POST['cantidad']) != '') {
            $Cliente->ActualizarCantEmpresas($_POST['cantidad'], $_GET['id']);

            foreach ($Cliente->TraeInfoCliente($_GET['id']) as $llave => $valor)

                $CantidadEmpresas = $valor['CANT_EMPRESAS'];
        }

        $tablaEmpresas = '<table id="table" class="table" style="width:60%;">
           	<thead>
					<tr>
<th style="text-align:left;">EMPRESA</th>
            <th style="text-align:left;">NIT</th>
            <th style="text-align:left;">E-MAIL</th>
            <th style="text-align:left;">FECHA DE REGISTRO</th></tr></thead><tbody>';

        $cont = 0;
        foreach ($Cliente->TraeUsuariosCliente($_GET['id']) as $llave => $valor) {
            $idEmpresa = $valor['ID_EMPRESA'];
            $tablaEmpresas .= '<tr><td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
            $tablaEmpresas .= '<td style="text-align:left;">' . $valor['NIT'] . '</td>';
            $tablaEmpresas .= '<td style="text-align:left;">' . $valor['EMAIL'] . '</td>';
            $tablaEmpresas .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td></tr>';
            $cont ++;
        }

        $tablaEmpresas .= '</tbody></table>';

    } else echo '<script> self.location = "/" </script>';

?>
<html>
<head>
    <title>Administrar Cliente</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>


</head>
<style type="text/css">
    select {
        width: 300px;
    }

    input[type='text'] {
        width: 287px;
    }

    input[type='password'] {
        width: 287px;
    }

    input[type='email'] {
        width: 287px;
    }

    .spana {
        font: bolder 17px 'Trebuchet MS', Arial, Sans-serif;
        color: #093C67;
    }

    .spanb {
        font: bolder 15px 'Trebuchet MS', Arial, Sans-serif;
        color: #000;
    }

</style>

<script>
    function ActivaOdesactiva(aa, id) {

        if (aa == 'a') {
            if (confirm("Seguro que quieres activar esta cuenta ?")) {
                window.location.href = 'ActivarOdesactivar.php?id=' + id + '&a=' + aa;
            }
        }
        else {
            if (confirm("Seguro que quieres desactivar esta cuenta ?")) {
                window.location.href = 'ActivarOdesactivar.php?id=' + id + '&a=' + aa;
            }
        }

    }
</script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="../../Imagenes/logo.png"></a>

        <h1 id="logo"><span class="gray">Administración</span></h1>
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <form method="post" enctype="multipart/form-data">
                <center>
                    <h3><b>ADMINISTRACIÓN DE CLIENTE</b></h3><br>

                    <span class="spanb">Vendedor:</span><span class="spana"><?= $txtNombre; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">Documento:</span><span class="spana"><?= $txtDoc; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">Telefono:</span><span class="spana"><?= $txtTelefono; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">E-Mail:</span><span class="spana"><?= $txtEmail; ?></span><br><br><br>
                    <img style="width: 140px;" src="../../Formularios/Vendedores/<?= $Logo; ?>"><br>

                    <h4><b>Capacidad de Empresas</b></h4>
                    <input type="number" id="num" name="cantidad" value='<?= $CantidadEmpresas; ?>'
                           style="text-align: center;width:60px;" max="99999" min=10/>
                    <input type="submit" value="Guardar" class="btnAzul" style="width: 95px;" name="btnGuardar"
                           onclick="Guardar();"
                           style="height: 30px;border-radius: 3px;cursor: pointer; width: 60px;"/>
                    </br>

                    <h4><b>Empresas</b></h4>
                    <?= $tablaEmpresas; ?>
                    <br>
                    <?= $Boton; ?>
                </center>

            </form>


        </div>
    </div>

</div>

</body>
</html>
