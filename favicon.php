<?php
// Legacy favicon endpoint retained for compatibility.
header('Content-Type: image/png');
readfile(__DIR__ . '/favicon/favicon.png');
exit;
