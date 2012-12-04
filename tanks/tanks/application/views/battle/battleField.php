<!DOCTYPE html>

<html>
	<head>
	<meta charset='utf-8'> 
	<link type="text/css" rel="stylesheet" href="<?=base_url()?>/css/battle/tank_image.css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script src="<?=base_url()?>/js/battle/tank_functions_<?php echo $player?>.js"></script>
	<script>

		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var enemy_pause = setTimeout(update_enemy, 500);
		var invitation_pause = setTimeout(invitation_check,2000);
		
		function update_enemy() { //call getIntel function
			var url_get = "<?= base_url() ?>combat/getIntel";
			$.getJSON(url_get, function (data,jqXHR){
				if (data && data.status =='success') { //access variables using dot notation
					// change turret position to turret_degree
					$("#<?php echo $enemy ?>_turret") // change turret position to turret_degree
						.css({
							"-moz-transform" : "rotate(" + data.enemy_angle + "deg)"
						});
					$("#<?php echo $enemy ?>") // change tanks position
					.animate({
						top : data.enemy_y1,
						left : data.enemy_x1
					}, 0);
				}
			});
			
			var url_post = "<?= base_url() ?>combat/postIntel";
			$.ajax({ // Nothing else needs to be done, other than posting the data
				  url: url_post,
				  data:
					{ 
					  'x1': (tank_x - 36),
					  'y1': (tank_y - 36),
				      'x2': mouse_x,
				      'y2': mouse_y,
				      'angle': turret_degree,
				      'shot': fire_cannon,
				      'hit': tank_hit
				    },
				  type: 'post',
				});
			enemy_pause = setTimeout(update_enemy, 500);	
		}
			
			function invitation_check() {
					if (status == 'waiting') {
						$.getJSON('<?= base_url() ?>arcade/checkInvitation',function(data, text, jqZHR){
								if (data && data.status=='rejected') {
									alert("Sorry, your invitation to battle was declined!");
									window.location.href = '<?= base_url() ?>arcade/index';
								}
								if (data && data.status=='accepted') {
									status = 'battling';
									$('#status').html('Battling ' + otherUser);
								}
								
						});
					}
					invitation_pause = setTimeout(invitation_check, 2000);
			}
	
	</script>
	</head> 
<body>

	<!--------------------------------
	           BATTLE FIELD 
	---------------------------------->
	<div id="battlefield">
	    <div id="player1" >
		    <div id="player1_turret">
			    <div id="player1_cannon"></div>
		    </div>
	    </div>
	    <div id="player2" >
		    <div id="player2_turret">
			    <div id="player2_cannon"></div>
		    </div>
	    </div>
	    <div id="player1_laser"></div>
	    <div id="player2_laser"></div>
	</div>
	<p id="test"></p>
	<h1>Battle Field</h1>

	<div>
	Hello <?= $user->fullName() ?>  <?= anchor('account/logout','(Logout)') ?>  <?= anchor('account/updatePasswordForm','(Change Password)') ?>
	</div>
	
	<div id='status'> 
	<?php 
		if ($status == "battling")
			echo "Battling " . $otherUser->login;
		else
			echo "Wating on " . $otherUser->login;
	?>
	</div>	
	
</body>

</html>

