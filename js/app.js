$(document).ready(function() {
    
    // ImgenMap resize
    $('map').imageMapResize();

    // Send Manual
    $( "#openSendManual" ).click(function() {
        Swal.fire({
            icon: 'question',
            title: 'Ingresa tu email para enviarte el manual gratuito',
            input: 'email',
            inputAttributes: {
                autocapitalize: 'off'
            },
            inputPlaceholder: 'example@mail.com',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Enviar manual',
            showLoaderOnConfirm: true,
            preConfirm: (mail) => {
                return fetch(`api/send-manual.php?email=${mail}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if(  result.value.ok ) {
                    Swal.fire(
                        '¡Hecho!',
                        result.value.message,
                        'success'
                    )
                } else {
                    Swal.fire(
                        '¡Error!',
                        result.value.message,
                        'error'
                    )
                }
            }
        })
    });
});