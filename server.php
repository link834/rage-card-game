<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Test</h1>

<form action="server.php" method="post">
    <input type="text" id="ip" name="ip" required />
    <input type="text" id="port" name="port" required />
    <input type="submit" name="start" value="Start Server" />
    <input type="submit" name="stop" value="Stop Server" />
</form>

<?php
    function startServer($ip, $port) {
        $nunya = $_SERVER['NUNYA'];
        echo system(
            "sudo python3 ./rage-server.py {$ip} {$port} {$nunya} > /dev/null 2>&1 & echo $! > ./python.pid"
        );
    }

    function stopServer() {
        echo system(
            'sudo kill $(cat ./python.pid)'
        );
    }

    if ($_POST['start']) {startServer($_POST['ip'], $_POST['port']);}
    if ($_POST['stop']) {stopServer();}
    //if ($_GET['start']) {startServer($_GET['ip'], $_GET['port']);}
?>

</body>
</html>
