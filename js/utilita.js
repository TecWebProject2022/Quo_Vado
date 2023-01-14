function Conferma_eliminazione(){
	var domanda = confirm("Sei sicuro di voler cancellare i commenti selezionati?","Eliminazione commento!!");
	if (domanda){
		return true;
	}
	return false;
}
function VerificaJS(){
	var mini = document.getElementsByClassName('minimenu');
	mini[0].setAttribute('id', 'menu-content');
	mini[0].classList.add('not_view');
	var  icon = document.getElementById('icon');
	icon.classList.add("fa","fa-bars");
}
function View(){
	var icon=document.getElementById('menu-content');
	if(icon.classList.contains('not_view')){
		icon.classList.remove('not_view');
	}
	else{
		icon.classList.add('not_view');
	}
}