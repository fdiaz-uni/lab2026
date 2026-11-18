
/**
 * recursos/js/fichero-javascript-externo.js
 * Configuración de jQuery Validation acoplada a Bootstrap 3.4.1
 */

$(document).ready(function () {
	if ($("#formLogin").length > 0) {
    $("#formLogin").validate({
        rules: {
            fEmail:    { required: true, email: true },
            fPassword: { required: true, minlength: 4 }
        },
        errorElement: "span",
        errorClass: "error",
        highlight: function (element) {
            $(element).closest(".form-group").addClass("has-error");
        },
        unhighlight: function (element) {
            $(element).closest(".form-group").removeClass("has-error");
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        }
    });
}


    if ($("#formPersona").length > 0) {
        
        $("#formPersona").validate({
            rules: {
                fNombre: {
                    required: true,
                    minlength: 3
                },
                fEmail: {
                    required: true,
                    email: true
                },
                fWebsite: {
                    required: false,
                    url: true
                },
                fSexo: {
                    required: true
                }
            },

            // Clases nativas de Bootstrap 3 para mensajes de error
            errorElement: "span",
            errorClass: "error",

            // Añade o quita la clase 'has-error' al <div class="form-group"> padre
            highlight: function (element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest(".form-group").removeClass("has-error");
            },

            // Coloca el error de los botones de radio al final de su grupo
            errorPlacement: function (error, element) {
                if (element.prop("type") === "radio") {
                    error.insertAfter(element.closest(".form-group").children().last());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    }

});
