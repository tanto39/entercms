<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/public/urlbot/UrlBot.php");

$url = "";

if (isset($_POST['url'])) {$url = htmlspecialchars(trim(stripcslashes(strip_tags($_POST['url']))));}

$result = \UrlBot\UrlBotReload::reloadUrl($url);

echo $result;