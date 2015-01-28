<?php

    class cls_Empresas
    {
        //put your code here

        public $_prueba;
        public $_CantEmpresas;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function get_CantEmpresas()
        {
            return $this->_CantEmpresas;
        }

        public function set_CantEmpresas($_CantEmpresas)
        {
            $this->_CantEmpresas = $_CantEmpresas;
        }

        public function TraeEmpresas($IdVendedor)
        {
            $query = "SELECT *,
            CASE WHEN t_empresas.ESTADO=1 THEN 'Activa' ELSE 'Inactiva' END AS ESTADO_EMPRESA,
            t_credenciales.EMAIL AS EMAIL

            FROM  t_empresas
             INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL = t_empresas.ID_CREDENCIAL
             WHERE t_empresas.ID_VENDEDOR='" . $IdVendedor . "'";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function ActivaOdesactivaEmpresa($id, $a)
        {

            if ($a == 'd') {
                $query = "UPDATE `t_empresas` SET `ESTADO`=0 WHERE (`ID_EMPRESA`=" . $id . ")";
            } else {
                $query = "UPDATE t_empresas SET `ESTADO`=1 WHERE (`ID_EMPRESA`=" . $id . ")";
            }

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function TraeDatosEmpresa($id)
        {
            $query = "SELECT *,
		t_credenciales.EMAIL AS EMAIL

		FROM t_empresas
		INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL=t_empresas.ID_CREDENCIAL
		WHERE t_empresas.ESTADO=1 AND ID_EMPRESA=" . $id;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeInfoEmpresa($id)
        {
            $query = "SELECT *,
        t_credenciales.EMAIL AS EMAIL

        FROM t_empresas
        INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL=t_empresas.ID_CREDENCIAL
        WHERE  t_empresas.ID_EMPRESA=" . $id;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function InsertaEmpresa($Nombre, $Nit, $Direccion, $Telefono, $Email, $CantUsuarios, $id_vendedor)
        {
            $query = "INSERT INTO t_credenciales
         (EMAIL,PASSWORD,NIVEL) VALUES ('" . $Email . "','contasistin',1);
		
		INSERT INTO t_empresas
       (`NOMBRE`, `LOGO`, `NIT`, `DIRECCION`, `TELEFONO`, `CANT_USUARIOS`, `ESTADO`, `FECHA_REGISTRO`, `ID_VENDEDOR`,
       `ID_CREDENCIAL`)
       
        VALUES ('" . $Nombre . "', 'logoDefault.png', '" . $Nit . "', '" . $Direccion . "', '" . $Telefono . "', '" . $CantUsuarios . "',1,
        now(),'" . $id_vendedor . "',(SELECT ID_CREDENCIAL FROM t_credenciales WHERE EMAIL = '" . $Email . "'))";;

            $query2 = "INSERT INTO t_credenciales
         (EMAIL,PASSWORD,NIVEL) VALUES ('" . $Email . "','contasistin',1)";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaEmpresa($Id, $Nombre, $Nit, $Direccion, $Telefono, $Email, $Logo)
        {
            $query = "UPDATE t_credenciales SET EMAIL=  '" . $Email . "' WHERE ID_CREDENCIAL=(SELECT ID_CREDENCIAL FROM t_empresas WHERE
        ID_EMPRESA='" . $Id . "');
		
		UPDATE `t_empresas` SET `NOMBRE`='" . $Nombre . "', `LOGO`='" . $Logo . "', `NIT`='" . $Nit . "', `DIRECCION`='" . $Direccion . "',
       `TELEFONO`='" . $Telefono . "'  WHERE (`ID_EMPRESA`=" . $Id . ")";

            $this->_DB->Exec($query);
            return true;

        }

        public function EliminaEmpresa($id)
        {
            $query = "DELETE FROM `t_empresas` WHERE (`ID_EMPRESA`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizarCantUsuarios($Cant, $IdEmpresa)
        {
            $query = "UPDATE  `t_empresas` SET CANT_USUARIOS=" . $Cant . " WHERE (`ID_EMPRESA`=" . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function CantidadEmpresas($idVendedor)
        {

            $query = "SELECT COUNT(*) FROM t_credenciales
        INNER JOIN t_empresas ON t_empresas.ID_CREDENCIAL=t_credenciales.ID_CREDENCIAL
         WHERE  t_empresas.ESTADO =1 AND t_empresas.ID_VENDEDOR=" . $idVendedor;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_CantEmpresas = ($datos[0][0]);
            }
        }

    }

?>
