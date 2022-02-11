<?php
    /* Permitimos a cualquier origen acceder a este API de manera remota */
    header('Access-Control-Allow-Origin: *');

    if ( isset($_GET['email']) && !empty($_GET['email']) ) {

        if ( sendEmailManual( $_GET['email'] ) ) {
            $data = array('ok' => true, 'message' => 'El manual se ha enviado con éxito a su correo');
            echo json_encode($data);
        } else {
            $data = array('ok' => false, 'message' => 'Estamos experimentando fallas, intente nuevamente porfavor');
            echo json_encode($data);
        }

    } else {
        $data = array('ok' => false, 'message' => 'No se ha encontrado el email');
        echo json_encode($data);
    }

    function sendEmailManual( String $email ) {
        // Recipient
        $to = $email; 
        
        // Sender
        $from = 'hola@hazcomunidad.com'; 
        $fromName = 'Haz Comunidad'; 
        
        // Email subject 
        $subject = 'Manual para hacer comunidad';
        
        // Attachment file
        $file = '../file/manual-haz-comunidad.pdf';
        
        // Email body content 
        $htmlContent = '
            <p>En este manual te hablamos de cómo puedes involucrarte en tu comunidad</p>
            <br>
            <p>- Equipo de Haz Comunidad</p>
        '; 
        
        // Header for sender info 
        $headers = "From: $fromName"." <".$from.">"; 
        
        // Boundary  
        $semi_rand = md5(time());  
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
        
        // Headers for attachment  
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
        
        // Multipart boundary  
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
        
        // Preparing attachment 
        if(!empty($file) > 0){ 
            if(is_file($file)){ 
                $message .= "--{$mime_boundary}\n"; 
                $fp =    @fopen($file,"rb"); 
                $data =  @fread($fp,filesize($file)); 
        
                @fclose($fp); 
                $data = chunk_split(base64_encode($data)); 
                $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                "Content-Description: ".basename($file)."\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
            } 
        } 
        $message .= "--{$mime_boundary}--"; 
        $returnpath = "-f" . $from; 
        
        // Send email 
        $mail = @mail($to, $subject, $message, $headers, $returnpath);  
        
        return $mail;
    }

?>