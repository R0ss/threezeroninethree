	var tank_x = 10; //tank x coordinates
	var tank_y = 10; // tank y coordinates
	var mouse_x; //mouse x coordinates
	var mouse_y; //mouse y coordinates
	var target_x, target_y; // Cannon target
	var time_out = setTimeout(turret_direction, 200); //begin updating current turret location
	var turret_degree = 0; // direction of turret in degrees
	var player1_angle = 0; // relative angle of mouse to tank
	var fire_cannon = 0;
	var tank_hit = 0;
	
	//determine if tank has been shot by enemy
	function got_hit(){
		tank_hit = 1;		
	}
		
	// update the turret position
	function turret_direction() {
		
		$("#player1_turret") // change turret position to turret_degree
			.css({
				"-moz-transform" : "rotate(" + turret_degree + "deg)"
				});
		tank_x = $( "#player1" ).position().left + 37; //tank x coordinates
		tank_y = $( "#player1" ).position().top + 37; // tank y coordinates
		time_out = setTimeout(turret_direction, 200); // repeat myself
	}
	
	function update_turret(){
		var deltaY = (mouse_x - tank_x); //adjust origin of compass to match turret
		var deltaX = (mouse_y - tank_y);
		player1_angle = (-1 * (Math.round(Math.atan2(deltaY, deltaX)*180/Math.PI)) + 360) % 360; //*-1
	    turret_degree = (turret_degree + 360) % 360;
	
		if (((player1_angle) != turret_degree)){
		    var difference = (player1_angle - turret_degree + 360) % 360;
			if (difference < 180){
				turret_degree = (turret_degree + 1) % 360; //rotate clockwise
			} else if (difference > 180) {
				turret_degree = (turret_degree - 1) % 360; //rotate counter clockwise
			}
		}
		
	}
	
	$(function(){ //fire cannon with mouse
		$(document).mousedown( function(event){
			if(player1_angle == turret_degree){ //only fire when cannon is in position
				fire_cannon = 1;
				target_x = mouse_x;
				target_y = mouse_y;
			  $("#player1_laser")
			    .offset({left: tank_x, top: tank_y - 37})
			    .fadeIn(300)
			    .css("display", "block")
			    .animate({top: mouse_y, left: mouse_x}, 400);
			}
			//tank movement
		}).keydown( function( event ){
	          var keyCode = event.keyCode || event.which;
	          var keyMap = { left: 65, up: 87, right: 68, down: 83}
	          switch ( keyCode ) {
	            case keyMap.left:
	              $( "#player1" ).stop().animate({
	                left: '-=40'
	              }, 100 );
	              update_turret();
	              break;
				
				case keyMap.right:
	              $( "#player1" ).stop().animate({
	                left: '+=40'
	              }, 100 );
	              update_turret();
	              break;
	 
	            case keyMap.up:
	              $( "#player1" ).stop().animate({
	                top: '-=40'
	              }, 100 );
	              update_turret();
	              break;
				
				case keyMap.down:
	              $( "#player1" ).stop().animate({
	                top: '+=40'
	              }, 100 );
	              update_turret();
	              break;
				}
			}).mousemove(function(event){
			    mouse_x = event.pageX;
				mouse_y = event.pageY;
				update_turret();
			});
	});
