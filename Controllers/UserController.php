<?php

require_once __DIR__ . '/../Helpers/DatabaseHelper.php';
require_once __DIR__ . '/../Helpers/FormCheckHelper.php';

class UserController
{
    public function show($id): void
    {
        $user = User::findById($id);

        if ($user) {
            // Die View für das Benutzerprofil einbinden und das User-Objekt übergeben
            include './Views/user_profile.php';
        } else {
            echo "Benutzer nicht gefunden.";
        }
    }

    public function login($formData): void
    {
        $formCheckHelper = new FormCheckHelper($formData);

        $user = User::findByEmail($formCheckHelper->email());
        // $pwr = $formCheckHelper->password_repeat();
        // $user->id('23');

        if ($user && password_verify($formCheckHelper->password(), $user->hashed_password())) {
            // Login erfolgreich, Session starten
            // session_start();
            $_SESSION["login"] = "true";
            header('Location: ./index.php?user_id=' . $user->id());
            exit();
        } else {
            // Login fehlgeschlagen
            header('Location: ./Views/login_form.php?error=Ungültige%20Anmeldedaten');
            exit();
        }
    }

    public function create($formData): void 
    {
        $formCheckHelper = new FormCheckHelper($formData);

        if (!$formCheckHelper->validatePasswordEquality()) {
            header('Location: ./Views/register_form.php?error=Passwörter%20stimmen%20nicht%20überein');
            exit();
        } elseif (!$formCheckHelper->validatePasswordPolicy()) {
            header('Location: ./Views/register_form.php?error=Passwort%20zu%20schwach');
            exit();
        } else {
            // Neues User-Objekt erstellen
            $user = new User($formCheckHelper->email(), $formCheckHelper->password_hash(), $formData['first_name'], $formData['last_name'], $formData['address']);
            // Neuen User in Datenbank speichern
            $this->store($user);
        }
    }

    private function store($user): void
    {
        try {
            // Versuch, den Benutzer zu speichern
            $user->save();

            // Erfolgreiches Einfügen
            header('Location: ./Views/login_form.php?msg=Account%20erfolgreich%20erstellt');
            exit();
        } catch (PDOException $e) {
            // Fehler 1062: Duplicate entry (Datenbankfehler für UNIQUE-Constraint)
            if ($e->getCode() === '23000') { 
                header('Location: ./Views/register_form.php?error=Benutzername%20nicht%20mehr%20verfügbar');
            } else {
                // Andere Fehler
                header('Location: ./Views/register_form.php?error=Unbekannter%20Fehler');
            }
            exit();
        }
    }

    public static function signOut(): void
    {
        session_unset();
        session_destroy();
        header("Location: ./index.php");
        exit();
    }
}
