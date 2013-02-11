<style type="text/css">
	iframe {
		width: 100%;
		height: 100%;
		border: none;
	}
</style>
<script language="javascript">
	function resize_iframe() {
		var height = window.innerWidth;
		if (document.body.clientHeight) {
			height = document.body.clientHeight;
		}
		document.getElementById("frame").style.height =
			parseInt(height - document.getElementById("frame").offsetTop - 110) + "px";
	}
	window.onresize=resize_iframe;
</script>
<iframe src="/webchat/index.html?=<?php echo time() ?>" onload="resize_iframe()" id="frame"></iframe>