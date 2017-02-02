<?php

function clean_string($string) {
  $bad = array("content-type","bcc:","to:","cc:","href");
  return str_replace($bad,"",$string);
}

if(isset($_GET['emailcotizacion'])) {
 
    // Edita las dos líneas siguientes con tu dirección de correo y asunto personalizados
 
    $email_from = "juan.basflo@gmail.com";
    $email_admin = "juan.basflo@gmail.com";
 
    $email_subject = "COTIZACION WAPOSAT NO-REPLY";   
     // Se valida que los campos del formulairo estén llenos
 
    if( 
        isset($_GET['nombrecotizacion']) && !empty($_GET['nombrecotizacion']) &&
        isset($_GET['telefonocotizacion']) && !empty($_GET['telefonocotizacion']) && 
        isset($_GET['emailcotizacion']) && !empty($_GET['emailcotizacion']) &&
        isset($_GET['mensajecotizacion']) && !empty($_GET['mensajecotizacion']))
    {
        // Guardando los cambios
        $nombre = $_GET['nombrecotizacion']; // requerido
        $telefono = $_GET['telefonocotizacion']; // no requerido 
        $email_to = $_GET['emailcotizacion']; // requerido
        $mensaje= $_GET['mensajecotizacion'];
        
        // Datos de cotizacion
        $tipoproducto= $_GET['tipoproducto'];
        $tipored= $_GET['tipored'];
        $electricidad= $_GET['electricidad'];
        $modoinstalacion= $_GET['modoinstalacion'];
        $cantidad= $_GET['cantidad'];
        
        // Creando header
        $headers = "";
        $headers .= "From: ".$email_from."\n";
        $headers .= "Reply-To: ".$email_from."\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        $headers .= "X-Mailer: php";

        //Mensaje para el cliente
        $client_Body = "<p>Estimado ".$nombre."</p>";
        $client_Body .= "<p>Hemos recibido su mensaje, ";
        $client_Body .= "estaremos contactandonos con usted lo mas pronto posible.</p>";
        $client_Body .= "<p>Por favor, no conteste a este correo.</p>";
        $client_Body .= "<p>Saludos cordiales.</p>";

        // Mensaje para el administrador de la web
        $email_message = "<p>Contenido del Mensaje.<p>";
        $email_message .= "<p>Nombre: ".clean_string($nombre)."<p>";
        $email_message .= "<p>Telefono: ".clean_string($telefono)."<p>";
        $email_message .= "<p>Email: ".clean_string($email_to)."<p>";
        $email_message .= "<p>Mensaje: ".clean_string($mensaje)."<p>";
        $email_message .= "<p>Tipo de producto: ".clean_string($tipoproducto)."<p>";
        $email_message .= "<p>Tipo de red: ".clean_string($tipored)."<p>";
        $email_message .= "<p>Electricidad: ".clean_string($electricidad)."<p>";
        $email_message .= "<p>Modo de instalacion: ".clean_string($modoinstalacion)."<p>";
        $email_message .= "<p>Cantidad: ".clean_string($cantidad)."<p>";
        
        //Enviando el mensaje al administrador web
        @mail($email_admin, $email_subject, $email_message, $headers);  

        //Enviando el mensaje al cliente
        @mail($email_to, $email_subject, $client_Body, $headers);  
    } 
    else{
      echo "Fallo de envio";
    }

}

?>
