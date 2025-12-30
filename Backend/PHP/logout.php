<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../Frontend/HTML/pagina_inicio.html");
exit;
