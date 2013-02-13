<style type="text/css">
	#frame {
		width: 100%;
		height: 100%;
		border: none;
	}
</style>
<script language="javascript">
	function resize_iframe() {
		var height = window.innerHeight;
		if (document.body.clientHeight) {
			height = Math.min(document.body.clientHeight, height);
		}
		document.getElementById("frame").style.height =
			parseInt(height - document.getElementById("frame").offsetTop - 110) + "px";
	}
	window.onresize=resize_iframe;
</script>
<iframe src="/webchat/index.html?time=<?php echo time() ?><?php echo
(empty($sets['user']['name']) ? '' : '&nick=' . urlencode($sets['user']['name'])); ?>"
  onload="resize_iframe()" id="frame"></iframe>