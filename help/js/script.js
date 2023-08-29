document.querySelectorAll(".hint").forEach((el, i) => el.style.display = 'none');
function showhint(id){
    document.getElementById(id).style.display = 'inline';
}
