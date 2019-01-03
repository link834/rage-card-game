<?php
    if ($_POST['start']) {
        echo exec("python3 ./rage-server.py $_POST['ip'] $POST['port'] &");
    }
?>
