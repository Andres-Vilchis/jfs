<?php

use CodeIgniter\I18n\Time;

if (! function_exists('esActivo')) {
    function esActivo(string $ruta): string
    {
        return uri_string() === $ruta ? 'active text-info" aria-current="page' : '';
    }
}

if (! function_exists('fechaFormateada')) {
    function fechaFormateada(string $fecha = 'now'): string
    {
        $time = Time::parse($fecha, 'America/Mexico_City', 'es_MX');
        return $time->toLocalizedString("EEE, dd 'de' MMMM yyyy");
    }
}