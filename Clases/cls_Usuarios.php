<?php

    class cls_Usuarios
    {

        public $_prueba;
        public $_ExisteCorreo;
        public $_ExisteDocumento;
        public $_CantUsuarios;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        public function get_CantUsuarios()
        {
            return $this->_CantUsuarios;
        }

        public function set_CantUsuarios($_CantUsuarios)
        {
            $this->_CantUsuarios = $_CantUsuarios;
        }

        public function get_ExisteCorreo()
        {
            return $this->_ExisteCorreo;
        }

        public function set_ExisteCorreo($_ExisteCorreo)
        {
            $this->_ExisteCorreo = $_ExisteCorreo;
        }

        public function get_ExisteDocumento()
        {
            return $this->_ExisteDocumento;
        }

        public function set_ExisteDocumento($_ExisteDocumento)
        {
            $this->_ExisteDocumento = $_ExisteDocumento;
        }

        public function validarCredenciales($login, $Pass)
        {

            $query = "SELECT * FROM t_credenciales WHERE EMAIL='" . $login . "' AND PASSWORD='" . $Pass . "'";

            $resulset = $this->_DB->Query($query);
            $credencial = $resulset->fetchAll();

            if (isset($credencial[0])) {
                switch ($credencial[0][3]) {
                    case 0 :
                        $query = "SELECT  t_usuarios.ID_USUARIO,
					t_usuarios.NOMBRE AS NOMBRE_USUARIO,
					 t_credenciales.NIVEL  AS NIVEL,
		             t_credenciales.EMAIL AS EMAIL,
                     t_credenciales.PASSWORD AS PASSWORD,
					t_usuarios.FECHA_REGISTRO,
					t_usuarios.RAIZ,
					t_empresas.NOMBRE AS NOMBRE_EMPRESA,
					t_empresas.CANT_USUARIOS,
					t_regimenes.NOMBRE as NOMBRE_REGIMEN,
					concat('../../Formularios/Empresas/',t_empresas.LOGO) AS LOGO_EMPRESA,
					t_empresas.ID_EMPRESA,
					t_empresas.NIT,
					t_empresas.TELEFONO AS TELEFONO_EMPRESA,
					t_empresas.DIRECCION AS DIRECCION_EMPRESA,
		            t_vendedor.ID_VENDEDOR,
		            t_vendedor.ESTADO AS Estado_vendedor
				
					FROM
					t_usuarios
					INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL=  t_usuarios.ID_CREDENCIAL
					INNER JOIN t_empresas ON t_usuarios.ID_EMPRESA = t_empresas.ID_EMPRESA
		            INNER JOIN t_vendedor ON t_empresas.ID_VENDEDOR= t_vendedor.ID_VENDEDOR
		            INNER JOIN t_regimenes ON t_regimenes.ID_REGIMEN= t_empresas.ID_REGIMEN

					 WHERE t_usuarios.ID_CREDENCIAL= '" . $credencial[0][0] . "' AND t_usuarios.ESTADO=1 AND t_empresas.ESTADO=1 AND t_vendedor.ESTADO=1";
                        break;
                    case 1 :
                        $query = "SELECT
                     t_credenciales.EMAIL AS EMAIL,
					t_credenciales.PASSWORD AS PASS,
					t_credenciales.NIVEL  AS NIVEL,
			        t_vendedor.ID_VENDEDOR,
			        t_vendedor.CANT_EMPRESAS,
			        t_vendedor.ID_ADMIN,
				    t_vendedor.NOMBRE AS NOMBRE_VENDEDOR,
                      concat('../../Formularios/Vendedores/',t_vendedor.LOGO) AS LOGO_VENDEDOR

                        FROM t_vendedor
                        
                        INNER JOIN t_credenciales ON t_vendedor.ID_CREDENCIAL=t_credenciales.ID_CREDENCIAL
                        WHERE t_vendedor.ID_CREDENCIAL= '" . $credencial[0][0] . "'  AND t_vendedor.ESTADO=1;";

                        break;

                    case 2 :
                        $query = "SELECT
                      t_admin.ID_ADMIN,
                      t_admin.ID_CREDENCIAL,
                       t_admin.NOMBRE AS NOMBRE_ADMIN,
                       t_credenciales.EMAIL AS EMAIL,
                       t_credenciales.PASSWORD AS PASS,
                       t_credenciales.NIVEL  AS NIVEL

                        FROM t_admin
                       
                        INNER JOIN t_credenciales ON t_admin.ID_CREDENCIAL=t_credenciales.ID_CREDENCIAL
                        WHERE t_admin.ID_CREDENCIAL= '" . $credencial[0][0] . "'  AND t_admin.ESTADO=1;";

                        break;
                }
            }

            $resulset = $this->_DB->Query($query);
            $array = $resulset->fetchAll();
            if ($array != null) {

                session_start();
                $_SESSION['login'] = $array;

                return true;
            } else {
                return FALSE;
            }
        }

        public function TraeDatosUsuarios($IdUsuario)
        {
            $query = "SELECT *,
		t_credenciales.EMAIL AS EMAIL,
		t_usuarios.NOMBRE AS NOMBRE_USUARIO
            FROM t_usuarios
            INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL=t_usuarios.ID_CREDENCIAL
            
             WHERE t_usuarios.ID_USUARIO=" . $IdUsuario;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function EliminarUsuario($id)
        {
            $query = "UPDATE `t_usuarios` SET `ESTADO`=0 WHERE (`ID_USUARIO`=" . $id . ")";

            return $this->_DB->Exec($query) > 0;
        }

        public function TraeUsuariosEmpresa($IdEmpresa)
        {
            $query = "SELECT *,
            t_credenciales.EMAIL

            FROM t_usuarios 
            INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL= t_usuarios.ID_CREDENCIAL
            WHERE t_usuarios.ID_EMPRESA=" . $IdEmpresa . " AND t_usuarios.ESTADO=1 ";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function TraeUsuariosVendedor()
        {
            $query = "SELECT *,
            t_credenciales.EMAIL,
            t_credenciales.PASSWORD

            FROM t_vendedor
            INNER JOIN t_credenciales ON t_credenciales.ID_CREDENCIAL= t_usuarios.ID_CREDENCIAL
            WHERE  t_vendedor.ESTADO=1   AND t_credenciales.NIVEL=1";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function InsertaUsuario($Nombre, $Documento, $Email, $Clave, $IdEmpresa)
        {
            $query = "INSERT INTO t_credenciales
         (EMAIL,PASSWORD,NIVEL) VALUES ('" . $Email . "','" . $Clave . "',0);
		
		INSERT INTO `t_usuarios` 
    (`NOMBRE`, `DOCUMENTO`,`ID_EMPRESA`,`ESTADO`, `FECHA_REGISTRO`, `ID_CREDENCIAL`)
     VALUES ('" . $Nombre . "', '" . $Documento . "', " . $IdEmpresa . ",1, now(),
     (SELECT ID_CREDENCIAL FROM t_credenciales WHERE EMAIL='" . $Email . "'))";

            return $this->_DB->Exec($query) > 0;
        }

        public function InsertaUsuarioDefault($nitEmpresa, $Clave, $identi)
        {
            $user = "usuario" . ($nitEmpresa + $Clave);
            $query = "INSERT INTO t_credenciales
         (EMAIL,PASSWORD,NIVEL) VALUES ('" . $user . "','" . $Clave . "',0);
    
		INSERT INTO  t_usuarios 
      (`NOMBRE`,`DOCUMENTO`, `ID_EMPRESA`, `ESTADO`, FECHA_REGISTRO,`ID_CREDENCIAL`,`RAIZ`)
     VALUES ('Usuario 1', '" . $identi . "',(SELECT ID_EMPRESA FROM t_empresas WHERE NIT='" . $nitEmpresa . "'),1, now(),
     (SELECT ID_CREDENCIAL FROM t_credenciales WHERE EMAIL='" . $user . "'),1)";

            if ($this->_DB->Exec($query) > 0)
                return true;
            else
                return false;
        }


        public function InsertaUsuarioAdmin($Nombre, $Documento, $Email, $Clave, $IdEmpresa)
        {
            $query = "INSERT INTO t_credenciales
		(EMAIL,PASSWORD,NIVEL)  VALUES ('" . $Email . "','" . $Clave . "',1);
		
            INSERT INTO  t_usuarios
        (`NOMBRE`, `DOCUMENTO`, `ID_EMPRESA`, `ESTADO`, `FECHA_REGISTRO`,`ID_CREDENCIAL`)
         VALUES ('" . $Nombre . "', '" . $Documento . "'," . $IdEmpresa . ",1, now(),
         (SELECT ID_CREDENCIAL FROM t_credenciales WHERE EMAIL='" . $Email . "'))";

            if ($this->_DB->Exec($query) > 0)
                return true;
            else
                return false;
        }

        public function ActualizaUsuario($id, $Nombre, $Documento, $Email, $Clave)
        {
            $query = "UPDATE  t_usuarios
       SET `NOMBRE`='" . $Nombre . "', `DOCUMENTO`='" . $Documento . "'  WHERE (`ID_USUARIO`=" . $id . ");
       
       UPDATE t_credenciales SET `EMAIL`='" . $Email . "', `PASSWORD`='" . $Clave . "' WHERE ID_CREDENCIAL =
       (SELECT ID_CREDENCIAL FROM t_usuarios WHERE ID_USUARIO=" . $id . ")";

            if ($this->_DB->Exec($query) > 0) return true;
            else  return false;
        }


        public function ValidaCorreo($Email)
        {

            $query = "SELECT CASE WHEN(SELECT EMAIL
		FROM t_credenciales WHERE EMAIL='" . $Email . "')IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';
            foreach ($Campos as $key => $datos) {
                $this->_ExisteCorreo = ($datos[0]);
            }
            return $datos;
        }

        public function CantidadUsuarios($IdEmpresa)
        {

            $query = "SELECT COUNT(*) FROM t_credenciales
        INNER JOIN t_usuarios ON t_usuarios.ID_CREDENCIAL=t_credenciales.ID_CREDENCIAL
         WHERE t_usuarios.ID_EMPRESA=" . $IdEmpresa;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';

            foreach ($Campos as $key => $datos) {
                $this->_CantUsuarios = ($datos[0]);
            }
            return $datos;
        }

        public function ValidaCorreoEditar($Email, $idUsuario)
        {

            $query = "SELECT CASE WHEN (SELECT EMAIL FROM
                       t_credenciales WHERE EMAIL='" . $Email . "' AND
                       
                       (SELECT EMAIL FROM t_credenciales WHERE ID_CREDENCIAL =
                       (SELECT ID_CREDENCIAL FROM t_usuarios WHERE ID_USUARIO='" . $idUsuario . "'))  <> '" . $Email . "')
                       IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';

            foreach ($Campos as $key => $datos) {
                $this->_ExisteCorreo = ($datos[0]);
            }
            return $datos;
        }

        public function ValidaDocumento($Doc)
        {

            $query = "SELECT CASE WHEN( SELECT DOCUMENTO
        FROM t_usuarios WHERE DOCUMENTO='" . $Doc . "')
        IS NULL THEN ('0') ELSE ('1') END";
            $datos = '';
            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            foreach ($Campos as $key => $datos) {
                $this->_ExisteDocumento = ($datos[0]);
            }
            return $datos;
        }

        public function ValidaDocumentoEditar($Doc, $idUsuario)
        {

            $query = "SELECT CASE WHEN(SELECT DOCUMENTO
            FROM t_usuarios WHERE DOCUMENTO='" . $Doc . "'  AND ID_USUARIO <>" . $idUsuario . ")IS NULL THEN ('0') ELSE ('1') END";

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            $datos = '';

            foreach ($Campos as $key => $datos) {
                $this->_ExisteDocumento = ($datos[0]);
            }
            return $datos;
            // var_dump($datos);
        }

        //para cuando insertamos un usuario nuevo
        public function traeModulosPadres()
        {
            $query = "SELECT
                t_modulo.ID_MODULO,
                t_modulo.NOMBRE
                FROM
                t_modulo WHERE (t_modulo.PADRE IS NULL ) AND VISIBLE=1 ";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function traeModulosAdmin()
        {

            $query = "SELECT
                t_modulo.ID_MODULO,
                t_modulo.NOMBRE
                FROM
                t_modulo WHERE (t_modulo.PADRE IS NULL) AND VISIBLE=1
                ORDER BY ORDEN";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function traeModulosHijos($idPadre)
        {
            $query = "SELECT
                t_modulo.ID_MODULO,
                t_modulo.NOMBRE
                FROM
                t_modulo WHERE t_modulo.PADRE=" . $idPadre . "  AND VISIBLE=1 ORDER BY ORDEN";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        //trae los permisos del usuario para modificar
        public function traeModulosXUsuario($Id, $IdMod)
        {
            $query = "SELECT
                t_permisos.ID_PERMISO,
                t_permisos.ID_USUARIO,
                t_permisos.ID_MODULO,
                t_permisos.SI_O_NO,
                t_permisos.USR_REGISTRO,
                t_permisos.FECHA_REGISTRO,
                t_modulo.NOMBRE
                FROM
                t_permisos
                INNER JOIN t_modulo ON t_modulo.ID_MODULO = t_permisos.ID_MODULO
                WHERE t_permisos.ID_USUARIO='" . $Id . "' AND  t_modulo.PADRE=" . $IdMod . " AND VISIBLE=1 ORDER BY ORDEN";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function traePermisos($identi)
        {
            $query = "SELECT
          
                t_permisos.ID_MODULO,
                t_permisos.SI_O_NO

                FROM
                t_permisos
                INNER JOIN t_modulo ON t_modulo.ID_MODULO = t_permisos.ID_MODULO
                WHERE t_permisos.ID_USUARIO='" . $identi . "' AND   VISIBLE=1 ORDER BY ID_MODULO";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function traeModulosXUsuarioAdmin($identi)
        {
            $query = "SELECT
                t_permisos.ID_PERMISO,
                t_permisos.ID_USUARIO,
                t_permisos.ID_MODULO,
                t_permisos.SI_O_NO,
                t_permisos.USR_REGISTRO,
                t_permisos.FECHA_REGISTRO,
                t_modulo.NOMBRE
                FROM
                t_permisos
                INNER JOIN t_modulo ON t_modulo.ID_MODULO = t_permisos.ID_MODULO
                WHERE t_permisos.ID_USUARIO='" . $identi . "'  AND VISIBLE=1 ORDER BY ORDEN";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function registraPermisos($doc, $mod, $per, $UsrReg)
        {
            $query = "INSERT INTO `t_permisos`
		
		(`ID_USUARIO`, `ID_MODULO`, `SI_O_NO`,`USR_REGISTRO`,`FECHA_REGISTRO`)
		
		VALUES ((SELECT ID_USUARIO FROM t_usuarios WHERE DOCUMENTO='" . $doc . "'), '" . $mod . "','" . $per . "','" . $UsrReg . "', now())";

            return $this->_DB->Exec($query) > 0;
        }

        public function actualizaPermisos($usu, $mod, $per)
        {
            $query = "UPDATE  t_permisos SET  SI_O_NO=$per
           , FECHA_REGISTRO=now()  WHERE (ID_USUARIO='" . $usu . "' AND ID_MODULO='" . $mod . "')";

         return $this->_DB->Exec($query) > 0;
        }

        public function TienePermiso($nombre, $idUser)
        {
            $mod = $this->TraeModulo($nombre);
            if ($mod != null)
                return !$this->Permiso($mod, $idUser);
            else return false;
        }

        public function TraeModulo($nombre)
        {
            $this->Retocar($nombre);
            $query = "SELECT ID_MODULO  FROM t_modulo WHERE t_modulo.NOMBRE='" . $nombre . "'";
            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            return $Campos[0][0];
        }

        private function Retocar(&$nombre)
        {
            $nombre = str_replace('.php', '', basename($nombre));
            $link = $nombre[0];
            for ($i = 1; $i < strlen($nombre); $i ++) {
                if (ctype_upper($nombre[$i])) $link .= ' ' . $nombre[$i];
                else $link .= $nombre[$i];
            }
            $nombre = $link;
        }

        public function Permiso($idmodulo, $iduser)
        {
            $query = "SELECT  SI_O_NO FROM t_permisos WHERE t_permisos.ID_USUARIO=$iduser AND ID_MODULO=$idmodulo";
            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();
            return $Campos[0][0] == 1;
        }
    }