function Conferma_eliminazione(){
	var domanda = confirm("Sei sicuro di voler cancellare i commenti selezionati?","Eliminazione commento!!");
	if (domanda){
		return true;
	}
	return false;
}