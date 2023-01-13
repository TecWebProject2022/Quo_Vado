function Conferma_eliminazione(){
	var domanda = confirm("Sei sicuro di voler cancellare i commenti selezionati?","Eliminazione commento!!");
	if (domanda){
		return true;
	}
	return false;
}
function VerificaJS(){
	const mini = document.getElementsByClassName('minimenu');
	mini[0].setAttribute('id', 'menu-content');
	const icon = document.getElementById('icon');
	icon.classList.add("fa","fa-bars");
}