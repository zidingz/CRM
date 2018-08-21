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
          </table>
        </div>
    </div>
  </div>


