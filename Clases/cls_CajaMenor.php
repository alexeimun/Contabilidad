<?php

    class cls_CajaMenor
    {
        public $_Consecutivo;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function TraeParametrosCajaMenor($idEmpresa)
        {
            $query = "SELECT
		 CONSECUTIVO FROM t_documentos 
		 WHERE TIPO_INTERNO='RECIBO_CAJA_MENOR' AND ID_EMPRESA=" . $idEmpresa;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            $this->_Consecutivo = $Campos[0][0];
        }
    }

?>