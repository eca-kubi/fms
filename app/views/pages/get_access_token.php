<?php

function getAccessToken()
{
    echo AccessToken::refreshAccessToken();
}

getAccessToken();
