$(document).ready(function() {
    
    // ImgenMap resize
    $('map').imageMapResize();

    // Send Manual
    $("#btn-enviar").click('click', function (event) { 
        event.preventDefault(); 
        $(this).prop('disabled', true);

        var email = $('#input-email').val();
        email = email.trim();

        if ( email.length <= 0 ) {
            Swal.fire( '¡Info!', 'El correo electrónico es requerido', 'info')
            return false;
        }

        if ( !ValidateEmail( email ) ) {
            Swal.fire( '¡Advertencia!', 'El correo electrónico es inválido', 'warning')
            $('#input-email').val('')
            return false;
        }

        $.get('api/send-manual.php', { email: email },
        function(response) {
            response = JSON.parse( response )
            if (response.ok) {
                Swal.fire( '¡Enviado!', response.message, 'success')
                $('#input-email').val('')
            } else {
                Swal.fire( '¡Error!', response.message, 'error')
            }
        });

        $(this).prop('disabled', false);
    });
});

function ValidateEmail(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
        return true;
    } else {
        return false;
    }
}