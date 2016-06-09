<?php
include_once('send_forms.php');

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data; 
}

$userEmail =  $age = $isBuyer = $contactMessage = $formError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST["email"])) {
        $formError = "Tu Email es necesario";
    } else {
        $userEmail = test_input($_POST["email"]);
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $formError['email'] = "Email invalido";
        }
    }
    
    if (!isset($_POST['age'])) {
        $formError = "Tu edad es necesaria";
    } else {
        $age = test_input($_POST['age']);
        if (!preg_match("/^[0-9]{2}$/", $age)) {
            $formError['edad'] = "Escribe tu edad a 2 digitos.";
        }
    }
    
    $isBuyer = (isset($_POST['isBuyer'])) ? 
        test_input($_POST['isBuyer']) : 'No';
    

    if ($formError != "") {
        $dataResponse = array('type' => 'error', 'text' => $formError);
        echo json_encode($dataResponse);
    } else {
        $name = "Interesado en Hood";
        $subject = "USUARIO INTERESADO EN USAR LA PLATAFORMA";
        $contactMessage = 
            "<br><span style='color: #777777; font-weight: bold;'>Email:</span> " 
            . $userEmail
            . "<br><span style='color: #777777; font-weight: bold;'>Edad:</span> " 
            . $age
            . "<br><span style='color: #777777; font-weight: bold;'>Hace compras por internet?:</span> "
            . $isBuyer
            . "<br>";

        $dataResponse = send_form($userEmail, $name, $subject, $contactMessage);

        if ($dataResponse['type']=='error') 
            $dataResponse = array('type' => 'error', 'text' => 'Tu mensaje no pudo ser enviado, ponte en contacto con el equipo de soporte.');
        echo json_encode($dataResponse);
    }
}

?>