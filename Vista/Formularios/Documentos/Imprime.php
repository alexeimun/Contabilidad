<?php
    session_start();
    switch ($_GET['id']) {

        case 0:
            $_SESSION['ConsecutivoRECIBO'] = $_GET['consecutivo'];
            $_SESSION['Pagos'] = $_GET['pagos'];
            $_SESSION['Total'] = 'no';

            echo '<script > window.open("ImpresionRecibo.php");self.location = "ReciboFactura.php" </script>';
            break;

        case 1:
            $_SESSION['ConsecutivoGastos'] = $_GET['consecutivoG'];
            $_SESSION['ConsecutivoEgresos'] = $_GET['consecutivoE'];
            $_SESSION['Total'] = 'no';
            $_SESSION['ReciboEgresos'] = 'ok';
            echo '<script > window.open("ImpresionReciboEgresos.php");self.location = "Egresos.php" </script>';
            break;
    }
