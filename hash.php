<?php
$inputPassword = "uscamayta";
$hashDesdeBD = '$2y$10$HZHhvPnR5QwEjLaxG3YYCeF4vQ3pDjgMIMiyxUg0Tw4UGEyrAgXdYW'; // copia el hash COMPLETO

if (password_verify($inputPassword, $hashDesdeBD)) {
    echo "✅ Contraseña válida";
} else {
    echo "❌ Contraseña inválida";
}
