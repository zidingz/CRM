<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\SessionUser;

$app->group('/deposits', function () {
  $this->get('/', 'getDeposits');
  $this->get('', 'getDeposits');
});

function getDeposits(Request $request, Response $response, array $args) {
  $renderer = new PhpRenderer('templates/finances/');

  $pageArgs = [
      'sRootPath' => SystemURLs::getRootPath(),
      'sPageTitle' => gettext('Deposit List'),
      'PageJSVars' => []
  ];

  if (!SessionUser::getUser()->isFinance()) {
    return $response->withRedirect(SystemURLs::getRootPath());
  } else {
    return $renderer->render($response, 'depositListing.php', $pageArgs);
  }
}