<?php

try {
    $dbc = new PDO('mysql:host=localhost;dbname=logreg', 'root', '');
    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbc->exec('SET NAMES "utf8"');
} catch (Exception $ex) {
    $error = 'Couldn\'t connect to the databse';
    include 'includes/error.html.php';
    exit();
}

$sql = 'SELECT name, email, password FROM users';
$result = $dbc->query($sql);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $names[] = $row['name'];
    $email[] = $row['email'];
}

$message = 'Fill out to sign up.';

if (isset($_POST['name'], $_POST['email'], $_POST['password']) and $_POST['name'] and $_POST['email'] and $_POST['password'] !== '') {
    if (in_array($_POST['name'], $names)) {
        $message = 'That user already exists';
    } else {
        if (in_array($_POST['email'], $email)) {
            $message = 'That email already has an account registered';
        } else {

            $sql = $dbc->prepare('INSERT INTO users SET
        name = ?,
        email = ?,
        password = ?');
            $name = strtolower($_POST['name']);
            $email = strtolower($_POST['email']);
            $password = $_POST['password'];
            $result = $sql->execute(array(
                $name,
                $email,
                md5($password . 'saltnpepper')
                    )
            );
        }
    }
}
include 'register.php';
