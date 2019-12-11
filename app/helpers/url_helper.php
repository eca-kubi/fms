<?php
function redirect(?string $page)
{
    header('location: ' . HOST . '/' . $page);
    exit;
}