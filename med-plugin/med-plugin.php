<?php
/*
Plugin Name: med plugin
Description: the first plugin
Version: 1.0.0
Author: Med BELGHAIH
*/

//Creation de la connection avec la base de donné de wordpress
require_once(ABSPATH . 'wp-config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($conn, DB_NAME);



//la creation du tableau 
function newTable()
{

    global $conn;

    $sql = "CREATE TABLE form(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname varchar(255) NOT NULL, lastname varchar(255) NOT NULL, email varchar(255) NOT NULL, phone int NOT NULL, msg varchar(255) NOT NULL)";
    $res = mysqli_query($conn, $sql);
    return $res;
}

//Creation du Table si la connection est établie
if ($conn == true){

    newTable();
}


//Fonction pour laisser ou supprimer des champs du formulaire
function form($atts){
    $prenom= "";
    $nom= "";
    $mail= "";
    $tel= "";
    $msg= "";

    extract(shortcode_atts(
        array(
            'firstname' => 'true',
            'lastname' => 'true',
            'email' => 'true',
            'phone' => 'true',
            'message' => 'true'
            
    ), $atts));

    if($firstname== "true"){
        $prenom = '<label>First name:</label><input type="text" name="firstname" required>';
    }

    if($lastname== "true"){
        $nom = '<label>Last name:</label><input type="text" name="lastname" required>';
    }

    if($email== "true"){
        $mail = '<label>Email:</label><input type="email" name="email" required>';
    }
    if($phone== "true"){
        $tel = '<label>phone:</label><input type="number" name="phone" required>';
    }

    if($message== "true"){
        $msg = '<label>Message:</label><textarea name="msg"></textarea>';
    }



    echo '<form method="POST"  >' .$prenom.$nom.$mail.$tel.$msg. '<input style="margin-top : 20px;" value="Send" type="submit" name="submit"></form>';
}



//Shortcode du plugin
add_shortcode('contactForm', 'form');



// Fonction d'envoi des informations au base de donnée
    function sendToDB($firstname,$lastname,$email,$phone,$msg)
    {
        global $conn;

    $sql = "INSERT INTO form(firstname,lastname,email,phone, msg) VALUES ('$firstname','$lastname','$email','$phone','$msg')";
    $res = mysqli_query($conn , $sql);
    
    return $res;
    }



//L'envoi des informations au base de donnée 
    if(isset($_POST['submit'])){

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $msg = $_POST['msg'];
        

        sendToDB($firstname,$lastname,$email,$phone,$msg);
    
    }




    add_action("admin_menu", "addMenu");
    function addMenu()
    {
        add_menu_page("med plugin", "med plugin", 4, "med plugin", "adminMenu");
    }

function adminMenu()
{
    echo <<< EOD
    <div style="font-size : 20px; display : flex; flex-direction : column;">
    <h1 style="color:blue;">
      Contact Form
    </h1>
  
    <h4>
      This contact form fields :
    </h4>
    <ul>
      <li>firstname</li>
      <li>lastname</li>
      <li>email</li>
      <li>phone</li>
      <li>message</li>
    </ul>
  
    <h3>
      Use The shortcode [contactForm]
    </h3>
  
  
  
  </div>

EOD;
}

?>