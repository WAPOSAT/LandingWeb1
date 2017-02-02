<?php

function clean_string($string) {
  $bad = array("content-type","bcc:","to:","cc:","href");
  return str_replace($bad,"",$string);
}

if(isset($_GET['email'])) {
 
    // Edita las dos líneas siguientes con tu dirección de correo y asunto personalizados
 
    $email_from = "juan.basflo@gmail.com";
    $email_admin = "juan.basflo@gmail.com";
 
    $email_subject = "CONSULTA WAPOSAT [CONTACTANOS]";   
     // Se valida que los campos del formulairo estén llenos
 
    if( 
        isset($_GET['tipo']) && !empty($_GET['tipo']) &&
        isset($_GET['nombreseccion']) && !empty($_GET['nombreseccion']) &&
        isset($_GET['nombre']) && !empty($_GET['nombre']) &&
        isset($_GET['telefono']) && !empty($_GET['telefono']) && 
        isset($_GET['email']) && !empty($_GET['email']) &&
        isset($_GET['mensaje']) && !empty($_GET['mensaje']))
    {
        // Guardando los cambios
        $tipo = $_GET['tipo']; // requerido     
        $nombreseccion = $_GET['nombreseccion']; // requerido
        $nombre = $_GET['nombre']; // requerido
        $telefono = $_GET['telefono']; // no requerido 
        $email_to = $_GET['email']; // requerido
        $mensaje= $_GET['mensaje'];
        
        // Creando header
        $headers = "";
        $headers .= "From: ".$email_from."\n";
        $headers .= "Reply-To: ".$email_from."\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        $headers .= "X-Mailer: php";

        /*
        $headers = "MIME-Version:1.0\r\n";
        $headers .= "From: $email_from \r\n";
        $headers .="Reply-To: $email_from \r\n";
        $headers .="To: $email_to \r\n Subject: $email_subject \r\n";
        $headers .='X-Mailer: PHP/' . phpversion();
        */

        //Mensaje para el cliente
        $client_Body = "<p>Estimado ".$nombre."</p>";
        $client_Body .= "<p>Hemos recibido su consulta, ";
        $client_Body .= "estaremos contactandonos con usted lo mas pronto posible.</p>";
        $client_Body .= "<p>Por favor, no conteste a este correo.</p>";
        $client_Body .= "<p>Saludos cordiales.</p>";

        // Mensaje para el administrador de la web
        $email_message = "<p>Contenido del Mensaje.<p>";
        $email_message .= "<p>Tipo: ".clean_string($tipo)."<p>";
        $email_message .= "<p>Nombre seccion: ".clean_string($nombreseccion)."<p>";
        $email_message .= "<p>Nombre: ".clean_string($nombre)."<p>";
        $email_message .= "<p>Telefono: ".clean_string($telefono)."<p>";
        $email_message .= "<p>Email: ".clean_string($email_to)."<p>";
        $email_message .= "<p>Mensaje: ".clean_string($mensaje)."<p>";

        /*
        $email_message .= "Tipo: ".clean_string($tipo)."\n";
        $email_message .= "nombreseccion: ".clean_string($nombreseccion)."\n";
        $email_message .= "nombre: ".clean_string($nombre)."\n";
        $email_message .= "telefono: ".clean_string($telefono)."\n";
        $email_message .= "email: ".clean_string($email_to)."\n"." $mensaje";
        */

        //Enviando el mensaje al administrador web
        @mail($email_admin, $email_subject, $email_message, $headers);  

        //Enviando el mensaje al cliente
        @mail($email_to, $email_subject, $client_Body, $headers);  
    } 
    else{
      echo "Fallo de envio";
    }
    /*
    $headers2 = "MIME-Version:1.0\r\n";
    $headers2 .= "From: juan.basilio@waposat.com \r\n";
    $headers2 .="Reply-To: juan.basilio@waposat.com \r\n";
    $headers2 .="To: $email_from \r\n Subject: WAPOSAT [RESPUESTA AUTOMÁTICA] \r\n";
    $headers2 .='X-Mailer: PHP/' . phpversion();
    @mail($email_from, "WAPOSAT [RESPUESTA ATUOMÁTICA]", "\r\n Nos pondremos en contacto contigo pronto \r\n Por favor, no contestar este correo.",$headers2) ;
    */
}

?>
