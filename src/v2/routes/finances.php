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
    $this->get('/{id}/newPayment', 'newPayment');
    $this->get('/{id}/{groupKey}', 'editPayment');
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

    if (!SessionUser::getUser()->isFinanceEnabled() && SessionUser::getUser()->getId() != $thisDeposit->getEnteredby()) {
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
            'fundData' => GetFundData($thisDeposit),
            'pledgeTypeData' => GetPledgeTypeData($thisDeposit),
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
        
        if (!$thisDeposit->getClosed()) {
          if ($thisDeposit->getType() == 'eGive') {
            $pageArgs['AddPaymentButton'] = '<input type=button class=btn value="'.gettext('Import eGive')."\" name=ImporteGive onclick=\"javascript:document.location='eGive.php?DepositSlipID=".$thisDeposit->getId()."&linkBack=DepositSlipEditor.php?DepositSlipID=".$thisDeposit->getId()."&PledgeOrPayment=Payment&CurrentDeposit=".$thisDeposit->getId()."';\">";
          } else {
            $pageArgs['AddPaymentButton'] = '<a class="btn btn-success"'.
                    'href="'.$request->getUri().'/newPayment"'.
                    '>'.gettext('Add Payment')."</a>";
          }
          if ($thisDeposit->getType() == 'BankDraft' || $thisDeposit->getType() == 'CreditCard') {
            $pageArgs['AddPaymentButton'] = '<input type="submit" class="btn btn-success" value="' . gettext('Load Authorized Transactions'). '" name="DepositSlipLoadAuthorized">'.
            '<input type="submit" class="btn btn-warning" value="'.gettext('Run Transactions').'" name="DepositSlipRunTransactions">';
          }
        }
        
        return $renderer->render($response, 'depositEditor.php', $pageArgs);
    }
}

function GetFundData($thisDeposit) {
  $fundData = [];
  foreach ($thisDeposit->getFundTotals() as $tmpfund) {
      $fund = new StdClass();
      $fund->color = '#'.random_color();
      $fund->highlight = '#'.random_color();
      $fund->label = $tmpfund['Name'];
      $fund->value = $tmpfund['Total'];
      array_push($fundData, $fund);
  }
  return $fundData;
}
  
function GetPledgeTypeData($thisDeposit)
{
  $pledgeTypeData = [];
  $t1 = new stdClass();
  $t1->value = $thisDeposit->getTotalamount() ? $thisDeposit->getTotalCash() : '0';
  $t1->color = '#197A05';
  $t1->highlight = '#4AFF23';
  $t1->label = 'Cash';
  array_push($pledgeTypeData, $t1);
  $t1 = new stdClass();
  $t1->value = $thisDeposit->getTotalamount() ? $thisDeposit->getTotalChecks() : '0';
  $t1->color = '#003399';
  $t1->highlight = '#3366ff';
  $t1->label = 'Checks';
  array_push($pledgeTypeData, $t1);
  return $pledgeTypeData;
  
}

function newPayment(Request $request, Response $response, array $args) {
    $renderer = new PhpRenderer('templates/finances/');

    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'sPageTitle' => gettext('Payment Editor'),
        'PageJSVars' => []
    ];

    if (!SessionUser::getUser()->isFinance()) {
        return $response->withRedirect(SystemURLs::getRootPath());
    } else {
        return $renderer->render($response, 'payment.php', $pageArgs);
    }
}
