@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../dan-da/tester-php/tester.php
php "%BIN_TARGET%" %*
