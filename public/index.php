<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Maxence\BakerySimulator\HTML\webpage;

$webpage = new webpage();
$webpage->setTitle("Bakery Simulator");
$webpage->appendCssUrl('http://localhost:8000/css/globalstyle.css');

$webpage->appendContent(<<<HTML
<div class="header">
    <h2>{$webpage->getTitle()}</h2>  
</div>

<div class="content">
    <a class="StartButton" href="http://localhost:8888/BakerySimulator/public/basepage.php">
        <button type="button">Commencez</button>
    </a>
</div>
HTML);

echo $webpage->toHtml();
