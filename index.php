<?php

session_start();
$token = md5(date('U').mt_rand()) . rand() . md5(uniqid() . mt_rand());
$_SESSION['token_id'] = $token;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="Generate an audible tune based on your Facebook post popularity and activity.">
		
		<link rel="shortcut icon" href="img/favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/custom.css">
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->	
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/MIDI/AudioDetect.js" type="text/javascript"></script>
		<script src="js/MIDI/LoadPlugin.js" type="text/javascript"></script>
		<script src="js/MIDI/Plugin.js" type="text/javascript"></script>
		<script src="js/MIDI/Player.js" type="text/javascript"></script>
		<script src="js/Window/DOMLoader.XMLHttp.js" type="text/javascript"></script>
		<script src="js/inc/Base64.js" type="text/javascript"></script>
		<script src="js/inc/Base64binary.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js" type="text/javascript"></script> 
		<script src="js/waveform.js" type="text/javascript"></script> 

		<title>Eternitune</title>
	</head>

	<body>
		<!--facebook required DIV -->
		<div id="fb-root"></div>
		<div class="navbar navbar-default navbar-static-top" role="navigation">
			<div class="container">
				<div class="navbar-header"> 
					<a class="navbar-brand" href="http://eternitune.com">
						<img src="img/logo.png" alt="Eternitune Logo"/>
					</a>
				</div>
			</div>
		</div>


		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">What is Eternitune?</h4>
					</div>
					<div class="modal-body"> 
						Eternitune takes your Facebook timeline and assigns a keytone to each activity depending on it's popularity,  generating your unique musical "Chimeline". 
						<br/><br/>
						<img class="center-block" src="/img/chimeline.png">
						<br/>
						Eternitune was conceived, designed and created during the eAudit 12hr Hack Challenge on 3/22/2014 by the band of misfits below. 
						<br/><br/>
						<img src="/img/2.jpg" width="100%">
						<img src="/img/6.jpg" width="100%">
						<img src="/img/3.jpg" width="100%">
						<img src="/img/1.jpg" width="100%">
						<img src="/img/4.jpg" width="100%">
						<img src="/img/5.jpg" width="100%"> 
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="container"> 
			<div class="jumbotron" style="border-radius:0px 30px 30px">
				<h1>What's your tune?</h1>
				<p>Generate your own unique musical eternitune based on your Facebook activity.<br></p>
				<div class="row">
					<div class="col-md-3" style="height:100px;"> <br/>
						<button type="button" class="btn btn-primary btn-lg" id="playButton"> 
							<i class="fa fa-play"></i> &nbsp;
							<span>Play Your Tune<span>
						</button>
						<br/>
						<br/>
						<a href="#" data-toggle="modal" data-target="#myModal"> What is Eternitune?</a>
					</div>
					
					<div class="col-md-4">
						<div id="waveformOut" style="height:100px;"></div>
						<div class="uibutton confirm" style="display:none" onclick="post()">
							<i class="fa fa-facebook"></i> &nbsp; Post Chimeline 
						</div>
						<input id="token" type="hidden" value="<?php echo $token; ?>">
					</div>
                    <div class="col-md-3">
						<div class="fb-like" data-href="http://eternitune.com" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
					</div>
				</div>
			</div>	
		</div>


		<script>
		  (function(d){
			 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));
		   
			 window.fbAsyncInit = function() {
				FB.init({
				  appId      : '276094882557467', 
				  channelUrl : 'https://eternitune.com/channel.html', 
				  status     : true, 
				  cookie     : true, 
				  xfbml      : true  
				});
			};
			
		</script> 
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<script src="js/main.js"></script>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-640330-26', 'eternitune.com');
			ga('send', 'pageview');
		</script>
	</body>
</html>