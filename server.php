<?php
    if ($_POST['start']) {
        echo exec('python3 /home/pi/rage-server/rage-server.py &');
    }

    if ($_POST['stop']) {
        echo exec('touch /home/pi/rage-server/stop-server');
    }
?>