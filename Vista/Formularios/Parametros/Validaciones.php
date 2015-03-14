<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Usuarios.php';
    include '../../../Clases/cls_Contabilidad.php';

    session_start();

    $Parametros = new cls_Parametros();
    $Usuarios = new cls_Usuarios();
    $Contabilidad = new cls_Contabilidad();

    if ($_GET['action'] == 'insertarformapago') {
        $Parametros->ValidaCodigoFormaPago($_GET['txtCodigo'], $_SESSION['login'][0]["ID_EMPRESA"]);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este codigo Ya existe</span><br><br>
               <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto;" readonly/>';
        } else {
            echo '<span id="lblError" class="Error"></span><br>
            <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'insertarusuario') {

        $Usuarios->ValidaCorreo($_GET['txtEmail']);
        $Usuarios->ValidaDocumento($_GET['txtDocumento']);

        if ($Usuarios->_ExisteDocumento == 1) {
            echo '<span id="lblError" class="Error">Este documento ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else if ($Usuarios->_ExisteCorreo == 1) {
            echo '<span id="lblError" class="Error">Este correo ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'editarusuario') {

        $Usuarios->ValidaCorreoEditar($_GET['txtEmail'], $_GET['id']);
        $Usuarios->ValidaDocumentoEditar($_GET['txtDocumento'], $_GET['id']);

        if ($Usuarios->_ExisteDocumento == 1) {
            echo '<span id="lblError" class="Error">Este documento ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else if ($Usuarios->_ExisteCorreo == 1) {
            echo '<span id="lblError" class="Error">Este correo ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'insertarcuentacontable') {

        $Contabilidad->ValidaCodigo($_GET['txtCodigo']);

        if ($Contabilidad->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este código ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }
    } else if ($_GET['action'] == 'editarcuentacontable') {

        $Contabilidad->ValidaCodigoEditar($_GET['txtCodigo'], $_GET['id']);

        if ($Contabilidad->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este código ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'insertartercero') {

        $Parametros->ValidaDocumentoTercero($_GET['txtNumDoc'], $_SESSION['login'][0]["ID_EMPRESA"]);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este número de documento ya existe</span><br><br>
               <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';
        } else {
            echo '<span id="lblError" class="Error"></span><br>
            <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'editartercero') {

        $Parametros->ValidaDocumentoTerceroEditar($_GET['txtNumDoc'], $_GET['id']);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este número de documento ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'insertarproducto') {

        $Parametros->ValidaCodigoProducto($_GET['txtCodigo'], $_SESSION['login'][0]["ID_EMPRESA"]);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este código de producto ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'editarproducto') {

        $Parametros->ValidaCodigoProductoEditar($_GET['txtCodigo'], $_GET['id']);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este código de producto ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'insertarempresa') {

        $Usuarios->ValidaCorreo($_GET['txtemail']);
        $Parametros->ValidaNitEmpresa($_GET['txtnit']);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este Nit ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';
        } else if ($Usuarios->_ExisteCorreo == 1) {
            echo '<span id="lblError" class="Error">Este correo ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';
        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'insertarvendedor') {

        $Parametros->ValidaDocVendedor($_GET['txtdoc']);
        $Usuarios->ValidaCorreo($_GET['txtemail']);

        if ($Parametros->_ExisteCodigo == 1) {
            echo '<span id="lblError" class="Error">Este Documento ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else if ($Usuarios->_ExisteCorreo == 1) {
            echo '<span id="lblError" class="Error">Este correo ya existe.</span><br><br>
             <input type="button"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px; background-color: #A9A9A9;cursor: auto" readonly/>';

        } else {
            echo '<span id="lblError" class="Error"></span><br>
          <input type="submit"  class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"  style="width:200px;"/>';
        }

    } else if ($_GET['action'] == 'recibocajamenor') {
        if (!is_numeric($_GET['Valor']))
            echo '<span class="Error">El valor debe ser un numero</span><br><br><br>
           <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto" disabled/> ';
        else if ($_GET['ciudad'] == "0")
            echo '<span class="Error">LA CANTIDAD A PAGAR ES MAYOR QUE EL TOTAL DE LOS PRODUCTOS</span><br><br><br>
       <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto" disabled/> ';

        else  echo '<input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px;" /> ';

    }
?>