var accessToken, counter;
var result = [], waveData = [];
var musicInterval, waveform;
var min = 999999, max = -1, state = 0;

//// MIDIJS noteOn and noteOff configuration
var velocity = 127;
var delay = 0.05; //sec
var notesInterval = 100; //ms

$(document).ready(function(){
	$("#playButton").click(function(){
		controller();
	});
});

function controller() {
	switch (state) {
		case 0 : { // First Play
			counter = 0;
			state = 1;
			LoginAndRun(createMusic, 'run');
			break;
		} 
		
		case 1: { //Loading
			$("#playButton").find("i").removeClass("fa-play").addClass("fa-spinner").addClass("fa-spin");
			$("#playButton").find("span").text("Generating Your Tune");
			state = 2;
			break;
		}
		
		case 2:{ //Playing
			state = 3;
			$("#playButton").find("i").removeClass("fa-spinner").removeClass("fa-spin").addClass("fa-stop");	
			$("#playButton").find("span").text("Stop Your Tune");
			break;
		}
		
		case 3: { //Stop
			state = 4;
			clearInterval(musicInterval);
			$("#playButton").find("i").removeClass("fa-stop").addClass("fa-play");
			$("#playButton").find("span").text("Play Your Tune");
			$(".uibutton").find("i").removeClass("fa-check").addClass("fa-facebook");
			$(".uibutton").show();
			break;
		} 
		
		case 4: { // 2nd, 3rd and ... Plays
			$(".uibutton").hide();
			$("#waveformOut").find("canvas").empty();
			state = 1;
			waveData = [];
			counter = 0;
			musicInterval = setInterval(playMusic, notesInterval);
			controller();
		}
	}
}

function LoginAndRun(func) {
	var param = arguments[1];
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			func(param);
		}
		else {
			FB.login(function(response) {
				if (response.authResponse) {
					func(param);
				} else {
					alert("Sorry! you did not login");
				}
			}, {scope: 'read_stream, publish_stream'});		
		}
	});
}

function createMusic(param) {
	controller();
	accessToken = FB.getAuthResponse()['accessToken'];
	FB.api({
		method: 'fql.query',
		query: 'select time, like_info.like_count, comment_info.comment_count from status where uid=me()',
		access_token: accessToken
	}, 
	function(data) {				
		for (var i = 0; i < data.length; i++) {
			var s = {
				time: data[i].time,
				count: parseInt(data[i].like_info.like_count) + parseInt(data[i].comment_info.comment_count)
			};
			
			min = min > s.count ? s.count : min;
			max = max < s.count ? s.count : max;
			result.push(s);
		}
		
		waveform = new Waveform({
			container: document.getElementById("waveformOut"),
			interpolate: false
		});
		
		var waveCtx = waveform.context;
		var gradient = waveCtx.createLinearGradient(0, 0, 0, waveform.height);
		gradient.addColorStop(0.0, "#43B8CD");
		gradient.addColorStop(1.0, "#000000");
		waveform.innerColor = gradient;
		
		MIDI.loadPlugin({
			soundfontUrl: "./soundfont/",
			instrument: [ "acoustic_grand_piano", "synth_drum" ],
			callback: function() {
				musicInterval = setInterval(playMusic, notesInterval);
			}
		});
	});
}
	
function playMusic() {
	if (counter == result.length) {
		controller();
		return;
	}
	
	if (counter == 0)
		controller();
	
	MIDI.programChange(0, 0);
	MIDI.programChange(1, 0);
	var note = MIDI.pianoKeyOffset + result[counter].count + 30;
	waveData.push(result[counter].count / (max - min));
	waveform.update({
		data: waveData
	});
	
	MIDI.setVolume(0, 127);
	MIDI.noteOn(0, note, velocity, delay + 0.05);
	//MIDI.noteOff(0, note, delay);
	//MIDI.noteOn(1, note + 3, velocity, delay + 0.05);
			
	counter++;
}

function post() {
	$(".uibutton").find("i").removeClass("fa-facebook").addClass("fa-spinner").addClass("fa-spin");
	var img = document.getElementsByTagName("canvas")[0];
	if (img == null || !img) {
		alert("you haven't played your tune!");
		return;
	}
	
	$.ajax({
		url: "image.php",
		type: 'POST',
		dataType: 'html',
		data: { "img" : img.toDataURL("image/png"), "token": $("#token").val()},
		success: function(url) {
			LoginAndRun(postToFb, url);
		}	
	});
}

function postToFb(url)
{
	var postObj = {
		message:  "I just generated my musical eternitune based on the popularity of my posts and this is my signature!  Try it for yourself at http://eternitune.com",
		//link: "http://eternitune.com",
		picture: url
	}
	
	FB.api('/me/feed', 'post', postObj, function(response){
		$(".uibutton").find("i").removeClass("fa-spinner").removeClass("fa-spin").addClass("fa-check");
	});
}

