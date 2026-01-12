<?php
/**
 * Logout Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Uitloggen en sessie vernietigen
 */

session_start();
session_destroy();
header('Location: login.php');
exit;
