<?php

    class cls_Inventarios
    {
        public $_ConsecutivoGastos;
        public $_ConsecutivoEgresos;
        public $_IdCuentaEgresos;
        public $_IdCuentaConsumo;
        public $_IdParam;
        public $_IdCuentaGastos;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function TraeEgresos($idEmpresa)
        {
            $resulset = $this->_DB->Query("CALL TraerGastos($idEmpresa)");
            return $resulset->fetchAll();
        }

        public function TraeConsecutivoGastos($idEmpresa)
        {
            $query = "SELECT   CONSECUTIVO,ID_DOCUMENTO FROM t_documentos WHERE TIPO_INTERNO='GASTOS' AND ID_EMPRESA= $idEmpresa";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $this->_ConsecutivoGastos = $Campos[0][0];
            $this->_IdParam = $Campos[0][1];
        }

        public function TraeConsecutivoEgresos($idEmpresa)
        {
            $query = "SELECT   CONSECUTIVO FROM t_documentos WHERE TIPO_INTERNO='EGRESOS' AND ID_EMPRESA= $idEmpresa";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $this->_ConsecutivoEgresos = $Campos[0][0];
        }

        public function TraeCuentas($idEmpresa)
        {
            $query = "SELECT  ID_CUENTA FROM  t_documentos
        WHERE (TIPO_INTERNO='GASTOS' OR TIPO_INTERNO='IMPUESTO_CONSUMO') AND   ID_EMPRESA=$idEmpresa";

            $resulset = $this->_DB->Query($query);

            $Campos = $resulset->fetchAll();
            $this->_IdCuentaGastos = $Campos[0][0];
            $this->_IdCuentaConsumo = $Campos[1][0];
        }

        public function TraeGastosTemp($IdUsuario)
        {
            $query = "SELECT *,
           t_conceptos.ID_CONCEPTO,
           if(t_gasto_t.POR='SV','Servicios','Bienes') AS  POR,
           if(t_gasto_t.FORMA_PAGO='CO','Contado','Crédito') AS  FORMA_PAGO,
           IF(t_conceptos.CONCEPTO=1,'Ingresos','Gastos') AS CONCEPTO

       FROM t_gasto_t


       INNER JOIN t_conceptos ON t_conceptos.ID_CONCEPTO=t_gasto_t.ID_CONCEPTO

         WHERE ID_USUARIO=$IdUsuario";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function  InsertaGastoTemp($IdConcepto, $IdUsuario, $FormaPago, $Por, $Detalle, $ValorBase, $Iva, $ImpuConsumo)
        {
            $query = "INSERT INTO `t_gasto_t`
        (`ID_CONCEPTO`,`ID_USUARIO`,`FORMA_PAGO`,`POR`,`DETALLE`,`VALOR_BASE`,`IVA`,`IMPU_CONSUMO`)
        VALUES  ($IdConcepto, $IdUsuario,' $FormaPago',' $Por',' $Detalle', $ValorBase, $Iva, $ImpuConsumo)";

            return $this->_DB->Exec($query) > 0;
        }

        public function EliminarGasto($id)
        {
            return $this->_DB->Exec("DELETE FROM `t_gasto_t` WHERE `ID_GASTO_TEMP`=$id") > 0;
        }

        public function TraeCostoVenta($Mes,$Ano)
        {
            $q1 = "(SELECT sum(VALOR) from t_control_inventario where ( $Ano = year(FECHA) AND $Mes =month(FECHA)) AND (TIPO_DOC='I' OR TIPO_DOC='C'))";
            $q2 = "(SELECT sum(VALOR) from t_control_inventario where  ($Ano = year(FECHA) AND $Mes=month(FECHA)) AND ( TIPO_DOC='D' OR TIPO_DOC='V' OR TIPO_DOC='F'))";

            $resulset = $this->_DB->Query("SELECT if($q1 IS NOT NULL,$q1,0 )-if($q2 IS NOT NULL,$q2,0)");
            $Escalar = $resulset->fetchAll();
            return $Escalar[0][0];
        }

        public function InsertaMovimientoInventario($IdConcepto, $Valor, $TipoDoc, $TipoMov, $Fecha)
        {
            $query = "INSERT INTO  t_control_inventario
       (`ID_CONCEPTO`, `VALOR`, `TIPO_DOC`, `TIPO_MOV`, `FECHA`)
       VALUES
       ( $IdConcepto ,  $Valor, '" . $TipoDoc . "','" . $TipoMov . "','" . $Fecha . "')";

            return $this->_DB->Exec($query) > 0;
        }
    }
