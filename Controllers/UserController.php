<?php

require_once '/opt/lampp/htdocs/oop/Helpers/DatabaseHelper.php';

class UserController
{
    public function show($id)
    {
        $user = User::findById($id);

        if ($user) {
            // Die View für das Benutzerprofil einbinden und das User-Objekt übergeben
            include 'Views/user_profile.php';
        } else {
            echo "Benutzer nicht gefunden.";
        }
    }

    public function login($username, $password)
    {
        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            // Login erfolgreich, Session starten
            // session_start();
            $_SESSION["login"] = "true";
            header('Location: /oop/index.php?user_id=' . $user->getId());
            exit();
        } else {
            // Login fehlgeschlagen
            header('Location: Views/login_form.php?error=Ungültige%20Anmeldedaten');
            exit();
        }
    }

    public function store($username, $password)
    {
        // Hash Password with default value according to:
        // https://www.php.net/manual/de/function.password-hash.php
        // and benchmarked costs according to Beispiel #3
        $password_hashed = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);

        // Neues User-Objekt erstellen
        $user = new User($username, $password_hashed);

        try {
            // Versuch, den Benutzer zu speichern
            $user->save();

            // Erfolgreiches Einfügen
            header('Location: Views/login_form.php?msg=Account%20erfolgreich%20erstellt');
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                // Fehler 1062: Duplicate entry (Datenbankfehler für UNIQUE-Constraint)
                header('Location: Views/register_form.php?error=Benutzername%20nicht%20mehr%20verfügbar');
            } else {
                // Andere Fehler
                header('Location: Views/register_form.php?error=Unbekannter%20Fehler');
            }
            exit();
        }
    }
}
