<?php
    session_start();
    switch ($_GET['id']) {
        case 0:
            $_SESSION['ConsecutivoFACT'] = $_GET['consecutivo'];
            echo '<script > window.open("ImpresionFactura.php");self.location = "ReimpresionDocumentos.php?id=0" </script>';
            break;

        case 1:
            $_SESSION['ConsecutivoRECIBO'] = $_GET['consecutivo'];
            $_SESSION['Pagos'] = $_GET['pagos'];
            $_SESSION['Total'] = 'ok';
            echo '<script > window.open("ImpresionRecibo.php");self.location = "ReimpresionDocumentos.php?id=1" </script>';
            break;

        case 2:
            $_SESSION['ConsecutivoCM'] = $_GET['consecutivo'];
            echo '<script > window.open("ImpresionReciboCajaMenor.php");self.location = "ReimpresionDocumentos.php?id=2" </script>';
            break;

        case 3:
            $_SESSION['ReciboEgresos'] = 'no';
            $_SESSION['ConsecutivoGastos'] = $_GET['consecutivog'];
            $_SESSION['Total'] = 'no';
            $_SESSION['Tipo'] = $_GET['tipo'];
            echo '<script > window.open("ImpresionReciboEgresos.php");self.location = "ReimpresionDocumentos.php?id=3" </script>';
            break;
    }