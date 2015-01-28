<?php

    class cls_Vendedores
    {
        //put your code here

        public $_prueba;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function TraeVendedores()
        {
            $query = "SELECT *,
            CASE WHEN t_vendedor.ESTADO=1 THEN 'Activa' ELSE 'Inactiva' END AS ESTADO_VENDEDOR,
            t_credenciales.EMAIL AS EMAIL

            FROM  t_vendedor
             INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL = t_vendedor.ID_CREDENCIAL";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function ActivaOdesactivaVendedor($id, $a)
        {

            if ($a == 'd') {
                $query = "UPDATE `t_vendedor` SET `ESTADO`=0 WHERE (`ID_VENDEDOR`=" . $id . ")";
            } else {
                $query = "UPDATE `t_vendedor` SET `ESTADO`=1 WHERE (`ID_VENDEDOR`=" . $id . ")";
            }

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function TraeDatosVendedor($id)
        {
            $query = "SELECT *,
        t_credenciales.EMAIL AS EMAIL

        FROM t_vendedor
        INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL=t_vendedor.ID_CREDENCIAL
        WHERE t_vendedor.ESTADO=1 AND ID_VENDEDOR=" . $id;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeUsuariosVendedor($IdVendedor)
        {
            $query = "SELECT *,
            t_credenciales.EMAIL

            FROM t_empresas 
            INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL= t_empresas.ID_CREDENCIAL
            WHERE t_empresas.ID_VENDEDOR=" . $IdVendedor . " AND t_empresas.ESTADO=1";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeInfoVendedor($id)
        {
            $query = "SELECT *,
        t_credenciales.EMAIL AS EMAIL

        FROM t_vendedor
        INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL=t_vendedor.ID_CREDENCIAL
        WHERE  t_vendedor.ID_VENDEDOR=" . $id;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function InsertaVendedor($Nombre, $Documento, $Telefono, $Email, $CantEmpresas, $idAdmin)
        {
            $query = "INSERT INTO t_credenciales
         (EMAIL,PASSWORD,NIVEL) VALUES ('" . $Email . "','contasistin',1);
        
        INSERT INTO t_vendedor
       (`NOMBRE`, `LOGO`, `DOCUMENTO`, `CANT_EMPRESAS`, `TELEFONO`, `ESTADO`, `FECHA_REGISTRO`, `ID_ADMIN`,
       `ID_CREDENCIAL`)
       
        VALUES ('" . $Nombre . "', 'logoDefault.png', '" . $Documento . "', '" . $CantEmpresas . "', '" . $Telefono . "',1,
        now(),'" . $idAdmin . "',(SELECT ID_CREDENCIAL FROM t_credenciales WHERE EMAIL = '" . $Email . "'))";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaVendedor($Id, $Nombre, $Documento, $Telefono, $Email, $Logo)
        {
            $query = "UPDATE t_credenciales SET EMAIL=  '" . $Email . "' WHERE ID_CREDENCIAL=(SELECT ID_CREDENCIAL FROM t_vendedor WHERE
        ID_VENDEDOR ='" . $Id . "');
        
        UPDATE `t_vendedor` SET `NOMBRE`='" . $Nombre . "', `LOGO`='" . $Logo . "', `DOCUMENTO`='" . $Documento . "',
       `TELEFONO`='" . $Telefono . "'  WHERE (`ID_VENDEDOR`=" . $Id . ")";

            $this->_DB->Exec($query);
            return true;

        }

        public function EliminaVendedor($id)
        {
            $query = "DELETE FROM `t_vendedor` WHERE (`ID_VENDEDOR`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizarCantEmpresas($Cant, $IdVendedor)
        {
            $query = "UPDATE  `t_vendedor` SET CANT_EMPRESAS=" . $Cant . " WHERE (`ID_VENDEDOR`=" . $IdVendedor . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }


    }

?>