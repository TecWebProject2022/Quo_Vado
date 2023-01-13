function Conferma_eliminazione(){
	
	var domanda = confirm("Sei sicuro di voler cancellare?");
	
	if (domanda){
		return true;
	}
	return false;
}