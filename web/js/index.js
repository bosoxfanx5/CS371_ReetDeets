$(document).ready(function() {
	$("html, body").animate({ scrollTop: 1000 }, 2000);
	
	$("#forgot").click(function() {
		$("#resetPass").fadeIn("slow");



	});

	$("#closeSet").click(function() {
		$("#resetPass").fadeOut("slow");



	});






	$("#createNew").click(function() {
		$("#creation").fadeIn("slow");



	});


	$("#closeCreate").click(function() {
		$("#creation").fadeOut("slow");



	});

	$("#loginBtn").click(function() {
		$("#login").fadeIn("slow");


	});


	$("#closeLogin").click(function() {
		$("#login").fadeOut("slow");



	});

	$("#features").click(function() {
		$("#features-review").fadeIn("slow");


	});

	$("#reviewLink").click(function() {
		$("#features-review").fadeIn("slow");


	});


	$("#closeComing").click(function() {
		$("#features-review").fadeOut("slow");



	});

});
