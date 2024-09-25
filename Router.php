<?php

require_once '/opt/lampp/htdocs/oop/Models/User.php';
require_once '/opt/lampp/htdocs/oop/Controllers/UserController.php';
require_once '/opt/lampp/htdocs/oop/Helpers/Helper.php';

/*
* Klasse zum handhaben von Requests
*/
class Router
{
    public function __construct()
    {
        // Überprüfe, ob session aktiv ist, ansonsten starte eine
        session_status() === PHP_SESSION_NONE && session_start();
    }

    // Überprüfe, um welchen Request es sich handelt und rufe die entsprechende 
    // Methode auf
    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handlePost();
        } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->handleGet();
        } else {
            echo "Nur POST und GET";
        }
    }

    private function handlePost()
    {
        if (isset($_POST['login'])) {
            Helper::checkCSRFToken();
            // Print debug output to file
            // file_put_contents('/opt/lampp/logs/custom_log', print_r($_POST['login']));
            $username = $_POST['username'];  
            $password = $_POST['password'];      

            $userController = new UserController();
            $userController->login($username, $password);

            // // https://stackoverflow.com/questions/1055728/php-session-with-an-incomplete-object
            // $_SESSION['user'] = serialize($user);
            exit();
        }

        if (isset($_POST['register'])) {
            Helper::checkCSRFToken();

            $username = $_POST['username'];  
            $password = $_POST['password'];      
            $confirm_password = $_POST['confirm_password'];

            if (Helper::validatePassword($password, $confirm_password)) {
                $userController = new UserController();
                $userController->store($username, $password);

                exit();
            } else {
                header('Location: Views/register_form.php?error=Passwörter%20stimmen%20nicht%20überein%20oder%20Passwort%20zu%20schwach');
                exit();
            }
        }

        isset($_POST["signout"]) && Helper::signOut();
    }

    private function handleGet()
    {
        if (isset($_GET['user_id'])) {
            // UserController instanziieren
            $userController = new UserController();

            // Benutzer-ID aus der URL abrufen
            $userId = $_GET['user_id'];

            // Die show-Methode des UserControllers aufrufen
            $userController->show($userId);
        } else {
            header('location: ./Views/login_form.php');
        }
    }
}
