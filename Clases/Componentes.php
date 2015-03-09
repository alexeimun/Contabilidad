<?php
    include_once 'cls_Factura.php';
    include_once '../../Css/css.php';

    class Componentes
    {
        private $_RequiereEntidad;
        private $_RequiereNumero;
        private $Factura;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }

        /**
         * Trae el componente que permite al usuario seleccionar
         * y pagar con su forma de pago
         */
        public function FormasPagos()
        {
            $cmbEntidad = '<option value ="0">-- Seleccione Entidad --</option>';
            foreach ($this->TraeEntidades($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
                $cmbEntidad .= '<option style="text-align:left;" value ="' . $valor['ID_ENTIDAD'] . '">' . $valor['NOMBRE_ENTIDAD'] . '</option>';

            $tabla = '<table style="width: 80%;color: #33373d;"> <tr>';

            $this->TraeDatosPago(($_GET['id']));

            if ($this->_RequiereEntidad == 1) {
                $tabla .= '<td  style="text-align: center;"><br>Entidad</td>
                    <td style="padding-left: 10px;text-align: left;">
                 <br>  <select style="width: 190px;"  id="cmbEntidad" name="cmbEntidad" class="chosen-select" >
                     ' . $cmbEntidad . '  </select></td>';
            }

            if ($this->_RequiereNumero == 1) {
                $tabla .= ' <td style="text-align: center;"><br>Número</td>
                    <td style="padding-left: 10px;text-align: left;">
                 <br>  <input style="width: 150px;"  name="txtNumero" id="txtNumero"   type="text" ></td>';
            }

            $tabla .= '  <td style="text-align: left;"><br>Valor</td>
                    <td style="padding-left: 10px;text-align: left;">
                 <br>  <input style="width: 150px;"  name="txtValor" id="txtValor"  type="text" required > </td></tr>
      </table>';

            echo $tabla;
        }

        public function TraeEntidades($IdEmpresa)
        {
            $query = "SELECT * FROM t_entidades WHERE ESTADO=1 AND ID_EMPRESA=$IdEmpresa";

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        private function TraeDatosPago($idPago)
        {
            $query = "SELECT  REQUIERE_ENTIDAD, REQUIERE_NUMERO FROM t_formas_pago WHERE ID_F_PAGO=" . $idPago;

            $resulset = $this->_DB->Query($query);
            $Campos = $resulset->fetchAll();

            $this->_RequiereEntidad = $Campos[0][0];
            $this->_RequiereNumero = $Campos[0][1];
        }

        public function TablaPagos()
        {
            $this->Factura = new cls_Factura();
            $tabla = '<table class="table" style="width:80%;">
            <th style="text-align:left;">Forma de pago</th>
            <th style="text-align:left;">Entidad</th>
            <th style="text-align:left;">Número</th>
            <th style="text-align:left;">Valor</th>
            <th style="text-align:right;">Acción</th>';

            $Totalpago = 0;
            foreach ($this->Factura->TraePagoTemporal($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
                $tabla .= '<tr><td style="text-align:left;">' . $valor['NOMBRE_F_PAGO'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE_ENTIDAD'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['NUMERO'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR'], 0, '', '.') . '</td>';
                $tabla .= '<td style="text-align:right;">
          <a onclick="EliminarPago(' . $valor['ID_PAGO_T'] . '); return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
                $Totalpago += $valor['VALOR'];
            }
            $_SESSION['TOTAL2'] = $Totalpago;
            $tabla .= '<tr><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Totalpago, 0, '', '.') . '</b></td><td></td></tr>';
            $tabla .= '</table>';

            echo $tabla;
        }

        public function EliminarPagoTemporal($id)
        {
            return $this->_DB->Exec("DELETE FROM `t_pagos_t` WHERE `ID_PAGO_T`= $id") > 0;
        }

        public function  ValidaAgregaPago($idPago, $Valor, $IdEntidad, $Numero, $Idusuario)
        {
            $this->TraeDatosPago($idPago);
            $mess = '';
            if ($this->_RequiereEntidad == "1" && $_GET['entidad'] == "0")
                $mess = '<span class="Error">POR FAVOR SELECCIONE UNA ENTIDAD</span><br><br><br>';
            else if ($this->_RequiereNumero == "1" && $_GET['numero'] == "")
                $mess = '<span class="Error">POR FAVOR DIGITE EL NUMERO</span><br><br><br>';
            else if ($_GET['valor'] == "" || !is_numeric($_GET['valor']))
                $mess = '<span class="Error">POR FAVOR DIGITE UN VALOR</span><br><br><br>';
            else
                try {
                    $this->InsertaPagoTemporal($idPago, $Valor, $IdEntidad, $Numero, $Idusuario);
                } catch (Exception $ex) {
                    echo $mess = $ex;
                }

            echo $mess;
        }

        private function  InsertaPagoTemporal($idPago, $Valor, $IdEntidad, $Numero, $Idusuario)
        {
            $query = "INSERT INTO `t_pagos_t`
        (`ID_ENTIDAD`, `VALOR`,`ID_F_PAGO`,`NUMERO`,`ID_USUARIO`,`ESTADO`)
        VALUES  (" . $IdEntidad . ", '" . $Valor . "','" . $idPago . "','" . $Numero . "'," . $Idusuario . ",1)";

            return $this->_DB->Exec($query) > 0;
        }

        public function  InsertaEntidad($Nombre, $Tipo, $IdUsuario, $IdEmpresa)
        {
            $query = "INSERT INTO `t_entidades`
        (NOMBRE_ENTIDAD,TIPO,ESTADO,USR_REGISTRO,ID_EMPRESA)
        VALUES  ('" . $Nombre . "', '" . $Tipo . "',1," . $IdUsuario . "," . $IdEmpresa . ")";

            return $this->_DB->Exec($query) > 0;
        }

        public function TraeEntidad($IdEntidad)
        {
            $query = "SELECT * FROM t_entidades WHERE ESTADO=1 AND ID_ENTIDAD=" . $IdEntidad;

            $resulset = $this->_DB->Query($query);
            return $resulset->fetchAll();
        }

        public function EliminarEntidad($IdEntidad)
        {
            $query = "UPDATE `t_entidades` SET `ESTADO`=0 WHERE (`ID_ENTIDAD`=" . $IdEntidad . ")";

            return $this->_DB->Exec($query) > 0;
        }

        public function ActualizaEntidad($Nombre, $Tipo, $UsrReg, $IdEntidad)
        {
            $query = "UPDATE `t_entidades` SET `NOMBRE_ENTIDAD`='" . $Nombre . "', `TIPO`='" . $Tipo . "',
            `USR_REGISTRO`=" . $UsrReg . ", `FECHA_REGISTRO`=now()
            WHERE (`ID_ENTIDAD`=" . $IdEntidad . ")";

            return $this->_DB->Exec($query) > 0;
        }

        public function OrdenaCuentas(&$Cuentas = [])
        {
            $Tam = count($Cuentas);

            for ($i = 0; $i < $Tam; $i ++) {
                for ($j = 0; $j < $Tam - 1 - $i; $j ++) {

                    $C1 = $Cuentas[$j + 1]['CODIGO'] . '';
                    $C2 = $Cuentas[$j]['CODIGO'] . '';

                    $Clase1 = strlen($C1) >= 1 ? $C1[0] : '';
                    $Clase2 = strlen($C2) >= 1 ? $C2[0] : '';

                    $Grupo1 = strlen($C1) >= 2 ? $C1[1] : '';
                    $Grupo2 = strlen($C2) >= 2 ? $C2[1] : '';

                    $Cuenta1 = strlen($C1) >= 4 ? $C1[2] . $C1[3] : '';
                    $Cuenta2 = strlen($C2) >= 4 ? $C2[2] . $C2[3] : '';

                    $SubCuenta1 = strlen($C1) >= 6 ? $C1[4] . $C1[5] : '';
                    $SubCuenta2 = strlen($C2) >= 6 ? $C2[4] . $C2[5] : '';

                    $Auxiliar1 = strlen($C1) >= 8 ? $C1[6] . $C1[7] : '';
                    $Auxiliar2 = strlen($C2) >= 8 ? $C2[6] . $C2[7] : '';

                    $SubAuxiliar1 = strlen($C1) == 10 ? $C1[8] . $C1[9] : '';
                    $SubAuxiliar2 = strlen($C2) == 10 ? $C2[8] . $C2[9] : '';

                    if ($Clase1 != '' && $Clase2 != '') {
                        if ($Clase1 <= $Clase2) {
                            if ($Clase1 < $Clase2) $this->swap($Cuentas, $j);

                            else {
                                if ($Grupo1 != '' && $Grupo2 != '') {
                                    if ($Grupo1 <= $Grupo2) {

                                        if ($Grupo1 < $Grupo2) $this->swap($Cuentas, $j);

                                        else {
                                            if ($Cuenta1 != '' && $Cuenta2 != '') {
                                                if ($Cuenta1 <= $Cuenta2) {

                                                    if ($Cuenta1 < $Cuenta2) $this->swap($Cuentas, $j);

                                                    else {
                                                        if ($SubCuenta1 != '' && $SubCuenta2 != '') {
                                                            if ($SubCuenta1 <= $SubCuenta2) {

                                                                if ($SubCuenta1 < $SubCuenta2) $this->swap($Cuentas, $j);

                                                                else {
                                                                    if ($Auxiliar1 != '' && $Auxiliar2 != '') {

                                                                        if ($Auxiliar1 <= $Auxiliar2) {
                                                                            if ($Auxiliar1 < $Auxiliar2) $this->swap($Cuentas, $j);

                                                                            else {
                                                                                if ($SubAuxiliar1 != '' && $SubAuxiliar2 != '') {

                                                                                    if ($SubAuxiliar1 < $SubAuxiliar2) $this->swap($Cuentas, $j);

                                                                                } else if ($SubAuxiliar1 == '') $this->swap($Cuentas, $j);
                                                                            }
                                                                        }
                                                                    } else if ($Auxiliar1 == '') $this->swap($Cuentas, $j);
                                                                }
                                                            }
                                                        } else if ($SubCuenta1 == '') $this->swap($Cuentas, $j);
                                                    }
                                                }
                                            } else if ($Cuenta1 == '') $this->swap($Cuentas, $j);
                                        }
                                    }
                                } else if ($Grupo1 == '') $this->swap($Cuentas, $j);
                            }
                        }
                    }
                }
            }

        }

        public function TraeBalanceGeneral($Cuentas = [])
        {
            $Tam = count($Cuentas);
            $Ctas = [];
            $pos = 0;

            for ($i = $Tam - 1; $i > - 1; $i --) {

                $fsubaux = false;
                $faux = false;
                $fsubcuenta = false;
                $fsubcuenta = false;
                $fcuenta = false;
                $fgrupo = false;
                $fclase = false;
                $totalaux = 0;
                $totalaux = 0;
                $totalaux = 0;
                $totalaux = 0;
                $totalaux = 0;

                $C = $Cuentas[$i]['CODIGO'] . '';

                $Clase = strlen($C) >= 1 ? $C[0] : '';

                $Grupo = strlen($C) >= 2 ? $C[1] : '';

                $Cuenta = strlen($C) >= 4 ? $C[2] . $C[3] : '';

                $SubCuenta = strlen($C) >= 6 ? $C[4] . $C[5] : '';

                $Auxiliar = strlen($C) >= 8 ? $C[6] . $C[7] : '';

                $SubAuxiliar = strlen($C) == 10 ? $C[8] . $C[9] : '';

                if ($SubAuxiliar != '') {
                    $Ctas[$pos]['CODIGO'] = $SubAuxiliar;
                    $Ctas[$pos]['SALDO'] = $this->TraeCuenta($Cuentas[$i]['ID_CUENTA']);
                    $valor += $Ctas[$pos]['SALDO'];
                    $fsubaux = true;
                    $pos ++;
                }
                else if ($Auxiliar != '') {
                    $Ctas[$pos]['CODIGO'] = $C;

                    $Ctas[$pos]['SALDO'] = $valor;
                    $faux = true;
                    $pos ++;
                }
            }
        }


        private function TraeCuenta($IdCuenta)
        {
            $IdEmpresa = $_SESSION['login'][0]['ID_EMPRESA'];
            $query = "SELECT
     (SELECT sum(VALOR) FROM t_movimiento WHERE  ID_CUENTA_MOV=$IdCuenta AND TIPO_MOV='C' AND ID_EMPRESA=" . $IdEmpresa . ")-
     (SELECT sum(VALOR) FROM t_movimiento WHERE  ID_CUENTA_MOV=$IdCuenta AND TIPO_MOV='D' AND ID_EMPRESA=" . $IdEmpresa . ") AS SALDO";

            $resulset = $this->_DB->Query($query);
            $saldo = $resulset->fetchAll();
            return $saldo[0][0];
        }

        private function swap(&$arr, $a)
        {
            $tmp = $arr[$a];
            $arr[$a] = $arr[$a + 1];
            $arr[$a + 1] = $tmp;
        }
    }
