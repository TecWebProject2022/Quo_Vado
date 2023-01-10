function addTextArea() {
    // Check if a session is in place
    if (!sessionStorage.getItem("session")) {
        sessionStorage.setItem("session", true);
    }

    var commentForm = document.createElement("form");
    textarea.setAttribute("id", "formNuovoCommento");
    
    // Create a new textarea element
    var commento = document.createElement("textarea");
    textarea.setAttribute("id", "nuovoCommento");
    textarea.setAttribute("rows", "4");
    textarea.setAttribute("cols", "50");
  
    // Append the textarea to the page
    commentForm.appendChild(textarea);

}