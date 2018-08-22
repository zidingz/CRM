<?php

use ChurchCRM\Slim\Middleware\Request\Auth\FinanceRoleAuthMiddleware;
use ChurchCRM\Base\PledgeQuery;

$app->group('/payments', function () {
    $this->get('/', function ($request, $response, $args) {
        $this->FinancialService->getPaymentJSON($this->FinancialService->getPayments());
    });

    $this->post('/', function ($request, $response, $args) {
        $payment = $request->getParsedBody();
        echo json_encode(['payment' => $this->FinancialService->submitPledgeOrPayment($payment)]);
    });
    
    $this->get('/{groupKey}', function ($request, $response, $args) {
        $groupKey = $args['groupKey'];
        $payment = PledgeQuery::create()
                ->findOneByGroupkey($groupKey);
        return $response->withJSON($payment->toArray());
    });

    $this->delete('/{groupKey}', function ($request, $response, $args) {
        $groupKey = $args['groupKey'];
        $this->FinancialService->deletePayment($groupKey);
        echo json_encode(['status' => 'ok']);
    });
})->add(new FinanceRoleAuthMiddleware());
