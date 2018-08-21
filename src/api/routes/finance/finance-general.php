<?php

use ChurchCRM\Base\DonationFundQuery;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/finance/', function () {

    $this->get('funds', function ($request, $response, $args) {
      $DonationFunds = DonationFundQuery::create()->filterByActive("true")->find();
      return $response->withJSON($DonationFunds->toArray());
   });
});
