<?php

    class cls_Contabilidad
    {
        //put your code here

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

        public function TraeCuentas($idEmpresa)
        {
            $query = "SELECT
            t_cuentas.ID_CUENTA,
            t_cuentas.CODIGO,
            t_cuentas.NOMBRE,
            t_cuentas.NATURALEZA,
            CASE WHEN t_cuentas.MANEJA_DOC_CRUCE =1 THEN 'SI' ELSE 'NO' END AS MANEJA_DOC_CRUCE,
            CASE WHEN t_cuentas.MANEJA_TERCERO =1 THEN 'SI' ELSE 'NO' END AS MANEJA_TERCERO,
            t_cuentas.ESTADO,
            t_cuentas.USR_REGISTRO,
            t_cuentas.FECHA_REGISTRO,
            t_cuentas.ID_EMPRESA
            FROM
            t_cuentas WHERE t_cuentas.ESTADO=1 AND t_cuentas.ID_EMPRESA=" . $idEmpresa . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function InsertaCuenta($Codigo, $Nombre, $ManejaTer, $ManejaDocCruce, $Naturaleza, $UsrReg, $IdEmpresa)
        {
            $query = "INSERT INTO `t_cuentas`
      (`CODIGO`, `NOMBRE`, `MANEJA_TERCERO`,`MANEJA_DOC_CRUCE`, `NATURALEZA`, `ESTADO`, `USR_REGISTRO`, `FECHA_REGISTRO`, `ID_EMPRESA`)
       VALUES
       ('" . $Codigo . "','" . $Nombre . "'," . $ManejaTer . "," . $ManejaDocCruce . ",'" . $Naturaleza . "',1," . $UsrReg . ",now()," . $IdEmpresa . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function ActualizaCuenta($id, $Codigo, $Nombre, $ManejaTer, $ManejaDocCruce, $Naturaleza)
        {
            $query = "UPDATE `t_cuentas`
       SET `CODIGO`='" . $Codigo . "',`NOMBRE`='" . $Nombre . "', `MANEJA_TERCERO`=" . $ManejaTer . ",`MANEJA_DOC_CRUCE`=" . $ManejaDocCruce . "
        ,`NATURALEZA`='" . $Naturaleza . "'  WHERE (`ID_CUENTA`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function TraeDatosCuenta($id)
        {
            $query = "SELECT
t_cuentas.ID_CUENTA,
t_cuentas.CODIGO,
t_cuentas.NOMBRE,
t_cuentas.MANEJA_TERCERO,
t_cuentas.MANEJA_DOC_CRUCE,
t_cuentas.NATURALEZA,
t_cuentas.ESTADO,
t_cuentas.USR_REGISTRO,
t_cuentas.FECHA_REGISTRO,
t_cuentas.ID_EMPRESA
FROM
t_cuentas WHERE t_cuentas.ID_CUENTA=" . $id . "";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function EliminarCuenta($id)
        {
            $query = "UPDATE `t_cuentas` SET `ESTADO`=0 WHERE (`ID_CUENTA`=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function ValidaCodigo($Cod)
        {

            $query = "SELECT CASE WHEN(SELECT CODIGO FROM t_cuentas WHERE CODIGO='" . $Cod . "')IS NULL THEN ('0')ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
            return $resulset->fetchAll();
        }

        public function ValidaCodigoEditar($Cod, $Id)
        {

            $query = "SELECT CASE WHEN(SELECT CODIGO
		FROM
		t_cuentas WHERE CODIGO='" . $Cod . "' AND ID_CUENTA <>" . $Id . ")IS NULL THEN ('0')ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCodigo = ($datos[0]);
            }
            // var_dump($datos);
            return $datos;
            return $resulset->fetchAll();
        }


    }

?>
