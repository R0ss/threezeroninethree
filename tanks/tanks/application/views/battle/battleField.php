<!DOCTYPE html>

<html>
	<head>
	<meta charset='utf-8'> 
	<link type="text/css" rel="stylesheet" href="<?=base_url()?>/css/battle/tank_image.css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script src="<?= base_url() ?>/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="<?=base_url()?>/js/battle/tank_functions_<?php echo $player?>.js"></script>
	<script>

		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var enemy_pause = setTimeout(update_enemy, 200);
		var invitation_pause = setTimeout(invitation_check,2000);
		var player_lost = false;
		var enemy_lost = false;
		
		var distance;
		function youreHit(tank_x, tank_y, shot_x, shot_y){
		  return Math.sqrt(Math.pow(tank_x - shot_x, 2) + Math.pow(tank_y - shot_y, 2)) < 55;
		  // 55 is about the general vicinity of the tank.
		}
		
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

          if (data.enemy_shot == 1){
					  $("#<?=$enemy?>_laser").offset({left: data.enemy_x1, top: data.enemy_y1 - 33}).fadeIn(300)
				    .css("display", "block").animate({top: data.enemy_y2, left: data.enemy_x2}, 400);
				    
				    if (youreHit(tank_x, tank_y, data.enemy_x2, data.enemy_y2) == true){
				      tank_hit = 1;
				    }
          }          
				}
				if(data.enemy_hit == true){
					$("#<?php echo $enemy ?>").hide("explode", 1000);
					enemy_lost = true;
					;
				}
				
			});
			
			var url_post = "<?= base_url() ?>combat/postIntel";
			$.ajax({ // Nothing else needs to be done, other than posting the data
				  url: url_post,
				  data:
					{ 
					  'x1': (tank_x - 36),
					  'y1': (tank_y - 36),
				      'x2': target_x,
				      'y2': target_y,
				      'angle': turret_degree,
				      'shot': fire_cannon,
				      'hit': tank_hit
				    },
				  type: 'post',
				});


			if(tank_hit == true){
				player_lost = true;
			     $("#<?php echo $player?>").hide("explode", 1000);
			     
				}
			enemy_pause = setTimeout(update_enemy, 200);
			// Determine which player won
			if(player_lost && enemy_lost){
				end_game("draw");
				}
			else if (player_lost){
				end_game("enemy_won");
				}
			else if (enemy_lost){
				end_game("player_won")
				}
			
			fire_cannon = 0;	
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
			// send results to endPhase function
			function end_game(winner){
				var url_winner = "<?= base_url() ?>combat/endPhase";
				$.ajax({ // Provide results of battle
					  url: url_winner,
					  data:{'winner': winner},
					  type: 'post',
					});
				if (winner == "draw"){
					alert("You have tied with your opponent.");
					window.location.href = '<?= base_url() ?>arcade/index';
				} else if (winner == "player_won"){
					alert("Congratulations you've won!");
					window.location.href = '<?= base_url() ?>arcade/index';
				} else {
					alert("YOU LOSE");
					window.location.href = '<?= base_url() ?>arcade/index';	
				}
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

 	    <div id="player1_box"></div>
	    <div id="player2_box"></div>
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

