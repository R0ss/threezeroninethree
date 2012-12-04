	var tank_x = 910; //tank x coordinates
	var tank_y = 720; // tank y coordinates
	var mouse_x; //mouse x coordinates
	var mouse_y; //mouse y coordinates
	var time_out = setTimeout(turret_direction, 200); //begin updating current turret location
	var turret_degree = 180; // direction of turret in degrees
	var player2_angle = 0; // relative angle of mouse to tank
	var fire_cannon = false;
	var tank_hit = false;
	
	//determine if tank has been shot by enemy
	function got_hit(){

		tank_hit = true;		
	}
	
	// update the turret position
	function turret_direction() {
		$("#player2_turret") // change turret position to turret_degree
			.css({
				"-moz-transform" : "rotate(" + turret_degree + "deg)"
				});
		tank_x = $( "#player2" ).position().left + 37; //tank x coordinates
		tank_y = $( "#player2" ).position().top + 37; // tank y coordinates
		time_out = setTimeout(turret_direction, 200); // repeat myself
	}
		
	function update_turret(){
		var deltaY = (mouse_x - tank_x); //adjust origin of compass to match turret
		var deltaX = (mouse_y - tank_y);
		player2_angle = (-1 * (Math.round(Math.atan2(deltaY, deltaX)*180/Math.PI)) + 360) % 360; //*-1
	    turret_degree = (turret_degree + 360) % 360;
	
		if (((player2_angle) != turret_degree)){
		    var difference = (player2_angle - turret_degree + 360) % 360;
			if (difference < 180){
				turret_degree = (turret_degree + 1) % 360; //rotate clockwise
			} else if (difference > 180) {
				turret_degree = (turret_degree - 1) % 360; //rotate counter clockwise
			}
		}
		
	}
	
	$(function(){ //fire cannon with mouse
		$(document).mousedown( function(event){
			if(player2_angle == turret_degree){ //only fire when cannon is in position
				fire_cannon = true;
				$("#player2_laser")
				.offset({left: tank_x, top: tank_y - 37})
				.fadeIn(300)
				.css("display", "block")
				.animate({top: mouse_y, left: mouse_x}, 900);
			}
			//tank movement
		}).keydown( function( event ){
	          var keyCode = event.keyCode || event.which;
	          var keyMap = { left: 65, up: 87, right: 68, down: 83}
	          switch ( keyCode ) {
	            case keyMap.left:
	              $( "#player2" ).stop().animate({
	                left: '-=40'
	              }, 100 );
	              update_turret();
	              if (tank_x < 500){
	            	  tank_x += 500;
	              }
	              break;

				
				case keyMap.right:
	              $( "#player2" ).stop().animate({
	                left: '+=40'
	              }, 100 );
	              update_turret();
	              break;
	 
	            case keyMap.up:
	              $( "#player2" ).stop().animate({
	                top: '-=40'
	              }, 100 );
	              update_turret();
	              break;
				
				case keyMap.down:
	              $( "#player2" ).stop().animate({
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