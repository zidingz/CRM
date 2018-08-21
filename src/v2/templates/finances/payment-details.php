<?php
use ChurchCRM\dto\SystemConfig;
?>
<div class="col-lg-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?= gettext("Payment Details") ?></h3>
      </div>
      <div class="box-body">
        <input type="hidden" name="FamilyID" id="FamilyID" value="<?= $iFamily ?>">
        <input type="hidden" name="PledgeOrPayment" id="PledgeOrPayment" value="<?= $PledgeOrPayment ?>">

        <div class="col-lg-12">
          <label for="FamilyName"><?= gettext('Family') ?></label>
          <select class="form-control"   id="FamilyName" name="FamilyName" >
            <option selected ><?= $sFamilyName ?></option>
          </select>
        </div>

        <div class="col-lg-6">
          <?php	if (!$dDate) {
    $dDate = $dep_Date;
} ?>
          <label for="Date"><?= gettext('Date') ?></label>
          <input class="form-control" data-provide="datepicker" data-date-format='yyyy-mm-dd' type="text" name="Date" value="<?= $dDate ?>" ><font color="red"><?= $sDateError ?></font>
          <label for="FYID"><?= gettext('Fiscal Year') ?></label>
           <?php PrintFYIDSelect($iFYID, 'FYID') ?>

          <?php if ($dep_Type == 'Bank' && SystemConfig::getValue('bUseDonationEnvelopes')) {
    ?>
          <label for="Envelope"><?= gettext('Envelope Number') ?></label>
          <input  class="form-control" type="number" name="Envelope" size=8 id="Envelope" value="<?= $iEnvelope ?>">
          <?php if (!$dep_Closed) {
        ?>
            <input class="form-control" type="submit" class="btn" value="<?= gettext('Find family->') ?>" name="MatchEnvelope">
          <?php
    } ?>

        <?php
} ?>

            <?php if ($PledgeOrPayment == 'Pledge') {
        ?>

        <label for="Schedule"><?= gettext('Payment Schedule') ?></label>
          <select name="Schedule" class="form-control">
              <option value="0"><?= gettext('Select Schedule') ?></option>
              <option value="Weekly" <?php if ($iSchedule == 'Weekly') {
            echo 'selected';
        } ?>><?= gettext('Weekly') ?>
              </option>
              <option value="Monthly" <?php if ($iSchedule == 'Monthly') {
            echo 'selected';
        } ?>><?= gettext('Monthly') ?>
              </option>
              <option value="Quarterly" <?php if ($iSchedule == 'Quarterly') {
            echo 'selected';
        } ?>><?= gettext('Quarterly') ?>
              </option>
              <option value="Once" <?php if ($iSchedule == 'Once') {
            echo 'selected';
        } ?>><?= gettext('Once') ?>
              </option>
              <option value="Other" <?php if ($iSchedule == 'Other') {
            echo 'selected';
        } ?>><?= gettext('Other') ?>
              </option>
          </select>

          <?php
    } ?>

      </div>


      <div class="col-lg-6">
        <label for="Method"><?= gettext('Payment by') ?></label>
        <select class="form-control" name="Method" id="Method">
          <?php if ($PledgeOrPayment == 'Pledge' || $dep_Type == 'Bank' || !$iCurrentDeposit) {
        ?>
            <option value="CHECK" <?php if ($iMethod == 'CHECK') {
            echo 'selected';
        } ?>><?= gettext('Check'); ?>
            </option>
            <option value="CASH" <?php if ($iMethod == 'CASH') {
            echo 'selected';
        } ?>><?= gettext('Cash'); ?>
            </option>
              <?php
    } ?>
          <?php if ($PledgeOrPayment == 'Pledge' || $dep_Type == 'CreditCard' || !$iCurrentDeposit) {
        ?>
            <option value="CREDITCARD" <?php if ($iMethod == 'CREDITCARD') {
            echo 'selected';
        } ?>><?= gettext('Credit Card') ?>
            </option>
          <?php
    } ?>
          <?php if ($PledgeOrPayment == 'Pledge' || $dep_Type == 'BankDraft' || !$iCurrentDeposit) {
        ?>
            <option value="BANKDRAFT" <?php if ($iMethod == 'BANKDRAFT') {
            echo 'selected';
        } ?>><?= gettext('Bank Draft') ?>
            </option>
          <?php
    } ?>
          <?php if ($PledgeOrPayment == 'Pledge') {
        ?>
            <option value="EGIVE" <?= $iMethod == 'EGIVE' ? 'selected' : '' ?>>
             <?=gettext('eGive') ?>
            </option>
          <?php
    } ?>
        </select>



        <?php if ($PledgeOrPayment == 'Payment' && $dep_Type == 'Bank') {
        ?>
          <div id="checkNumberGroup">
          <label for="CheckNo"><?= gettext('Check') ?> #</label>
          <input class="form-control" type="number" name="CheckNo" id="CheckNo" value="<?= $iCheckNo ?>"/><font color="red"><?= $sCheckNoError ?></font>
          </div>
        <?php
    } ?>


        <label for="TotalAmount"><?= gettext('Total $') ?></label>
        <input class="form-control"  type="number" step="any" name="TotalAmount" id="TotalAmount" disabled />

    </div>


    <?php
    if ($dep_Type == 'CreditCard' || $dep_Type == 'BankDraft') {
        ?>
    <div class="col-lg-6">

            <tr>
              <td class="<?= $PledgeOrPayment == 'Pledge' ? 'LabelColumn' : 'PaymentLabelColumn' ?>"><?= gettext('Choose online payment method') ?></td>
              <td class="TextColumnWithBottomBorder">
                <select name="AutoPay">
      <?php
                          echo '<option value=0';
        if ($iAutID == 0) {
            echo ' selected';
        }
        echo '>'.gettext('Select online payment record')."</option>\n";
        $sSQLTmp = 'SELECT aut_ID, aut_CreditCard, aut_BankName, aut_Route, aut_Account FROM autopayment_aut WHERE aut_FamID='.$iFamily;
        $rsFindAut = RunQuery($sSQLTmp);
        while ($aRow = mysqli_fetch_array($rsFindAut)) {
            extract($aRow);
            if ($aut_CreditCard != '') {
                $showStr = gettext('Credit card ...').mb_substr($aut_CreditCard, strlen($aut_CreditCard) - 4, 4);
            } else {
                $showStr = gettext('Bank account ').$aut_BankName.' '.$aut_Route.' '.$aut_Account;
            }
            echo '<option value='.$aut_ID;
            if ($iAutID == $aut_ID) {
                echo ' selected';
            }
            echo '>'.$showStr."</option>\n";
        } ?>
                </select>
              </td>
            </tr>

      </div>
    <?php
    } ?>

    <div class="col-lg-6">
       <?php if (SystemConfig::getValue('bUseScannedChecks') && ($dep_Type == 'Bank' || $PledgeOrPayment == 'Pledge')) {
        ?>
          <td align="center" class="<?= $PledgeOrPayment == 'Pledge' ? 'LabelColumn' : 'PaymentLabelColumn' ?>"><?= gettext('Scan check') ?>
          <textarea name="ScanInput" rows="2" cols="70"><?= $tScanString ?></textarea></td>
        <?php
    } ?>
    </div>

    <div class="col-lg-6">
      <?php if (SystemConfig::getValue('bUseScannedChecks') && $dep_Type == 'Bank') {
        ?>
        <input type="submit" class="btn" value="<?= gettext('find family from check account #') ?>" name="MatchFamily">
        <input type="submit" class="btn" value="<?= gettext('Set default check account number for family') ?>" name="SetDefaultCheck">
      <?php
    } ?>
    </div>

    <div class="col-lg-12">
    <?php if (!$dep_Closed) {
        ?>
        <input type="submit" class="btn " value="<?= gettext('Save') ?>" name="PledgeSubmit">
        <?php if ($_SESSION['user']->isAddRecordsEnabled()) {
            echo '<input type="submit" class="btn btn-primary value="'.gettext('Save and Add').'" name="PledgeSubmitAndAdd">';
        } ?>
          <?php
    } ?>
    <?php if (!$dep_Closed) {
        $cancelText = 'Cancel';
    } else {
        $cancelText = 'Return';
    } ?>
    <input type="button" class="btn btn-danger" value="<?= gettext($cancelText) ?>" name="PledgeCancel" onclick="javascript:document.location='<?= $linkBack ? $linkBack : 'Menu.php' ?>';">
    </div>
  </div>
</div>
  </div>