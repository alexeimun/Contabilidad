<?php
    session_start();

    if (isset($_SESSION['login']) == '')
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

?>
<html>

<head>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.js"></script>
</head>
<style>


</style>
<style type="text/css" media="print">
    @page {
        margin: 25px;
    }

</style>

<style type="text/css">

    #wrapper {
        height: 210%;
    }

    table thead tr th {
        border: 1px solid black;
        text-align: center;
        font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
        font-size: 15px;
        background: #5E83A3;
        color: #f1f6f4;
    }

    table td table td input {
        font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
        font-size: 16px;
        height: 20px;
    }

    table tr td:first-of-type, table tr td:first-of-type input, table thead tr th:first-of-type {
        width: 80px;
    }

    table tr td:last-of-type, table tr td:last-of-type input, table thead tr th:last-of-type {
        width: 120px;
    }

    table tr td:nth-last-of-type(2), table tr td:nth-last-of-type(2) input, table thead tr th:nth-last-of-type(2) {
        width: 120px;
    }

    table tbody tr td:nth-of-type(2) input {
        width: 216px;
    }

    table tbody tr td:nth-of-type(2) input {
        width: 216px;
    }

    table thead tr:first-of-type th:last-of-type, tr:first-of-type th:first-of-type {
        background: inherit;
        border: inherit;
    }

    #tabs ul {
        width: 260px;
        margin-left: 30px;
        height: 46px;
        border: 0;
    }

    #tabs {
        font-size: 11pt;
        border: 0;
        margin: 5px;
    }

    table tbody tr td:first-of-type input {
        text-align: center;
    }


</style>

<meta charset="UTF-8"/>
<body onload="Imprimir();">
<?= $_SESSION['Tabla'] ?>

<script>

    function Imprimir() {
        window.print();
    }

    //    document.location.href="LibroFiscal.php";


</script>
</body>

</html>