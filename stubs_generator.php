<?php

require 'vendor/autoload.php';

use StubsGenerator\{StubsGenerator, Finder};

$generator = new StubsGenerator();

// Matches only the files required by the public to build their own notifications.
$finder = Finder::create()->in([
    'src/modules/addons/lknhooknotification/src/Core/Platforms/Baileys',
    'src/modules/addons/lknhooknotification/src/Core/Platforms/Chatwoot',
    'src/modules/addons/lknhooknotification/src/Core/Platforms/EvolutionApi',
    'src/modules/addons/lknhooknotification/src/Core/Platforms/Common',
    'src/modules/addons/lknhooknotification/src/Core/Platforms/MetaWhatsApp',
    'src/modules/addons/lknhooknotification/src/Core/Notification',
    'src/modules/addons/lknhooknotification/src/Core/NotificationReport',
    'src/modules/addons/lknhooknotification/src/Core/Shared',
    'src/modules/addons/lknhooknotification/vendor/setasign',
]);

$result = $generator->generate($finder);

$stubs = $result->prettyPrint();

file_put_contents(__DIR__ . '/stubs.php', $stubs);
