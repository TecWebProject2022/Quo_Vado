<?php
require_once "userData.php"; 

$paginaHTML=file_get_contents("registrazione_utente.html");
$tagpermessi='<em><strong><li><ul>';#legge anche il tag di chiusura 
$messaggiForm='';

# recupero dell' imput e salvataggio nel database
if(isset($_POST['submit'])){
    #pulizia input e gestione eeventuali messaggi d'errore
    $userdata= new userData($_POST['name'],$_POST['last_name'],$_POST['birthday'],$_POST['gender'],$_POST['school'],$_POST['username'],$_POST['password'],);

    if(!$userdata=""){
        $messaggiForm ="<p>I dati inseriti non sono corretti: " . $userdata ."</p>" ; #output messaggi d'errore
    }else{
        $userdata->save();
        $messaggiForm=$userdata? $userdata :"<p>Registrazione avvenuta con successo!</p>" ;

        # aggiorno la pagina contenente il form con i dati ricevuti 
        $paginaHTML=str_replace('<valoreNome />',$userdata->getName(),$paginaHTML);
        $paginaHTML=str_replace('<valoreCognome />',$userdata->getLastName(),$paginaHTML);
        $paginaHTML=str_replace('<valoreDataNascita />',$userdata->getBirthday(),$paginaHTML);
        $paginaHTML=str_replace('<valoreGenere />',$userdata->getUsername(),$paginaHTML);
        $paginaHTML=str_replace('<valoreUsername />',$userdata->getPassword(),$paginaHTML);
        $paginaHTML=str_replace('<valorePassword />',$userdata->getSchool(),$paginaHTML);

    }
    #aggiungo i messaggi per l'utente
    $paginaHTML=str_replace('<messaggiForm />',$messaggiForm,$paginaHTML);
    $userdata->free();
}


echo $paginaHTML;

?>