<?php

    class Utiles
    {
        //put your code here

        public function EnviaEmail($Destinatario, $mensaje, $Asunto)
        {
            include("/../View/PHPMailer-master/class.phpmailer.php");
            include("/../View/PHPMailer-master/class.smtp.php");
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;// 587;
            $mail->Username = "mailout@sofinte.com";
            $mail->Password = "MAILout++";


            $mail->From = "CorreodeLauter@gmail.com";
            $mail->FromName = "Lauter";
            $mail->Subject = $Asunto;
            $mail->AltBody = "Mensaje de Lauter";
            $mail->MsgHTML($mensaje);
            //$mail->AddAttachment("files/files.zip");
            //$mail->AddAttachment("files/img03.jpg");
            $mail->AddAddress($Destinatario, "");
            $mail->IsHTML(true);
            if (!$mail->Send()) {
                return FALSE;
            } else {
                return TRUE;
            }
        }


        function generar_clave($longitud)
        {
            $cadena = "[^A-Z0-9]";
            return substr(eregi_replace($cadena, "", md5(rand())) .
                eregi_replace($cadena, "", md5(rand())) .
                eregi_replace($cadena, "", md5(rand())),
                0, $longitud);
        }


    }

?>
