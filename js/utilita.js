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
}

function View(){
	var minimenu = document.getElementById('menu-content');
	if(minimenu.classList.contains('not_view')){
		minimenu.classList.remove('not_view');
	}
	else{
		minimenu.classList.add('not_view');
	}
}