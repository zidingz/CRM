<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\DepositQuery;
use ChurchCRM\SessionUser;

$app->group('/deposits', function () {
    $this->get('/', 'getDeposits');
    $this->get('', 'getDeposits');
    $this->get('/{id}', 'getDepositById');
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

function getDepositById(Request $request, Response $response, array $args) {
    $renderer = new PhpRenderer('templates/finances/');

    $iDepositSlipID = $args['id'];
    $thisDeposit = ChurchCRM\DepositQuery::create()->findOneById($iDepositSlipID);

    if (empty($thisDeposit)) {
        return $response->withRedirect(SystemURLs::getRootPath() . "/v2/deposits");
    }

    if (!SessionUser::getUser()->isFinanceEnabled() || SessionUser::getUser()->getId() == $thisDeposit->getEnteredby()) {
        return $response->withRedirect(SystemURLs::getRootPath());
    } else {
        //todo: handle electronic transactions //Is this the second pass?
            //if (isset($_POST['DepositSlipLoadAuthorized'])) {
            //    $thisDeposit->loadAuthorized();
            //} elseif (isset($_POST['DepositSlipRunTransactions'])) {
            //    $thisDeposit->runTransactions();
            //}
        $pageArgs = [
            'sRootPath' => SystemURLs::getRootPath(),
            'sPageTitle' => gettext('Deposit Slip Number: ') . $thisDeposit->getId(),
            'thisDeposit' => $thisDeposit,
            'PageJSVars' => []
        ];
        if ($thisDeposit->getType() == 'Bank') {
            $_SESSION['idefaultPaymentMethod'] = 'CHECK';
        } elseif ($thisDeposit->getType() == 'CreditCard') {
            $_SESSION['idefaultPaymentMethod'] = 'CREDITCARD';
        } elseif ($thisDeposit->getType() == 'BankDraft') {
            $_SESSION['idefaultPaymentMethod'] = 'BANKDRAFT';
        } elseif ($thisDeposit->getType() == 'eGive') {
            $_SESSION['idefaultPaymentMethod'] = 'EGIVE';
        }
        $_SESSION['iCurrentDeposit'] = $thisDeposit->getId();
        SessionUser::getUser()->setCurrentDeposit($thisDeposit->getId());
        SessionUser::getUser()->save();
        return $renderer->render($response, 'depositEditor.php', $pageArgs);
    }
}
