<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/Componentes.php';

    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();
    $Contabilidad = new cls_Contabilidad();


    $Cuenta = '<option value ="0" selected>-- Seleccione Una Cuenta --</option>';
    $Tercero = '<option value ="0" selected>-- Seleccione Un Tercero --</option>';

    foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1)
        $Cuenta .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';

    foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $Tercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';

?>
<html>
<head>
    <title>Saldos Iniciales</title>

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
                <h3><b>SALDOS INICIALES</b></h3><br>


                <input type="button" id="AgregarCampo" class="btnAzul" value="Agregar Campo" style="width:160px;">

                <form action="">
                    <div id="contenedor"></div>
                    <input type="hidden" name="saldos">
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

    $('body').on('change', '#contenedor div div select#cmbCuenta', function () {
        var obj = $(this);
        if ($(this).val() > 0) {
            $.ajax({
                url: 'Actions.php', type: 'post', data: {'validar': 'validar', 'id': $(this).val()},
                success: function (data) {
                    if (data == 0) {
                        obj.parent().next().fadeOut(500, function () {
                            obj.parent().css('margin-right', '202px');
                        });
                    }
                    else {
                        obj.parent().next().fadeIn(500);
                        obj.parent().css('margin-right', '0');
                    }
                },
                error: function () {alert('Ha ocurrido un error en el sistema');}
            });
        }
    });

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
        + ' <div><div  id="fila"   class="btnAzul" style="height: 13px;width: 24px;padding: 6px;">1</div></div>'

        + '<div> <select id="cmbCuenta" name="cmbCuenta[]"  style="width:200px;" class="chosen-select" >'
        + ' <?=$Cuenta ?> '
        + ' </select></div>'

        + '<div> <select  name="cmbTercero[]" id="cmbTercero" class="chosen-select" style="width:200px;" >'
        + ' <?=$Tercero ?> '
        + '</select></div>'

        + '<div> <select  name="cmbTipoMov[]" class="chosen-select" id="cmbTipoMov" style="width:100px;">'
        + ' <option value="D" selected>DEBITO</option>'
        + ' <option value="C">CREDITO</option>'
        + ' </select></div>'

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
            + '<div> <select id="cmbCuenta" name="cmbCuenta[]"  class="chosen-select" style="width:200px;">'
            + ' <?=$Cuenta ?> '
            + ' </select></div>'

            + '<div> <select  name="cmbTercero[]" id="cmbTercero" class="chosen-select" style="width:200px;">'
            + ' <?=$Tercero ?> '
            + ' </select></div>'

            + '<div> <select id="cmbTipoMov"  name="cmbTipoMov[]" class="chosen-select"  style="width:100px;">'
            + ' <option value="D" selected>DEBITO</option>'
            + ' <option value="C">CREDITO</option>'
            + ' </select></div>'

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
        var debitos = 0, creditos = 0;
        var find1, find2;
        var erValor = false, erCuenta = false, erTercero = false;
        var errorIndex = 1;

        $('#contenedor div').each(function (index, element) {
            find1 = $(element).find('select#cmbTipoMov');
            find2 = $(element).find('input.Valor');

            //Validaciones
            if ($(element).find('select#cmbCuenta').val() == 0) {
                erCuenta = true;
                return false;
            }

            if ($(element).find('select#cmbTercero').parent().is(':visible') && $(element).find('select#cmbTercero').val() == 0) {
                erTercero = true;
                return false;
            }
            if ($(element).find('input.Valor').val() == '') {
                erValor = true;
                return false;
            }
            //Fin Validaciones

            if (find2.length && find1.length) {
                if (find1.val() === 'D')
                    debitos += !isNaN(parseInt(find2.val())) ? parseInt(find2.val()) : 0;
                else
                    creditos += !isNaN(parseInt(find2.val())) ? parseInt(find2.val()) : 0;
                errorIndex++;
            }
        });

        if (erCuenta) {
            $('div.Total').html('Debe seleccionar una cuenta en la fila ' + errorIndex).css({
                'color': 'red',
                'font-size': '18px'
            });
            $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
            return false;
        }
        else if (erTercero) {
            $('div.Total').html('Debe seleccionar un tercero en la fila ' + errorIndex).css({
                'color': 'red',
                'font-size': '18px'
            });
            $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
            return false;
        }
        else if (erValor) {
            $('div.Total').html('Debe digitar un Valor numérico en la fila ' + errorIndex).css({
                'color': 'red',
                'font-size': '18px'
            });
            $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
            return false;
        }
        else if (debitos < creditos) {
            $('div.Total').html('Los débitos son menores a los creditos')
                .css({'color': 'red', 'font-size': '18px'});
            $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
            return false;
        }
        else if (debitos > creditos) {
            $('div.Total').html('Los débitos son mayores a los creditos')
                .css({'color': 'red', 'font-size': '18px'});
            $('div.Total').append('&nbsp;<img src="../../Imagenes/bad.png" style="width: 32px;height: 32px;" />');
            return false;
        }
        else {
            $('div.Total').html('Los débitos son iguales a los creditos')
                .css({'color': 'green', 'font-size': '18px'});
            $('div.Total').append('&nbsp;<img src="../../Imagenes/good.png" style="width: 32px;height: 32px;" />');
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
});
</script>
<style>
    #contenedor {
        margin-left: 220px;
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