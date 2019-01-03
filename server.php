<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Test</h1>

<form action="server.php">
    <input type="text" id="ip" name="ip" />
    <input type="text" id="port" name="port" />
    <input type="submit" name="start" value="Start Server" />
    <input type="submit" name="stop" value="Stop Server" />
</form>

<?php
    /*if ($_POST['start']) {
        echo exec("python3 ./rage-server.py $_POST['ip'] $_POST['port'] &");
        echo "<h1>python3 ./rage-server.py $_POST['ip'] $_POST['port'] &</h1>";
    }*/

    if ($_GET['start']) {
        echo shell_exec("python3 ./rage-server.py {$_GET['ip']} {$_GET['port']} &");
        echo "<h1>python3 ./rage-server.py " . $_GET['ip'] . " " . $_GET['port'] . " &</h1>";
    }
?>

</body>
</html>
