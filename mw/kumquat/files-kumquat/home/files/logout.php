<?php
    session_unset();
    session_destroy();
    exit(header('Location: index.php'));
?>