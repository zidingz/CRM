<div class="col-lg-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?= gettext("Fund Split") ?></h3>
      </div>
        <div class="box-body">
          <table id="FundTable">
            <thead>
              <tr>
                <th class="<?= $PledgeOrPayment == 'Pledge' ? 'LabelColumn' : 'PaymentLabelColumn' ?>"><?= gettext('Fund Name') ?></th>
                <th class="<?= $PledgeOrPayment == 'Pledge' ? 'LabelColumn' : 'PaymentLabelColumn' ?>"><?= gettext('Amount') ?></th>

                <?php if ($bEnableNonDeductible) {
        ?>
                  <th class="<?= $PledgeOrPayment == 'Pledge' ? 'LabelColumn' : 'PaymentLabelColumn' ?>"><?= gettext('Non-deductible amount') ?></th>
                <?php
    } ?>

                <th class="<?= $PledgeOrPayment == 'Pledge' ? 'LabelColumn' : 'PaymentLabelColumn' ?>"><?= gettext('Comment') ?></th>
             </tr>
            </thead>
            <tbody>
              <?php
              foreach ($fundId2Name as $fun_id => $fun_name) {
                  ?>
                <tr>
                  <td class="TextColumn"><?= $fun_name ?></td>
                  <td class="TextColumn">
                    <input class="FundAmount" type="number" step="any" name="<?= $fun_id ?>_Amount" id="<?= $fun_id ?>_Amount" value="<?= ($nAmount[$fun_id] ? $nAmount[$fun_id] : "") ?>"><br>
                    <font color="red"><?= $sAmountError[$fun_id] ?></font>
                  </td>
                  <?php
                    if ($bEnableNonDeductible) {
                        ?>
                      <td class="TextColumn">
                        <input type="number" step="any" name="<?= $fun_id ?>_NonDeductible" id="<?= $fun_id ?>_NonDeductible" value="<?= ($nNonDeductible[$fun_id] ? $nNonDeductible[$fun_id] : "")?>" />
                        <br>
                        <font color="red"><?= $sNonDeductibleError[$fun_id]?></font>
                      </td>
                    <?php
                    } ?>
                  <td class="TextColumn">
                    <input  type="text" size=40 name="<?= $fun_id ?>_Comment" id="<?= $fun_id ?>_Comment" value="<?= $sComment[$fun_id] ?>">
                  </td>
                </tr>
              <?php
              } ?>
            </tbody>
          </table>
        </div>
    </div>
  </div>