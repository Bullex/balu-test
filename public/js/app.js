$('.fa-plus').on('click', function(event){
  event.preventDefault();
  var id = $(this).parent().data('id');
  var text = $(this).parent().prev().text();
  $('#header_text').html('Creating new child node for '+text);
  $('input[name="parent_id"').val(id);
});
$('form[name="createNode"]').on("submit", function(event) {
  var name = $('input[name="name"').val();
  if(!!name) {
    $.post("/public/node/"+$('input[name="parent_id"').val()+"/children?name="+name, function( data ) {
      location.reload();
    });
  } else {
    event.preventDefault();
    $('input[name="name"').prop('placeholder', 'ENTER NODE NAME!!!!!!!')
  }
});