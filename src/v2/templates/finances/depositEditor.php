<?php

use ChurchCRM\dto\SystemURLs;

require SystemURLs::getDocumentRoot() . '/Include/SimpleConfig.php';
require SystemURLs::getDocumentRoot() . '/Include/Header.php';

?>
<div class="row">
  <div class="col-lg-7">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo gettext('Deposit Details: '); ?></h3>
      </div>
      <div class="box-body">
        <form method="post" action="#" name="DepositSlipEditor" id="DepositSlipEditor">
          <div class="row">
            <div class="col-lg-4">
              <label for="Date"><?= gettext('Date'); ?>:</label>
              <input type="text" class="form-control date-picker" name="Date" value="<?php echo $thisDeposit->getDate('Y-m-d'); ?>" id="DepositDate" >
            </div>
            <div class="col-lg-4">
              <label for="Comment"><?php echo gettext('Comment:'); ?></label>
              <input type="text" class="form-control" name="Comment" id="Comment" value="<?php echo $thisDeposit->getComment(); ?>"/>
            </div>
            <div class="col-lg-4">
              <label for="Closed"><?php echo gettext('Closed:'); ?></label>
              <input type="checkbox"  name="Closed" id="Closed" value="1" <?php if ($thisDeposit->getClosed()) {
    echo ' checked';
} ?>/><?php echo gettext('Close deposit slip (remember to press Save)'); ?>
            </div>
          </div>
          <div class="row p-2">
            <div class="col-lg-5 m-2" style="text-align:center">
              <input type="submit" class="btn" value="<?php echo gettext('Save'); ?>" name="DepositSlipSubmit">
            </div>
            <div class="col-lg-5 m-2" style="text-align:center">
              <input type="button" class="btn" value="<?php echo gettext('Deposit Slip Report'); ?>" name="DepositSlipGeneratePDF" onclick="window.CRM.VerifyThenLoadAPIContent(window.CRM.root + '/api/deposits/<?php echo $thisDeposit->getId() ?>/pdf');">
            </div>
          </div>
          <?php
          if ($thisDeposit->getType() == 'BankDraft' || $thisDeposit->getType() == 'CreditCard') {
              echo '<p>'.gettext('Important note: failed transactions will be deleted permanantly when the deposit slip is closed.').'</p>';
          }
          ?>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo gettext('Deposit Summary: '); ?></h3>
      </div>
      <div class="box-body">
        <div class="col-lg-6">
          <canvas id="type-donut" style="height:250px"></canvas>
          <ul style="margin:0px; border:0px; padding:0px;">
          <?php
          // Get deposit totals
          echo '<li><b>TOTAL ('.$thisDeposit->getPledges()->count().'):</b> $'.$thisDeposit->getVirtualColumn('totalAmount').'</li>';
                        if ($thisDeposit->getCountCash()) {
                            echo '<li><b>CASH ('.$thisDeposit->getCountCash().'):</b> $'.$thisDeposit->getTotalCash().'</li>';
                        }
                        if ($thisDeposit->getCountChecks()) {
                            echo '<li><b>CHECKS ('.$thisDeposit->getCountChecks().'):</b> $'.$thisDeposit->getTotalChecks().' </li>';
                        }
          ?>
            </ul>
        </div>
         <div class="col-lg-6">
          <canvas id="fund-donut" style="height:250px"></canvas>
          <ul style="margin:0px; border:0px; padding:0px;">
          <?php
          foreach ($thisDeposit->getFundTotals() as $fund) {
              echo '<li><b>'.$fund['Name'].'</b>: $'.$fund['Total'].'</li>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo gettext('Payments on this deposit slip:'); ?></h3>
    <div class="pull-right">
      <?php
      if ($thisDeposit->getClosed()) {
          if ($thisDeposit->getType() == 'eGive') {
              echo '<input type=button class=btn value="'.gettext('Import eGive')."\" name=ImporteGive onclick=\"javascript:document.location='eGive.php?DepositSlipID=".$thisDeposit->getId()."&linkBack=DepositSlipEditor.php?DepositSlipID=".$thisDeposit->getId()."&PledgeOrPayment=Payment&CurrentDeposit=".$thisDeposit->getId()."';\">";
          } else {
              echo '<input type=button class="btn btn-success" value="'.gettext('Add Payment')."\" name=AddPayment onclick=\"javascript:document.location='PledgeEditor.php?CurrentDeposit=".$thisDeposit->getId()."&PledgeOrPayment=Payment&linkBack=DepositSlipEditor.php?DepositSlipID=".$thisDeposit->getId()."&PledgeOrPayment=Payment&CurrentDeposit=".$thisDeposit->getId()."';\">";
          }
          if ($thisDeposit->getType() == 'BankDraft' || $thisDeposit->getType() == 'CreditCard') {
              ?>
          <input type="submit" class="btn btn-success" value="<?php echo gettext('Load Authorized Transactions'); ?>" name="DepositSlipLoadAuthorized">
          <input type="submit" class="btn btn-warning" value="<?php echo gettext('Run Transactions'); ?>" name="DepositSlipRunTransactions">
          <?php
          }
      }
      ?>
    </div>
  </div>
  <div class="box-body">
    <table class="table" id="paymentsTable" width="100%"></table>
    <?php
    if (!$thisDeposit->getClosed()) {
        if ($thisDeposit->getType() == 'Bank') {
            ?>
        <button type="button" id="deleteSelectedRows"  class="btn btn-danger" disabled>Delete Selected Rows</button>
        <?php
        }
    }
    ?>
  </div>
</div>


<script  src="<?= SystemURLs::getRootPath() ?>/skin/js/DepositSlipEditor.js"></script>
<?php
  $fundData = [];
  foreach ($thisDeposit->getFundTotals() as $tmpfund) {
      $fund = new StdClass();
      $fund->color = '#'.random_color();
      $fund->highlight = '#'.random_color();
      $fund->label = $tmpfund['Name'];
      $fund->value = $tmpfund['Total'];
      array_push($fundData, $fund);
  }
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
?>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
  var depositType = '<?php echo $thisDeposit->getType(); ?>';
  var depositSlipID = <?php echo $thisDeposit->getId(); ?>;
  var isDepositClosed = Boolean(<?=  $thisDeposit->getClosed(); ?>);
  var fundData = <?= json_encode($fundData) ?>;
  var pledgeData = <?= json_encode($pledgeTypeData) ?>;

  $(document).ready(function() {
    initPaymentTable();
    initCharts(pledgeData, fundData);
    initDepositSlipEditor();

    $('#deleteSelectedRows').click(function() {
      var deletedRows = dataT.rows('.selected').data();
      bootbox.confirm({
        title:'<?= gettext("Confirm Delete")?>',
        message: '<p><?= gettext("Are you sure you want to delete the selected")?> ' + deletedRows.length + ' <?= gettext("payments(s)?") ?></p>' +
        '<p><?= gettext("This action CANNOT be undone, and may have legal implications!") ?></p>'+
        '<p><?= gettext("Please ensure this what you want to do.</p>") ?>',
        buttons: {
          cancel : {
            label: '<?= gettext("Close"); ?>'
          },
          confirm: {
            label: '<?php echo gettext("Delete"); ?>'
          }
        },
        callback: function ( result ) {
          if ( result )
          {
            window.CRM.deletesRemaining = deletedRows.length;
            $.each(deletedRows, function(index, value) {
              window.CRM.APIRequest({
                method: 'DELETE',
                path: 'payments/' + value.Groupkey,
              })
              .done(function(data) {
                dataT.rows('.selected').remove().draw(false);
                window.CRM.deletesRemaining --;
                if ( window.CRM.deletesRemaining == 0 )
                {
                  location.reload();
                }
              });
              });
          }
        }
      })
    });
  });
</script>

<?php 
require SystemURLs::getDocumentRoot() . '/Include/Footer.php';
?>