<?php

    class cls_Egresos
    {
        public $_ConsecutivoGastos;
        public $_ConsecutivoEgresos;
        public $_IdCuentaEgresos;
        public $_IdCuentaConsumo;
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
            $query = "SELECT   CONSECUTIVO FROM t_documentos WHERE TIPO_INTERNO='GASTOS' AND ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $this->_ConsecutivoGastos = $Campos[0][0];
        }

        public function TraeConsecutivoEgresos($idEmpresa)
        {
            $query = "SELECT   CONSECUTIVO FROM t_documentos WHERE TIPO_INTERNO='EGRESOS' AND ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $this->_ConsecutivoEgresos = $Campos[0][0];
        }

        public function TraeCuentas($idEmpresa)
        {
            $query = "SELECT  ID_CUENTA FROM  t_documentos
        WHERE (TIPO_INTERNO='GASTOS' OR TIPO_INTERNO='IMPUESTO_CONSUMO') AND   ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);

            $Campos = $resulset->fetchAll();
            $this->_IdCuentaGastos = $Campos[0][0];
            $this->_IdCuentaConsumo = $Campos[1][0];
        }
    }