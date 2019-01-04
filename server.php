<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Test</h1>

<form action="server.php" method="post">
    <input type="text" id="ip" name="ip" />
    <input type="text" id="port" name="port" />
    <input type="submit" name="start" value="Start Server" />
    <input type="submit" name="stop" value="Stop Server" />
</form>

<?php
    function startServer($ip, $port) {
        $nunya = $_SERVER['NUNYA'];
        echo shell_exec("sudo python3 ./rage-server.py {$ip} {$port} {$nunya} &");
        echo "<hr>sudo python3 ./rage-server.py {$ip} {$port} {$nunya} &<hr>";
    }

    if ($_POST['start']) {startServer($_POST['ip'], $_POST['port']);}
    if ($_GET['start']) {startServer($_GET['ip'], $_GET['port']);}
?>

</body>
</html>
