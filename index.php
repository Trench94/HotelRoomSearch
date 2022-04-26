<?php

use MercuryHolidays\Search\Searcher;

ini_set('display_errors', 1);

require __DIR__.'../vendor/autoload.php';


$searcher = new Searcher();
$results = $searcher->search(1, 20, 30);

// Enable to debug
// print("<pre>" . print_r($results, true) . "</pre>");
