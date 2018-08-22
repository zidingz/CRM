<?php

use ChurchCRM\dto\SystemURLs;

require SystemURLs::getDocumentRoot() . '/Include/SimpleConfig.php';
require SystemURLs::getDocumentRoot() . '/Include/Header.php';
?>

<div class="row">
  <?php
  echo $this->fetch('payment-details-bank.php', $data); 
  echo $this->fetch('payment-fund-split.php', $data); 
  ?>
  
</div>

<script nonce="<?= SystemURLs::getCSPNonce() ?>" >
  $(document).ready(function() {

    $("#FamilyName").select2({
      minimumInputLength: 2,
      ajax: {
          url: function (params){
            var a = window.CRM.root + '/api/families/search/'+ params.term;
            return a;
          },
          dataType: 'json',
          delay: 250,
          data: "",
          processResults: function (data, params) {
            var results = [];
            var families = JSON.parse(data).Families
            $.each(families, function(key, object) {
              results.push({
                id: object.Id,
                text: object.displayName
              });
            });
            return {
              results: results
            };
          }
        }
    });

    $("#FamilyName").on("select2:select", function (e) {
      $('[name=FamilyID]').val(e.params.data.id);
    });

    $("#FundTable").DataTable({
        "language": {
            "url": window.CRM.plugin.dataTable.language.url
        },
        responsive:true,
        paging: false,
        searching: false,
        "dom": window.CRM.plugin.dataTable.dom,
        ajax: {
            url: window.CRM.root + "/api/finance/funds",
            "dataSrc":""
        },
        columns: [
          {
              title: i18next.t('FundName'),
              data: 'Name'
          },
          {
              title: i18next.t('Amount'),
              data: '',
              render: function (data, type, full, meta) {
                console.log(full);
                  return '<input class="FundAmount" type="number" step="any" name="'+full.Id+'_Amount" id="'+full.Id+'_Amount" value="">';
              }
          }
        ],
                
    });


    $(".FundAmount").change(function(){
      CalculateTotal();
    });

    $("#Method").change(function() {
      EvalCheckNumberGroup();
    });

    EvalCheckNumberGroup();
    CalculateTotal();
  });

  function EvalCheckNumberGroup()
  {
    if ($("#Method option:selected").val()==="CHECK") {
      $("#checkNumberGroup").show();
    }
    else
    {
      $("#checkNumberGroup").hide();
      $("#CheckNo").val('');
    }
  }
  function CalculateTotal() {
    var Total = 0;
      $(".FundAmount").each(function(object){
        var FundAmount = Number($(this).val());
        if (FundAmount >0 )
        {
          Total += FundAmount;
        }
      });
      $("#TotalAmount").val(Total);
  }
</script>


<?php 
require SystemURLs::getDocumentRoot() . '/Include/Footer.php';
?>