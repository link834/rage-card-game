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
        $eip = escapeshellarg($ip);
        $eport = escapeshellarg($port);
        echo exec(
            "sudo python3 ./rage-server.py {$eip} {$eport} {$nunya} > /dev/null 2>&1 &"
        );
        echo exec(
            "echo $! > server.pid"
        );
    }

    function stopServer() {
        echo "<h1>HI</h1>";
    }

    if ($_POST['start']) {startServer($_POST['ip'], $_POST['port']);}
    if ($_POST['stop']) {stopServer();}
    //if ($_GET['start']) {startServer($_GET['ip'], $_GET['port']);}
?>

<script>
    document.getElementByName
</script>

</body>
</html>
