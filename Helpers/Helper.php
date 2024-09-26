<?php
class Helper
{
    // CSRF-Token überprüfen
    public static function checkCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Token ungültig oder nicht vorhanden
            die('Ungültiger CSRF-Token');
        }
    }

    public static function generateCSRFToken()
    {
        // Session starten
        session_status() === PHP_SESSION_NONE && session_start();
        // CSRF-Token generieren, falls es nicht bereits existiert
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validatePassword($password, $confirm_password)
    {
        // Regex to check password strength
        // minimum length should be 8: {8,}
        // at least one uppercase letter: [A-Z]
        // at least one lowercase letter: [a-z]
        // at least one digits: \d
        // at least one special character: [\W_]
        // ?= matches without consuming
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        return $password === $confirm_password &&
        preg_match($pattern, $password);
    }
}
