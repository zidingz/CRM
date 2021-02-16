<?php

use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\AppIntegrityService;
use Slim\Views\PhpRenderer;

$app->group('/system', function () {
    $this->get('', function ($request, $response, $args) {
        $renderer = new PhpRenderer('templates/');
        $renderPage = 'setup-system.php';
        return $renderer->render($response, $renderPage, ['sRootPath' => SystemURLs::getRootPath()]);
    });


});
