<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<title>Error - SchoolPay</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet">

<body class="gray-bg">
    <div id="error_list">
	    <div class="middle-box text-center animated fadeInDown">
    		<center>
    			<img style="width: 200px; padding-bottom:15px" src="http://pngimg.com/uploads/attention/attention_PNG5.png">
    		</center>
	    </div>
	    <div class="middle-box text-center animated fadeInDown" >
			<h4>A PHP Error was encountered</h4>
			<p>Severity: <?php echo $severity; ?></p>
			<p>Message:  <?php echo $message; ?></p>
			<p>Filename: <?php echo $filepath; ?></p>
			<p>Line Number: <?php echo $line; ?></p>

			<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
				<p>Backtrace:</p>
				<?php foreach (debug_backtrace() as $error): ?>
					<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
						<p style="margin-left:10px">
							File: <?php echo $error['file'] ?><br />
							Line: <?php echo $error['line'] ?><br />
							Function: <?php echo $error['function'] ?>
						</p>
					<?php endif ?>
				<?php endforeach ?>
			<?php endif ?>

			<h4>Please tell Admin for this Error</h4>
		</div>
	</div> 
	
	<br>

	<div class="text-center animated fadeInDown">
		<button class="btn btn-primary m-t" id="btnReport" onclick="SendMail()">Back To Home And Send This Error</button>
	</div>
</body>
	
<script type="text/javascript">	
	var site 		= window.location.href;
	var bodyHtml 	= document.getElementById("error_list").innerHTML; 
	var subject 	= 'Error in :' + site;
	var to 			= 'support@ifcacloud.com'
	var msg 		= bodyHtml;

	function SendMail(){
		var snd 	= "to="+to+"&subject="+subject+"&message="+msg+""; 
		var path 	= window.location.pathname;
		var dd 		= path.split("/");	
		var url 	= window.location.origin+"/"+dd[1]+'/c_api_email/POST_EMAIL_API';	
		var xhttp 	= new XMLHttpRequest();

		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {      
				window.location.href = window.location.origin+"/"+dd[1];
			}
		};
		
		xhttp.open("POST", url, true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(snd);
	}
</script>