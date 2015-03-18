<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/Componentes.php';

    session_start();
    if (isset($_SESSION['login']) == ''  || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();
    $Contabilidad = new cls_Contabilidad();

    $Concepto = '<option value ="0" selected>-- Seleccione Un Concepto --</option>';
    $name = '';

    foreach ($Parametros->TraeConceptos($_SESSION['login'][0]["ID_EMPRESA"], 1) as $llave => $valor) {

        switch ($valor['CONCEPTO']) {
            case 2:
                $name = 'Inventario Inicial';
                break;
            case 3:
                $name = 'Inventario Final';
                break;
            case 4:
                $name = 'Compras';
                break;
            case 5:
                $name = 'Devoluciones Compras';
                break;
            case 6:
                $name = 'Descuentos Compras';
                break;
        }
        $Concepto .= '<option value="' . $valor['ID_CONCEPTO'] . '">' . $valor['CODIGO'] . ' - ' . $name . '</option>';
    }


    $Tercero = '<option value ="0" selected>-- Seleccione Un Tercero --</option>';

    foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $Tercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';

?>
<html>
<head>
    <title>Devoluciones compras</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <script src="../../Js/menu.js"></script>
    <?php include '../../Css/css.php' ?>
</head>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>
        <div id="main">
            <center>
                <h3><b>DEVOLUCIONES COMPRAS</b></h3><br>

                <input type="button" id="AgregarCampo" class="btnAzul" value="Agregar Campo" style="width:160px;">

                <form action="">
                    <div id="contenedor"></div>
                    <input type="hidden" name="devoluciones">
                </form>
        </div>
        <input type="submit" class="btnAzul" name="guardar" value="FINALIZAR"
               style="width:200px;margin-left:460px;margin-top: 20px;"/>
        </center>
        <br><br>

        <div class="Total"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

            var MaxInputs = 1000; //Número Maximo de Campos
            var contenedor = $("#contenedor"); //ID del contenedor
            var AddButton = $("#AgregarCampo"); //ID del Botón Agregar

            //var x = número de campos existentes en el contenedor
            var x = $("#contenedor").find("div").length;
            var FieldCount = x - 1; //para el seguimiento de los campos


            //Campo Inicial
            {
                FieldCount++;
                //agregar campo
                $(contenedor).append
                ('<div>'

                    //# Filas
                + ' <div><div  id="fila" class="btnAzul" style="height: 13px;width: 24px;padding: 6px;">1</div></div>'

                + '<div> <input  type="date"  style="padding-bottom:5px;" name="Fecha[]"  value="<?= date("Y").'-'.date("m").'-'.date("d") ?>" required> </div>'

                + '<div> <select   id="cmbConcepto" name="cmbConcepto[]"  class="chosen-select" style="width:200px;" >'
                + '<?=$Concepto ?>'
                + '</select></div>'


                + '<div> <input type="text" name="Valor[]" style="width: 100px;" placeholder="Valor" class="Valor" onkeypress="return validarNro(event)" required/></div>'

                + ' <div><input type="button" class="eliminar btnAzul"  title="Eliminar campo" value="X"'
                + ' style="width:40px;" style="width: 30px;" >'
                + '</div></div>');
                x++; //text box increment
            }

            $(AddButton).click(function () {
                if (x <= MaxInputs) //max input box allowed
                {
                    FieldCount++;
                    //agregar campo
                    $(contenedor).append
                    ('<div>'
                    + ' <div><div id="fila"  class="btnAzul" style="height: 13px;width: 24px;padding: 6px;">' + (x + 1) + '</div></div>'
                    + '<div> <input  type="date"  style="padding-bottom:5px;" name="Fecha[]"  value="<?= date("Y").'-'.date("m").'-'.date("d") ?>" required> </div>'

                    + '<div> <select  id="cmbConcepto"  name="cmbConcepto[]"  class="chosen-select" style="width:200px;" >'
                    + '<?=$Concepto ?>'
                    + '</select></div>'

                    + '<div> <input type="text" name="Valor[]" class="Valor" style="width: 100px;" placeholder="Valor"  onkeypress="return validarNro(event)" required/></div>'

                    + ' <div><input type="button" class="eliminar btnAzul"  title="Eliminar campo" value="X"'
                    + ' style="width:40px;" style="width: 30px;" >'
                    + '</div></div>');
                    x++; //text box increment
                }

                var config = {
                    '.chosen-select': {},
                    '.chosen-select-deselect': {allow_single_deselect: true},
                    '.chosen-select-no-single': {disable_search_threshold: 10},
                    '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                    '.chosen-select-width': {width: "95%"}
                };
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }
            });

            $("body").on("click", ".eliminar", function () { //click en eliminar campo
                if (x > 1) {
                    $(this).parent('div').parent().remove(); //eliminar el campo

                    $('#contenedor div div #fila').each(function (index, element) {
                        $(element).text(index + 1);
                    });
                    x--;
                }
            });

            $('input[name=guardar]').click(function () {
                if (Evaluar()) {
                    $.ajax({
                        url: 'Actions.php', type: 'post', data: $('form').serialize(),
                        success: function (data) {alert(data);},
                        error: function () {alert('Ha ocurrido un error en el sistema');}
                    });
                }
            });

            function Evaluar() {
                var erValor = false, erConcepto = false;
                var errorIndex = 0;

                $('#contenedor > div').each(function (index, element) {
                    //Validaciones
                    if ($(element).find('select#cmbConcepto').val() == 0) {
                        erConcepto = true;
                        errorIndex = index + 1;
                        return false;
                    }

                    if ($(element).find('input.Valor').val() == '') {
                        errorIndex = index + 1;
                        erValor = true;
                        return false;
                    }
                    //Fin Validaciones
                });

                if (erConcepto) {
                    $('div.Total').show(500);
                    $('div.Total').html('Debe seleccionar un concepto en la fila ' + errorIndex).css({
                        'color': 'red',
                        'font-size': '18px'
                    });
                    $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
                    return false;
                }

                else if (erValor) {
                    $('div.Total').show(500);
                    $('div.Total').html('Debe digitar un Valor numérico en la fila ' + errorIndex).css({
                        'color': 'red',
                        'font-size': '18px'
                    });
                    $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
                    return false;
                }
                else {
                    $('div.Total').hide(1000);
                    return true;
                }
            }

            var config = {
                '.chosen-select': {},
                '.chosen-select-deselect': {allow_single_deselect: true},
                '.chosen-select-no-single': {disable_search_threshold: 10},
                '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                '.chosen-select-width': {width: "95%"}
            };
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
        }
    );
</script>
<style>
    #contenedor {
        margin-left: 260px;
        width: 940px;
    }

    #contenedor div {
        float: left;
    }

    #contenedor div div {
        padding-left: 1px;
        margin-top: 3px;
    }

    .Total {
        text-align: center;
    }

    .eliminar.btnAzul {
        height: 29px;
        padding-top: 5px;
        border-radius: 7px;
    }

    #AgregarCampo {
        margin-bottom: 20px;
    }

    input.Valor {
        border-radius: 6px;
        padding-top: 7px;
    }
</style>
</body>
</html>