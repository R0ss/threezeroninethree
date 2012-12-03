
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
		
		$(function(){
			$('body').everyTime(1000,function(){ //call getIntel function
				var url_get = "<?= base_url() ?>combat/getIntel";
				$.getJSON(url_get, function (data){
					if (data && data.status =='success') { //access variables using dot notation
						// change turret position to turret_degree
						$("#<?php echo $enemy ?>_turret") // change turret position to turret_degree
							.css({
								"transform" : "rotate(" + data.enemy_angle + "deg)"
							});
						$("#<?php echo $enemy ?>") // change tanks position
						.css({
							top : data.enemy_y1,
							left : data.enemy_x1
						});
						$("#test").html(data.enemy_y1 + " , " + data.enemy_x1);
					}
				});
				
				var arguments = {};
				arguments['x1'] = tank_x;
				arguments['y1'] = tank_y;
				arguments['x2'] = mouse_x; // we might consider using cannon angles to fire
				arguments['y2'] = mouse_y;
				arguments['angle'] = turret_degree;
				arguments['shot'] = fire_cannon;
				arguments['hit'] = tank_hit;
				var url_post = "<?= base_url() ?>combat/postIntel";
				$.ajax({ // Nothing else needs to be done, other than posting the data
					  url: url_post,
					  data: arguments,
					  type: 'post',
					});	
			});
			
			$('body').everyTime(2000,function(){
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
					var url = "<?= base_url() ?>combat/getMsg";
					$.getJSON(url, function (data,text,jqXHR){
						if (data && data.status=='success') {
							var conversation = $('[name=conversation]').val();
							var msg = data.message;
							if (msg.length > 0)
								$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
						}
					});
			});

			$('form').submit(function(){
				var arguments = $(this).serialize();
				var url = "<?= base_url() ?>combat/postMsg";
				$.post(url,arguments, function (data,textStatus,jqXHR){
						var conversation = $('[name=conversation]').val();
						var msg = $('[name=msg]').val();
						$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
						});
				return false;
				});	
		});
	
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
		
	<div id="chatbox">
        <?php 
	
	        echo form_textarea('conversation');
	
	        echo form_open();
	        echo form_input('msg');
	        echo form_submit('Send','Send');
	        echo form_close();
	
        ?>
	</div>
	
	
	
</body>

</html>

