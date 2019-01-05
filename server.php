<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Test</h1>

<form action="server.php" method="post">
    <div><input type="text" id="ip" name="ip" required /></div>
    <div><input type="text" id="port" name="port" required /></div>
    <div><input type="submit" name="start" value="Start Server" /></div>
</form>
<hr>
<div>
    <button id="stopServer" type="button">Stop Server</button>
    <script>
        const websocket = new WebSocket("wss://imothilightortil.dynamic-dns.net:16427/");
        const stopBtn = document.getElementById("stopServer");
        stopBtn.onclick = event => {
            websocket.send(JSON.stringify({
                type: 'kill'
            }));
        }
    </script>
</div>

<?php
    function startServer($ip, $port) {
        $nunya = $_SERVER['NUNYA'];
        $eip = escapeshellarg($ip);
        $eport = escapeshellarg($port);
        echo exec(
            "sudo python3 ./rage-server.py {$eip} {$eport} {$nunya} > /dev/null 2>&1 &"
        );
    }

    if ($_POST['start']) {startServer($_POST['ip'], $_POST['port']);}
    //if ($_GET['start']) {startServer($_GET['ip'], $_GET['port']);}
?>

</body>
</html>
