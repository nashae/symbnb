// quand on clique sur add_image
$('#add_image').click(function () { 
    // on recupere la valeur de widgets-counter, on la transforme en INT avec le+ (sinon string par defaut)
const index = +$('#widgets-counter').val();
    // on recupere le prototype des entrées et on remplace le champs contenant __name__par la valeur d'index
const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);
    // on rajoute le template à la div annonce_images
$('#annonce_images').append(tmpl);
    //on ajoute 1 a widgets-counter
$('#widgets-counter').val(index + 1);
    // on appelle le bouton supprimer
handleDeleteButtons();
});

function handleDeleteButtons(){
$('button[data-action="delete"]').click(function(){  //quand on clique sur le bouton delete
    const target = this.dataset.target;  //this dans fonction js: l'element html designé, ici this represente le bouton, dataset tous les attributs data-xx, 
    $(target).remove();
})
}

function updateCounter(){
const count = +$('#annonce_images div.form-group').length;
$('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();