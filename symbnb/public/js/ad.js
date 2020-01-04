$('#add-image').click(function(){
    //récupérer le n° des futurs champs
    const index=+$('#widgets-counter').val;

    // récupérer le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);

    //injecter le code au sein de la div
    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);
    //gérer le boutton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target=this.dataset.target;

        $(target).remove();
    });
}

function updatecounter(){

    const count = +$('#annonce_images div.form-group').length;
    $('#widgets-counter').val(count);
}
updatecounter()
handleDeleteButtons();