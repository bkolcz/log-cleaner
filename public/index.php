<?php 

use LogCleaner\Kernel;

require_once dirname(__DIR__).'/vendor/autoload.php';

echo json_encode((new Kernel())->run(["analyse" => ["dateTo" => new DateTime("24/Mar/2022:13:57:18 +0100")], "removeAll" => ["dateFrom" => new DateTime("24/Mar/2022:13:00:00 +0100"), "dateTo" => new DateTime("24/Mar/2022:13:57:18 +0100")]]),JSON_PRETTY_PRINT); // DEBUG