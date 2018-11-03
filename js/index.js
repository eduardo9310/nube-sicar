
jQuery(document).on('submit','#formlg', function(event){
  event.preventDefault();

  function limpiar() {
    $('.usuariolg').val('');
    $('.passlg').val('');
  }

  jQuery.ajax({
    url: 'main_app/login.php',
    type: 'POST',
    dataType: 'json',
    data: $(this).serialize(),
    beforeSend: function(){
      $('#btn-login').val('Validando.....');
    }
  })
  .done(function(respuesta){
    // console.log(respuesta);

    if(!respuesta.error){
      if(respuesta.estado==1){
        if(respuesta.tipo == 'Admin'){

            Push.create("Notificación",{
              body:"Bienvenido usuario Administrador",
              icon: "main_app/img/User_96px.png",
                 timeout:"4000",
                 onClick: function(){
                   window.focus();
                   this.close();
                 }
              });
          location.href = 'main_app/Admin';

        }else if(respuesta.tipo == 'Usuario') {

          Push.create("Notificación",{
            body:"Bienvenido usuario Invitado",
            icon: "main_app/img/User_96px.png",
                 timeout:"4000",
                 onClick: function(){
                   window.focus();
                   this.close();
                 }
              });

          location.href = 'main_app/Usuario';
        }
      }else{
        $('.estado').slideDown('slow');
        setTimeout(function() {
          $('.estado').slideUp('slow');
        }, 3000);
        $('.botonlg').val('Iniciar');
        console.log("Usuario desactivado");
        limpiar();
      }
    }else{
      $('.error').slideDown('slow');
      setTimeout(function() {
        $('.error').slideUp('slow');
      }, 3000);
      $('#btn-login').val('Iniciar');
      limpiar();
    }
  })
  .fail(function(resp){
    // console.log(resp.responseText);
    $('#btn-login').val('Iniciar');

  })
  .always(function(){
    // console.log("Complete");
  })
});
