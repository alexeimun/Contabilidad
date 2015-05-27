<?php

    class cls_Factura
    {
        public $_ExisteProducto;
        public $_CantidadProductos;
        public $_IdParam;

        public $_Consecutivo;
        public $_ConsecutivoRecibo;
        public $_PrecioProducto;
        public $_RequiereEntidad;
        public $_RequiereNumero;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function get_PrecioProducto()
        {
            return $this->_PrecioProducto;
        }

        public function set_PrecioProducto($_PrecioProducto)
        {
            $this->_PrecioProducto = $_PrecioProducto;
        }

        public function get_Consecutivo()
        {
            return $this->_Consecutivo;
        }

        public function set_Consecutivo($_Consecutivo)
        {
            $this->_Consecutivo = $_Consecutivo;
        }

        public function get_IdParam()
        {
            return $this->_IdParam;
        }

        public function set_IdParam($_IdParam)
        {
            $this->_IdParam = $_IdParam;
        }

        public function get_CantidadProductos()
        {
            return $this->_CantidadProductos;
        }

        public function set_CantidadProductos($_CantidadProductos)
        {
            $this->_CantidadProductos = $_CantidadProductos;
        }

        public function get_ExisteProducto()
        {
            return $this->_ExisteProducto;
        }

        public function set_ExisteProducto($_ExisteProducto)
        {
            $this->_ExisteProducto = $_ExisteProducto;
        }

        public function TraeProductos($idUsuario, $idEmpresa)
        {
            $query = "SELECT
        t_factura_temporal.ID,
        t_factura_temporal.ID_PRODUCTO,
        t_factura_temporal.CANTIDAD,
        t_factura_temporal.DESCUENTO,
        t_factura_temporal.ID_USUARIO,
        t_factura_temporal.ID_EMPRESA,
        t_productos.DESCRIPCION,
        t_productos.CODIGO,
        t_productos.PRECIO
        FROM
        t_factura_temporal
        INNER JOIN t_productos ON t_factura_temporal.ID_PRODUCTO = t_productos.ID_PRODUCTO
        WHERE t_factura_temporal.ID_USUARIO=" . $idUsuario . " AND t_factura_temporal.ID_EMPRESA=" . $idEmpresa . " ORDER BY t_factura_temporal.ID";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeInfoProducto($idProducto)
        {
            $query = "SELECT
            t_productos.PRECIO
            FROM
            t_productos WHERE ID_PRODUCTO=" . $idProducto;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';

            foreach ($Campos as $key => $datos) {
                $this->_PrecioProducto = $datos[0];
            }
            // var_dump($datos);
            return $datos;
        }

        public function TraeParametrosFactura($idUsuario, $idEmpresa)
        {
            $query = "SELECT
        (SELECT Count(*) FROM t_factura_temporal
        WHERE t_factura_temporal.ID_USUARIO=" . $idUsuario . " AND t_factura_temporal.ID_EMPRESA=" . $idEmpresa . ") AS CANTIDAD_PRODUCTOS,
        (SELECT CONSECUTIVO FROM t_documentos WHERE TIPO_INTERNO='FACTURA' AND ID_EMPRESA=" . $idEmpresa . ") AS CONSECUTIVO,
                     (SELECT ID_DOCUMENTO FROM t_documentos WHERE TIPO_INTERNO='FACTURA' AND ID_EMPRESA=14) AS ID_PARAM";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';
            foreach ($Campos as $key => $datos) {
                $this->_CantidadProductos = ($datos[0]);
                $this->_Consecutivo = ($datos[1]);
                $this->_IdParam = ($datos[2]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function TraeParametrosRecibo($idEmpresa)
        {
            $query = "SELECT CONSECUTIVO,ID_DOCUMENTO FROM t_documentos WHERE TIPO_INTERNO='RECIBO' AND ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            $this->_ConsecutivoRecibo = $Campos[0][0];
            $this->_IdParam = $Campos[0][1];
        }


        public function CuentaProductos($idUsuario, $idEmpresa)
        {

            $query = "SELECT Count(*) FROM t_factura_temporal
         WHERE t_factura_temporal.ID_USUARIO=" . $idUsuario . " AND t_factura_temporal.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';
            foreach ($Campos as $key => $datos) {
                $this->_CantidadProductos = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }

        public function InsertaProducto($IdProducto, $Cantidad, $Descuento, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_factura_temporal`
        (`ID_PRODUCTO`, `CANTIDAD`, `DESCUENTO`, `ID_USUARIO`, `ID_EMPRESA`)
        VALUES  (" . $IdProducto . ", " . $Cantidad . "," . $Descuento . " , " . $UsrReg . ", " . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function EliminarProducto($id)
        {
            $query = "DELETE FROM `t_factura_temporal` WHERE (`ID`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function ValidaProducto($idProd, $idUsuario, $idEmpresa)
        {
            $query = "SELECT CASE WHEN(SELECT ID_PRODUCTO FROM t_factura_temporal WHERE ID_PRODUCTO=" . $idProd . "
  AND t_factura_temporal.ID_USUARIO=" . $idUsuario . " AND t_factura_temporal.ID_EMPRESA=" . $idEmpresa . ") IS NULL THEN ('0')ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';
            foreach ($Campos as $key => $datos) {
                $this->_ExisteProducto = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
        }


        public function TraePagoTemporal($Idusuario)
        {
            $query = "SELECT
            t_pagos_t.ID_F_PAGO,
            t_pagos_t.ID_PAGO_T,
            t_entidades.ID_ENTIDAD,
            VALOR,
            NUMERO,
            t_entidades.NOMBRE_ENTIDAD,
            t_formas_pago.NOMBRE_F_PAGO,
            t_formas_pago.ID_CUENTA
           FROM
            t_pagos_t
            INNER JOIN t_entidades ON t_entidades.ID_ENTIDAD = t_pagos_t.ID_ENTIDAD
            INNER JOIN t_formas_pago ON t_formas_pago.ID_F_PAGO = t_pagos_t.ID_F_PAGO
            WHERE
                t_pagos_t.ESTADO = 1 AND t_formas_pago.REQUIERE_ENTIDAD=1
            AND t_pagos_t.ID_USUARIO =  $Idusuario

            UNION
            #SIN ENTIDADES
            (SELECT
            	t_pagos_t.ID_F_PAGO,
	            t_pagos_t.ID_PAGO_T,
                0 AS ID_ENTIDAD,
                VALOR,
                NUMERO,
                '' AS NOMBRE_ENTIDAD,
                t_formas_pago.NOMBRE_F_PAGO,
                 t_formas_pago.ID_CUENTA
            FROM
                t_pagos_t
            INNER JOIN t_formas_pago ON t_formas_pago.ID_F_PAGO = t_pagos_t.ID_F_PAGO
            WHERE
                t_pagos_t.ESTADO = 1 AND t_formas_pago.REQUIERE_ENTIDAD=0
            AND t_pagos_t.ID_USUARIO =  $Idusuario )";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }
    }