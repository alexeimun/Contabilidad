<?php

    class cls_Inventario
    {
        //put your code here

        public $_prueba;
        public $_ExisteCodigo;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function EliminaProductosFinal($idUsuario, $idEmpresa)
        {
            $query = "DELETE FROM t_factura_temporal WHERE t_factura_temporal.ID_USUARIO=" . $idUsuario . " AND t_factura_temporal.ID_EMPRESA=" . $idEmpresa . "";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function AnulaFactura($Consecutivo, $idUsuario, $idEmpresa)
        {

            $query = "UPDATE `t_movimiento` SET `ANULADO`=1, `USR_ANULA`=" . $idUsuario . ", `FECHA_ANULA`=now() WHERE (`CONSECUTIVO`=" . $Consecutivo . " AND ID_EMPRESA=" . $idEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaConsecutivo($Consecutivo, $idEmpresa)
        {
            $query = "UPDATE `t_documentos` SET `CONSECUTIVO`=" . $Consecutivo . " WHERE (ID_EMPRESA=" . $idEmpresa . " AND TIPO_INTERNO='FACTURA')";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function TraeInventario()
        {
            $query = "SELECT * FROM t_inventario";
            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeInformacionFactura($Consecutivo, $idEmpresa)
        {
            $query = "SELECT DISTINCT
t_terceros.NOMBRE1,
t_terceros.NOMBRE2,
t_terceros.APELLIDO1,
t_terceros.APELLIDO2,
t_terceros.NUM_DOCUMENTO,
t_terceros.DIRECCION,
t_terceros.TELEFONO,
t_empresas.NOMBRE,
t_empresas.LOGO,
t_empresas.NIT,
t_empresas.DIRECCION AS DIR_EMPRESA,
t_empresas.TELEFONO AS TEL_EMPRESA,
t_empresas.EMAIL,
t_movimiento.FECHA_REGISTRO,
t_movimiento.OBS,
t_movimiento.ANULADO,
t_usuarios.NOMBRE_COMPLETO,
(SELECT LEYENDA FROM t_documentos WHERE TIPO_INTERNO='FACTURA' AND ID_EMPRESA=" . $idEmpresa . ") AS LEYENDA
FROM
t_movimiento
INNER JOIN t_terceros ON t_movimiento.ID_TERCERO = t_terceros.ID_TERCERO
INNER JOIN t_empresas ON t_movimiento.ID_EMPRESA = t_empresas.ID_EMPRESA
INNER JOIN t_usuarios ON t_movimiento.USR_REGISTRO = t_usuarios.ID_USUARIO

WHERE t_movimiento.CONSECUTIVO=" . $Consecutivo . " AND t_movimiento.ID_EMPRESA =" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeFacturasReimpresion($idEmpresa)
        {
            $query = "SELECT DISTINCT
t_terceros.NOMBRE1,
t_terceros.NOMBRE2,
t_terceros.APELLIDO1,
t_terceros.APELLIDO2,
t_terceros.NUM_DOCUMENTO,
t_terceros.DIRECCION,
t_terceros.TELEFONO,
t_empresas.NOMBRE,
t_empresas.LOGO,
t_empresas.NIT,
t_empresas.DIRECCION AS DIR_EMPRESA,
t_empresas.TELEFONO AS TEL_EMPRESA,
t_empresas.EMAIL,
t_movimiento.FECHA_REGISTRO,
t_movimiento.OBS,
t_usuarios.NOMBRE_COMPLETO,
t_movimiento.VALOR,
t_movimiento.CONSECUTIVO,
t_movimiento.ANULADO,
t_movimiento.FECHA_REGISTRO
FROM
t_movimiento
INNER JOIN t_terceros ON t_movimiento.ID_TERCERO = t_terceros.ID_TERCERO
INNER JOIN t_empresas ON t_movimiento.ID_EMPRESA = t_empresas.ID_EMPRESA
INNER JOIN t_usuarios ON t_movimiento.USR_REGISTRO = t_usuarios.ID_USUARIO
WHERE ID_PRODUCTO=0 AND t_movimiento.ID_EMPRESA =" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeDetalleFactura($Consecutivo, $idEmpresa)
        {
            $query = "SELECT DISTINCT
t_movimiento.DESCRIPCION,
t_movimiento.CANTIDAD,
t_movimiento.VALOR,
t_movimiento.DESCUENTO
FROM
t_movimiento
WHERE t_movimiento.ID_PRODUCTO <> 0 AND t_movimiento.CONSECUTIVO=" . $Consecutivo . " AND t_movimiento.ID_EMPRESA =" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function InsertaMovimiento($IdTercero, $IdProducto, $IdCuentaMov, $TipoDoc, $Consecutivo, $IdFormaPago, $Secuencia, $Descripcion, $TipoMov, $Cantidad, $Valor, $Descuento, $Obs, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_movimiento`
       (`ID_TERCERO`, `ID_PRODUCTO`, `ID_CUENTA_MOV`, `TIPO_DOC`, `CONSECUTIVO`, `ID_F_PAGO`, `SECUENCIA`,
       `DESCRIPCION`, `TIPO_MOV`, `CANTIDAD`, `VALOR`,`DESCUENTO`, `ANULADO`, `OBS`, `USR_REGISTRO`, `FECHA_REGISTRO`, `ID_EMPRESA`)
       VALUES
       (" . $IdTercero . ", " . $IdProducto . ", " . $IdCuentaMov . ", '" . $TipoDoc . "', " . $Consecutivo . ", " . $IdFormaPago . ", " . $Secuencia . ",
        '" . $Descripcion . "', '" . $TipoMov . "', " . $Cantidad . ", " . $Valor . ", " . $Descuento . ",0, '" . $Obs . "', " . $UsrReg . ", now(), " . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

    }

?>
