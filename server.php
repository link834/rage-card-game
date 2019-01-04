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
    function startServer($ip, $port) {
        $nunya = getenv("ZZ_WEB_STUFF");
        echo shell_exec("python3 ./rage-server.py {$ip} {$port} {$nunya}  &");
        echo "<h1>python3 ./rage-server.py " . $ip . " " . $port . " &</h1>";
    }

    if ($_POST['start']) {startServer($_POST['ip'], $_POST['port']);}
    if ($_GET['start']) {startServer($_GET['ip'], $_GET['port']);}
?>

</body>
</html>
