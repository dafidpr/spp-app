<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?=get_field('5','settings','meta_value')?>"></script>

<div class="content">

  <div class="panel">

    <div class="content-header no-mg-top">

      <i class="fa fa-credit-card"></i>

      <div class="content-header-title">Status Pembayaran</div>

    </div>

    <div class="row">

      <div class="col-md-12">

        <div class="content-box">

          <div class="row">

            <div class="col-md-12">

              <div class="form-group row">

                <div class="col-sm-2"><label for=""> Status Message</label></div>

                <div class="col-sm-10"><label for=""> Transaksi sedang diproses</label></div>

              </div>

              <div class="form-group row">

                <div class="col-sm-2"><label for=""> Order ID</label></div>

                <div class="col-sm-10"><label for=""> <?=$order_id?></label></div>

              </div>

              <div class="form-group row">

                <div class="col-sm-2"><label for=""> Gross Amount</label></div>

                <div class="col-sm-10"><label for=""> <?=number_format($gross_amount,0,',','.')?></label></div>

              </div>

              <div class="form-group row">

                <div class="col-sm-2"><label for=""> Transaction Status</label></div>

                <div class="col-sm-10"><label for=""> <?=$transaction?></label></div>

              </div>
            

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>











<form id="payment-form" method="post" action="<?=site_url()?>payment/finish">

  <input type="hidden" name="result_type" id="result-type" value="">

  <input type="hidden" name="result_data" id="result-data" value="">

</form>



<div id="pay-button"></div>



<script type="text/javascript"> 

  $('#pay-button').click(function (event) {

    event.preventDefault();

    $(this).attr("disabled", "disabled");



    $.ajax({

      url: '<?=site_url()?>snap/token',

      cache: false,



      success: function(data) {

        //location = data;



        console.log('token = '+data);

        

        var resultType = document.getElementById('result-type');

        var resultData = document.getElementById('result-data');



        function changeResult(type,data){

          $("#result-type").val(type);

          $("#result-data").val(JSON.stringify(data));

          //resultType.innerHTML = type;

          //resultData.innerHTML = JSON.stringify(data);

        }



        snap.pay(data, {



          onSuccess: function(result){

            changeResult('success', result);

            console.log(result.status_message);

            console.log(result);

            $("#payment-form").submit();

          },

          onPending: function(result){

            changeResult('pending', result);

            console.log(result.status_message);

            $("#payment-form").submit();

          },

          onError: function(result){

            changeResult('error', result);

            console.log(result.status_message);

            $("#payment-form").submit();

          }

        });

      }

    });

  });

</script>





<script type="text/javascript">

  $(document).ready(function() {

    $('#pay-button').trigger('click');

  })

</script>

