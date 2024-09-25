<!-- INFO: User register -->

<?php 
require_once __DIR__ . '/../Helpers/Helper.php';
$csrf_token = Helper::generateCSRFToken() 
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='x-ua-compatible' content='ie=edge'>
    <title>Registrieren</title>
    <meta name='' content=''>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
  </head>
  
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <h2 class="text-center mt-5">Registrierung</h2>
          <form action="../index.php" method="POST">
            <div class="form-group">
              <label for="username">Benutzername</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password">Passwort</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password">Passwort bestätigen</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <!-- CSRF-Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div class="form-group">
              <button type="submit" name="register" class="btn btn-primary btn-block mt-2">Registrieren</button>
            </div>

            <div class="form-group text-center">
              <a href="./login_form.php" class="btn btn-link">Bereits registriert? Login</a>
            </div>
          </form>
        </div>
      </div>

      <div class="row justify-content-center mt-4">
        <div class="col-md-6">
          <div class="alert alert-info" role="alert">
            <strong>Passwortanforderungen:</strong>
            <ul>
              <li>Mindestlänge: 8 Zeichen.</li>
              <li>Mindestens ein Großbuchstabe.</li>
              <li>Mindestens ein Kleinbuchstabe.</li>
              <li>Mindestens eine Ziffer.</li>
              <li>Mindestens ein Sonderzeichen.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/alert.js"></script>
  </body>
</html>
