<?php

use ChurchCRM\dto\SystemURLs;

require SystemURLs::getDocumentRoot() . '/Include/SimpleConfig.php';
require SystemURLs::getDocumentRoot() . '/Include/Header.php';
?>

<div class="row">
  <?php
  echo $this->fetch('payment-details.php', $data); 
  echo $this->fetch('payment-fund-split.php', $data); 
  ?>
  
</div>


<?php 
require SystemURLs::getDocumentRoot() . '/Include/Footer.php';
?>