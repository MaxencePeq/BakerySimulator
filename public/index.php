<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Maxence\BakerySimulator\HTML\webpage;

$webpage = new webpage();
$webpage->setTitle("Bakery Simulator");

echo $webpage->toHtml();
