<?php

    class cls_Parametros
    {
        public $_prueba;
        public $_ExisteCodigo;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function get_ExisteCodigo()
        {
            return $this->_ExisteCodigo;
        }

        public function set_ExisteCodigo($_ExisteCodigo)
        {
            $this->_ExisteCodigo = $_ExisteCodigo;
        }

        public function TraeDocumentos($idEmpresa)
        {
            $query = "SELECT
            t_documentos.ID_DOCUMENTO,
            t_documentos.TIPO,
            t_documentos.NOMBRE_DOCUMENTO,
            t_documentos.NOMBRE_IMPRESO,
            t_documentos.CONSECUTIVO,
            t_documentos.ESTADO,
            t_documentos.USR_REGISTRO,
            t_documentos.FECHA_REGISTRO,
            t_documentos.ID_EMPRESA
            FROM
            t_documentos WHERE t_documentos.ESTADO=1 AND t_documentos.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeCiudades()
        {
            $query = "SELECT
            t_ciudades.ID_CIUDAD,
            CONCAT(UPPER(LEFT(t_ciudades.NOMBRE, 1)), LOWER(SUBSTRING(t_ciudades.NOMBRE, 2))) AS NOMBRE,
            CONCAT(UPPER(LEFT(t_ciudades.DEPARTAMENTO, 1)), LOWER(SUBSTRING(t_ciudades.DEPARTAMENTO, 2)))AS DEPARTAMENTO
            FROM
            t_ciudades
            ORDER BY NOMBRE";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeTerceros($idEmpresa)
        {
            $query = "SELECT
            t_terceros.ID_TERCERO,
            t_terceros.TIPO_DOCUMENTO,
            t_terceros.NUM_DOCUMENTO,
            t_terceros.DIRECCION,
            concat(t_terceros.NOMBRE1,' ',t_terceros.NOMBRE2,' ',t_terceros.APELLIDO1,' ',t_terceros.APELLIDO2)AS N_COMPLETO,
            t_terceros.TELEFONO,
            t_terceros.CELULAR,
            t_terceros.EMAIL,
            t_terceros.ID_CIUDAD,
            t_terceros.USR_REGISTRO,
            t_terceros.FECHA_REGISTRO,
            t_terceros.ID_EMPRESA,
            t_terceros.ESTADO
            FROM
            t_terceros
            WHERE t_terceros.ESTADO=1 AND t_terceros.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeTercero($idEmpresa, $dato, $modo)
        {
            $q1 = $modo == 'Doc' ? "(select ID_TERCERO from t_terceros WHERE NUM_DOCUMENTO=" . $dato . ")" : $dato;
            $query = "SELECT
            t_terceros.NUM_DOCUMENTO,
            concat(t_terceros.NOMBRE1,' ',t_terceros.NOMBRE2,' ',t_terceros.APELLIDO1,' ',t_terceros.APELLIDO2)AS N_COMPLETO,
            t_terceros.EMAIL
            FROM
            t_terceros
            WHERE t_terceros.ESTADO=1 AND t_terceros.ID_TERCERO=" . $q1 . " AND t_terceros.ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeFormasPago($idEmpresa)
        {
            $query = "SELECT
		t_formas_pago.ID_F_PAGO,
		t_formas_pago.ID_CUENTA,
		t_formas_pago.CODIGO_F_PAGO,
		t_formas_pago.NOMBRE_F_PAGO,
		t_formas_pago.ESTADO,
		t_formas_pago.FECHA_REGISTRO,
		t_formas_pago.USR_REGISTRO,
		t_formas_pago.ID_EMPRESA,
		t_formas_pago.REQUIERE_ENTIDAD,
		t_formas_pago.REQUIERE_NUMERO,
		t_cuentas.NOMBRE
		FROM
		t_formas_pago
		INNER JOIN t_cuentas ON t_formas_pago.ID_CUENTA = t_cuentas.ID_CUENTA
		WHERE t_formas_pago.ESTADO=1 AND t_formas_pago.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function EliminarTercero($id)
        {
            $query = "UPDATE `t_terceros` SET `ESTADO`=0 WHERE (`ID_TERCERO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function EliminarFormaPago($id)
        {
            $query = "UPDATE `t_formas_pago` SET `ESTADO`=0 WHERE (`ID_F_PAGO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function TraeDatosDocumento($IdDoc)
        {
            $query = "SELECT *
FROM
t_documentos WHERE t_documentos.ID_DOCUMENTO=" . $IdDoc . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeDocumentoConsumo($idEmpresa)
        {
            $query = "SELECT ID_CUENTA FROM  t_documentos WHERE  TIPO_INTERNO='IMPUESTO_CONSUMO'  AND ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeDocumentoCxP($idEmpresa)
        {
            $query = "SELECT ID_CUENTA FROM  t_documentos WHERE  TIPO_INTERNO='CXP'  AND ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function ActualizaDocumento($id, $Tipo, $Nombre, $NombreImpreso, $Consecutivo, $cta, $leyenda, $Usreg)
        {
            $query = "UPDATE `t_documentos` SET `TIPO`='" . $Tipo . "', `NOMBRE_DOCUMENTO`='" . $Nombre . "',
       `NOMBRE_IMPRESO`='" . $NombreImpreso . "', `CONSECUTIVO`=" . $Consecutivo . ",`ID_CUENTA`=" . $cta . ", `LEYENDA`='" . $leyenda . "',`USR_REGISTRO`=" . $Usreg . ", `FECHA_REGISTRO`=now()
        WHERE (`ID_DOCUMENTO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaDocGastos($cta1, $cta2, $IdEmpresa)
        {
            $query = "UPDATE `t_documentos` SET ID_CUENTA=" . $cta1 . " WHERE TIPO_INTERNO='IMPUESTO_CONSUMO' AND ID_EMPRESA=" . $IdEmpresa . "
            ;UPDATE `t_documentos` SET ID_CUENTA=" . $cta2 . " WHERE TIPO_INTERNO='CXP' AND ID_EMPRESA=" . $IdEmpresa;

            if ($this->_DB->Exec($query) > 0)
                return true;
            else
                return false;
        }


        public function ActualizaFormaPago($id, $Codigo, $Nombre, $cta, $RequiereEntidad, $RequiereNumero)
        {
            $query = "UPDATE `t_formas_pago`
        SET `CODIGO_F_PAGO`='" . $Codigo . "', `NOMBRE_F_PAGO`='" . $Nombre . "',`ID_CUENTA`=" . $cta . ",
         `REQUIERE_ENTIDAD`=" . $RequiereEntidad . ", `REQUIERE_NUMERO`=" . $RequiereNumero . " WHERE (`ID_F_PAGO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function InsertaGrupos($Nombre, $ctaInventario, $ctaVentas, $ctaCosto, $ctaDevoluciones, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_grupos`
       (`NOMBRE`, `CTA_INVENTARIO`, `CTA_VENTAS`, `CTA_COSTO`, `CTA_DEVOLUCIONES`, `ESTADO`, `USR_REGISTRO`, `FECHA_REGISTRO`, `ID_EMPRESA`)
       VALUES
       ('" . $Nombre . "', " . $ctaInventario . ", " . $ctaVentas . ", " . $ctaCosto . ", " . $ctaDevoluciones . ",1," . $UsrReg . ",now()," . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function InsertaFormaPago($Codigo, $Nombre, $cta, $RequiereEntidad, $RequiereNumero, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_formas_pago`
       (`ID_CUENTA`, `CODIGO_F_PAGO`, `NOMBRE_F_PAGO`, `ESTADO`, `FECHA_REGISTRO`,
        `USR_REGISTRO`,`ID_EMPRESA`, `REQUIERE_ENTIDAD`, `REQUIERE_NUMERO`)
         VALUES
        (" . $cta . ", '" . $Codigo . "', '" . $Nombre . "',1, now(), " . $UsrReg . ", " . $IdEmpresa . "," . $RequiereEntidad . ", " . $RequiereNumero . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaGrupo($id, $Nombre, $ctaInventario, $ctaVentas, $ctaCosto, $ctaDevoluciones)
        {
            $query = "UPDATE `t_grupos`
       SET `NOMBRE`='" . $Nombre . "',`CTA_INVENTARIO`='" . $ctaInventario . "', `CTA_VENTAS`=" . $ctaVentas . ",`CTA_COSTO`=" . $ctaCosto . "
        ,`CTA_DEVOLUCIONES`='" . $ctaDevoluciones . "'  WHERE (`ID_GRUPO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function TraeDatosGrupo($id)
        {
            $query = "SELECT
t_grupos.ID_GRUPO,
t_grupos.NOMBRE,
t_grupos.CTA_INVENTARIO,
t_grupos.CTA_VENTAS,
t_grupos.CTA_COSTO,
t_grupos.CTA_DEVOLUCIONES,
t_grupos.ESTADO,
t_grupos.USR_REGISTRO,
t_grupos.FECHA_REGISTRO,
t_grupos.ID_EMPRESA
FROM
t_grupos
WHERE t_grupos.ID_GRUPO=" . $id . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeDatosFormaPago($id)
        {
            $query = "SELECT
t_formas_pago.ID_F_PAGO,
t_formas_pago.ID_CUENTA,
t_formas_pago.CODIGO_F_PAGO,
t_formas_pago.NOMBRE_F_PAGO,
t_formas_pago.REQUIERE_ENTIDAD,
t_formas_pago.REQUIERE_NUMERO,
t_formas_pago.ESTADO,
t_formas_pago.FECHA_REGISTRO,
t_formas_pago.USR_REGISTRO,
t_formas_pago.ID_EMPRESA
FROM
t_formas_pago
WHERE t_formas_pago.ID_F_PAGO=" . $id . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function EliminarGrupo($id)
        {
            $query = "UPDATE `t_grupos` SET `ESTADO`=0 WHERE (`ID_GRUPO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function TraeDatosProducto($id)
        {
            $query = "SELECT t_productos.ID_PRODUCTO,
t_productos.TIPO,
t_productos.CODIGO,
t_productos.DESCRIPCION,
t_productos.PRECIO,
t_productos.ID_GRUPO,
t_productos.ESTADO,
t_productos.USR_REGISTRO,
t_productos.FECHA_REGISTRO,
t_productos.ID_EMPRESA
FROM
t_productos
WHERE t_productos.ID_PRODUCTO=" . $id . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function EliminarProducto($id)
        {
            $query = "UPDATE `t_productos` SET `ESTADO`=0 WHERE (`ID_PRODUCTO`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function TraeDatosTercero($id)
        {
            $query = "SELECT
t_terceros.ID_TERCERO,
t_terceros.NOMBRE1,
t_terceros.NOMBRE2,
t_terceros.APELLIDO1,
t_terceros.APELLIDO2,
t_terceros.TIPO_DOCUMENTO,
t_terceros.NUM_DOCUMENTO,
t_terceros.DIRECCION,
t_terceros.TELEFONO,
t_terceros.CELULAR,
t_terceros.EMAIL,
t_terceros.ID_CIUDAD,
t_terceros.USR_REGISTRO,
t_terceros.FECHA_REGISTRO,
t_terceros.ID_EMPRESA,
t_terceros.ESTADO
FROM
t_terceros
WHERE t_terceros.ID_TERCERO=" . $id . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeGrupos($idEmpresa)
        {
            $query = "SELECT
            t_grupos.ID_GRUPO,
            t_grupos.NOMBRE,
            t_grupos.CTA_INVENTARIO,
            t_grupos.CTA_VENTAS,
            t_grupos.CTA_COSTO,
            t_grupos.CTA_DEVOLUCIONES,
            (SELECT concat(t_cuentas.CODIGO,' - ',t_cuentas.NOMBRE) FROM t_cuentas WHERE t_cuentas.ID_CUENTA= t_grupos.CTA_INVENTARIO)AS N_CTA_INVENTARIO,
            (SELECT concat(t_cuentas.CODIGO,' - ',t_cuentas.NOMBRE) FROM t_cuentas WHERE t_cuentas.ID_CUENTA= t_grupos.CTA_VENTAS)AS N_CTA_VENTAS,
            (SELECT concat(t_cuentas.CODIGO,' - ',t_cuentas.NOMBRE) FROM t_cuentas WHERE t_cuentas.ID_CUENTA= t_grupos.CTA_COSTO)AS N_CTA_COSTO,
            (SELECT concat(t_cuentas.CODIGO,' - ',t_cuentas.NOMBRE) FROM t_cuentas WHERE t_cuentas.ID_CUENTA= t_grupos.CTA_DEVOLUCIONES) AS N_CTA_DEVOLUCIONES,
            t_grupos.ESTADO,
            t_grupos.USR_REGISTRO,
            t_grupos.FECHA_REGISTRO,
            t_grupos.ID_EMPRESA
            FROM
            t_grupos
            WHERE t_grupos.ESTADO=1 AND t_grupos.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeProductos($idEmpresa)
        {
            $query = "SELECT
t_productos.ID_PRODUCTO,
t_productos.TIPO,
t_productos.CODIGO,
t_productos.DESCRIPCION,
t_productos.PRECIO,
t_productos.ID_GRUPO,
t_productos.ESTADO,
t_productos.USR_REGISTRO,
t_productos.FECHA_REGISTRO,
t_productos.ID_EMPRESA,
t_grupos.NOMBRE AS NOMBRE_GRUPO
FROM
t_productos
INNER JOIN t_grupos ON t_productos.ID_GRUPO = t_grupos.ID_GRUPO
WHERE t_productos.ESTADO=1 AND t_productos.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function ValidaCodigoFormaPago($Cod, $IdEmpresa)
        {

            $query = "SELECT CASE WHEN(SELECT CODIGO_F_PAGO
		FROM
		t_formas_pago WHERE CODIGO_F_PAGO='" . $Cod . "' AND ID_EMPRESA=" . $IdEmpresa . ")IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function ValidaCodigoFormaPagoEditar($Cod, $IdEmpresa, $id)
        {

            $query = "SELECT CASE WHEN(SELECT CODIGO_F_PAGO
		FROM
		t_formas_pago WHERE CODIGO_F_PAGO='" . $Cod . "' AND ID_EMPRESA=" . $IdEmpresa . " AND ID_F_PAGO <> " . $id . ")IS NULL THEN ('0')ELSE ('1')
		END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function ValidaCodigoProducto($Cod, $IdEmpresa)
        {

            $query = "SELECT CASE WHEN(SELECT CODIGO
		FROM
		t_productos WHERE CODIGO='" . $Cod . "' AND ID_EMPRESA=" . $IdEmpresa . ")IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function ValidaNitEmpresa($Cod)
        {

            $query = "SELECT CASE WHEN(SELECT NIT
        FROM
        t_empresas WHERE NIT='" . $Cod . "')IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function ValidaDocVendedor($Cod)
        {

            $query = "SELECT CASE WHEN(SELECT Documento
        FROM
        t_vendedor WHERE DOCUMENTO='" . $Cod . "')IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function ValidaCodigoProductoEditar($Cod, $id)
        {

            $query = "SELECT CASE WHEN(SELECT CODIGO
		FROM
		t_productos WHERE CODIGO='" . $Cod . "' AND ID_PRODUCTO <> " . $id . ")IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function InsertaProducto($Codigo, $Nombre, $Tipo, $Precio, $Grupo, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_productos`
       (`TIPO`, `CODIGO`, `DESCRIPCION`, `PRECIO`, `ID_GRUPO`, `ESTADO`, `USR_REGISTRO`, `FECHA_REGISTRO`, `ID_EMPRESA`)
       VALUES
       ('" . $Tipo . "', '" . $Codigo . "', '" . $Nombre . "', " . $Precio . ", " . $Grupo . ", 1, " . $UsrReg . ",now()," . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function InsertaTercero($Nombre1, $Nombre2, $Apellido1, $Apellido2, $TipoDoc, $NumDoc, $Direccion, $Telefono, $Celular, $Email, $IdCiudad, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_terceros`
       (`NOMBRE1`, `NOMBRE2`, `APELLIDO1`, `APELLIDO2`, `TIPO_DOCUMENTO`, `NUM_DOCUMENTO`, `DIRECCION`,
       `TELEFONO`, `CELULAR`, `EMAIL`, `ID_CIUDAD`, `ESTADO`, `USR_REGISTRO`, `FECHA_REGISTRO`, `ID_EMPRESA`)
       VALUES
       ('" . $Nombre1 . "', '" . $Nombre2 . "', '" . $Apellido1 . "', '" . $Apellido2 . "', '" . $TipoDoc . "', '" . $NumDoc . "', '" . $Direccion . "',
        '" . $Telefono . "', '" . $Celular . "', '" . $Email . "'," . $IdCiudad . ",1," . $UsrReg . ", now(), " . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaProducto($Id, $Codigo, $Nombre, $Tipo, $Precio, $Grupo)
        {
            $query = "UPDATE `t_productos`
       SET `TIPO`='" . $Tipo . "', `CODIGO`='" . $Codigo . "', `DESCRIPCION`='" . $Nombre . "',
        `PRECIO`=" . $Precio . ", `ID_GRUPO`=" . $Grupo . " WHERE (`ID_PRODUCTO`=" . $Id . ")";

            $this->_DB->Exec($query);
            return true;

        }

        public function ActualizaTercero($Id, $Nombre1, $Nombre2, $Apellido1, $Apellido2, $TipoDoc, $NumDoc, $Direccion, $Telefono, $Celular, $Email, $IdCiudad)
        {
            $query = "UPDATE `t_terceros`
        SET `NOMBRE1`='" . $Nombre1 . "', `NOMBRE2`='" . $Nombre2 . "', `APELLIDO1`='" . $Apellido1 . "', `APELLIDO2`='" . $Apellido2 . "',
            `TIPO_DOCUMENTO`='" . $TipoDoc . "',`NUM_DOCUMENTO`='" . $NumDoc . "', `DIRECCION`='" . $Direccion . "', `TELEFONO`='" . $Telefono . "',
            `CELULAR`='" . $Celular . "',`EMAIL`='" . $Email . "', `ID_CIUDAD`=" . $IdCiudad . " WHERE (`ID_TERCERO`=" . $Id . ")";

            $this->_DB->Exec($query);
            return true;

        }

        public function ValidaDocumentoTercero($Doc, $IdEmpresa)
        {

            $query = "SELECT CASE WHEN(SELECT NUM_DOCUMENTO
		FROM
		t_terceros WHERE NUM_DOCUMENTO='" . $Doc . "' AND ID_EMPRESA=" . $IdEmpresa . ")IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function ValidaDocumentoTerceroEditar($Cod, $Id)
        {

            $query = "SELECT CASE WHEN(SELECT NUM_DOCUMENTO
		FROM
		t_terceros WHERE NUM_DOCUMENTO='" . $Cod . "' AND ID_TERCERO <>" . $Id . ")IS NULL THEN ('0') ELSE ('1') END ";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }


        public function TraeConceptos($IdEmpresa)
        {
            $query = "SELECT
        t_conceptos.ESTADO,
        t_conceptos.ID_CONCEPTO,
        t_conceptos.DESCRIPCION,
        t_conceptos.CODIGO,
        if(t_conceptos.CONCEPTO=0,'Gastos','Ingresos') AS 'CONCEPTO',
        t_cuentas.NOMBRE AS 'NOMBRE_CUENTA'
        
        FROM
            t_conceptos
        INNER JOIN t_cuentas ON t_cuentas.ID_CUENTA=t_conceptos.ID_CUENTA
        WHERE  t_conceptos.ESTADO=1 AND t_conceptos.ID_EMPRESA =" . $IdEmpresa;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeConcepto($Idconcepto)
        {
            $query = "SELECT
        t_conceptos.ESTADO,
        t_conceptos.ID_CONCEPTO,
        t_conceptos.DESCRIPCION,
        t_conceptos.CODIGO,
        t_conceptos.CONCEPTO,
        t_cuentas.NOMBRE AS 'NOMBRE_CUENTA',
        t_conceptos.ID_CUENTA
        
        FROM
            t_conceptos
        INNER JOIN t_cuentas ON t_cuentas.ID_CUENTA=t_conceptos.ID_CUENTA
        WHERE t_conceptos.ESTADO=1 AND  t_conceptos.ID_CONCEPTO=" . $Idconcepto;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function InsertaConcepto($Codigo, $Concepto, $Descripcion, $Idcuenta, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_conceptos`
        ( `CODIGO`,`CONCEPTO`,`DESCRIPCION`,`ID_CUENTA`, `ESTADO`, `USR_REGISTRO`, `FECHA_REGISTRO`, `ID_EMPRESA`)
       VALUES
       ('" . $Codigo . "', '" . $Concepto . "', '" . $Descripcion . "', '" . $Idcuenta . "',1," . $UsrReg . ", now(), " . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaConcepto($Codigo, $Concepto, $Descripcion, $Idcuenta, $UsrReg, $Idconcepto)
        {
            $query = "UPDATE `t_conceptos` SET `CODIGO`='" . $Codigo . "', `CONCEPTO`=" . $Concepto . ",
       `DESCRIPCION`='" . $Descripcion . "', `ID_CUENTA`=" . $Idcuenta . ", `USR_REGISTRO`=" . $UsrReg . "
       , `FECHA_REGISTRO`=now()
        WHERE (`ID_CONCEPTO`=" . $Idconcepto . ")";

            if ($this->_DB->Exec($query) > 0) return true; else return false;
        }

        public function EliminarConcepto($Idconcepto)
        {
            $query = "UPDATE `t_conceptos` SET `ESTADO`=0 WHERE (`ID_CONCEPTO`=" . $Idconcepto . ")";

            if ($this->_DB->Exec($query) > 0) return true;
            else  return false;
        }

        public function TraeMovContable($idEmpresa, $Cuenta, $inicio, $fin)
        {
            $resulset = $this->_DB->Query("CALL TraeMovContable(" . $idEmpresa . "," . $Cuenta . ", '" . $inicio . "', '" . $fin . "')");
            return $resulset->fetchAll();
        }

        public function TraeMovTerceroporNombre($idEmpresa, $IdTercero, $inicio, $fin)
        {
            $resulset = $this->_DB->Query("CALL TraeMovTerceroporNombre(" . $idEmpresa . "," . $IdTercero . ", '" . $inicio . "', '" . $fin . "')");
            return $resulset->fetchAll();
        }

        public function TraeMovTerceroporDoc($idEmpresa, $Doc, $inicio, $fin)
        {
            $resulset = $this->_DB->Query("CALL TraeMovTerceroporDoc(" . $idEmpresa . "," . $Doc . ", '" . $inicio . "', '" . $fin . "')");
            return $resulset->fetchAll();
        }


        public  function TraeProductosServicios($Ano, $Mes,$Dia, $IdEmpresa)
        {
            $resulset = $this->_DB->Query("CALL TraeValorProdServ($IdEmpresa,$Dia,$Ano,$Mes)");
            return $resulset->fetchAll();
        }
    }