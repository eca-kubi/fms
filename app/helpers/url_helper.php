<?php
function redirect(string $page = '')
{
    header('location: ' . URL_ROOT . '/' . $page);
    exit;
}