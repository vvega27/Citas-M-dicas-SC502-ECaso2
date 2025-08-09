$('#formLogin').on('submit', function(e){
  e.preventDefault();
  $.post($(this).attr('action'), $(this).serialize())
    .done(function(r){
      if (r && r.status === 'success') {
        window.location.href = 'citas.php';
      } else {
        $('#msg').text((r && r.message) ? r.message : 'login invalido');
      }
    })
    .fail(function(){
      $('#msg').text('error de red o servidor');
    });
});
//login 
