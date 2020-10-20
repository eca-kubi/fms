<?php
function redirect(string $page = ''): void
{
    $getParams = fetchGetParams();
    if (strpos($page, '?') === false && $getParams) {
        header('location: ' . URL_ROOT . '/' . $page . '?' . fetchGetParams());
    }
    else {
        header('location: ' . URL_ROOT . '/' . $page);
    }
    exit;
}
