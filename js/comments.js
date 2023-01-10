function addComment() {
    parent=document.getElementById('newcomment');
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    // Check if a session is in place
    if (!sessionStorage.setItem("session", true)){
        var a=document.createElement('strong');
       a.appendChild(document.createTextNode('Utente non registrato, per lasciare un commento registrati o accedi!'));
       parent.appendChild(a);
    }
    else{
        //creo il form
        var commentForm = document.createElement("form");
        commentForm.setAttribute("id", "formNuovoCommento");
        commentForm.setAttribute("method", "post");
        commentForm.setAttribute("action", "addComment.php");
        //creo la label
        var commentLabel = document.createElement("label");
        commentLabel.setAttribute("for", "nuovoCommento");
        commentLabel.appendChild(document.createTextNode('Inserisci il commento'));
        //cxreo la textarea
        var commento = document.createElement("textarea");
        textarea.setAttribute("id", "nuovoCommento");
        textarea.setAttribute("rows", "4");
        textarea.setAttribute("cols", "50");
        //creo il bottone per il submit
        var submitButton = document.createElement("input");
        submitButton.setAttribute("id", "addCommento");
        submitButton.setAttribute("value", "submit");
        submitButton.setAttribute("type", "submit");
        submitButton.setAttribute("name", "Aggiungi commento");
        //submitButton.setAttribute("onsubmit", "return Validate()");
        //inserisco nel form
        commentForm.appendChild(commentlabel);
        commentForm.appendChild(textarea);
        commentForm.appendChild(submitButton);
        //rimuovo il pulsante per aggingere un commento e aggiungo il form
        parent.removeChild(parent.children[0]);
        parent.appendChild(commentForm);
    }
    

}