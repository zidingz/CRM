<div class="box-body">
  <div class="col-lg-6">
    <canvas id="type-donut" style="height:250px"></canvas>
    <ul style="margin:0px; border:0px; padding:0px;">
      <li>
        <b><?=gettext("Total")?>: (<?=$thisDeposit->getPledges()->count()?>):</b> $<?= $thisDeposit->getVirtualColumn('totalAmount')?>
      </li>
      <li>
        <b><?=gettext("Cash")?>: (<?=$thisDeposit->getCountCash()?>):</b> $<?=$thisDeposit->getTotalCash()?>
      </li>
      <li>
        <b><?=gettext("Checks")?>: (<?=$thisDeposit->getCountChecks()?>:</b> $<?=$thisDeposit->getTotalChecks()?>
      </li>
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