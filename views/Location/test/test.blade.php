@if(app()->getLocale() == 'en')
<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
@else
<script src="https://sdk.accountkit.com/vi_VN/sdk.js"></script>
@endif


	<div class="col-12 mt-5">
		<div class="text-center">
			<button class="btn btn-primary" onclick="smsLogin();">Confirm You Phone</button>
		</div>
	</div>



<script>
	var access_token = 'AA|1989720184605994|dbff752d2cb6a5902d2ecd37cb765941';
  // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId:"1989720184605994",
        state:"{{csrf_token()}}", 
        version:"v1.1",
        fbAppEventsEnabled:true,
        redirect:"develop.kingmap.vn/test",
        debug: true
      }
    );
  };

  // login callback
  function loginCallback(response) {
  	console.log(response);
    if (response.status === "PARTIALLY_AUTHENTICATED") {
      var code = response.code;
      var csrf = response.state;
      $.ajax({
      	type:'GET',
      	url: 'https://graph.accountkit.com/v1.1/access_token?grant_type=authorization_code'+'&access_token='+access_token+'&code='+code,
      	success: function(res){
      		var new_token = res.access_token;
      		$.ajax({
		      	type:'GET',
		      	url: 'https://graph.accountkit.com/v1.1/me?access_token='+new_token,
		      	success: function(new_res){
		      		alert("You phone has been comfirm: " + new_res.phone.number);
		      	}
		      });
      	}
      });
      // Send code to server to exchange for access token
    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
    }
  }

  // phone form submission handler
  function smsLogin() {
    var countryCode = "+84";
    var phoneNumber = "";
    AccountKit.login(
      'PHONE', 
      {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
      loginCallback
    );
  }


  // email form submission handler
  function emailLogin() {
    var emailAddress = document.getElementById("email").value;
    AccountKit.login(
      'EMAIL',
      {emailAddress: emailAddress},
      loginCallback
    );
  }
</script>

