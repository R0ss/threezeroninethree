	var tank_x = 0; //tank x coordinates
	var tank_y = 0; // tank y coordinates
	var mouse_x; //mouse x coordinates
	var mouse_y; //mouse y coordinates
	var time_out = setTimeout(turret_direction, 25); //begin updating current turret location
	var turret_degree = 0; // direction of turret in degrees
	var player1_angle = 0; // relative angle of mouse to tank
	var fire_cannon = 0;
	var tank_hit = 0;
	
	//determine if tank has been shot by enemy
	function got_hit(){

		tank_hit = 0;		
	}
	
	var angle = 0;
	//current mouse position
	$(document).mousemove(function(e){
      mouse_x = e.pageX;
	  mouse_y = e.pageY;
	});
	
	//current tank position
	function tank_pos(){
		tank_x = $( "#player1" ).position().left + 45; //tank x coordinates
		tank_y = $( "#player1" ).position().top + 75; // tank y coordinates
	}
	
	// update the turret position
	function turret_direction() {
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
		$("#player1_turret") // change turret position to turret_degree
			.css({
				"-moz-transform" : "rotate(" + turret_degree + "deg)"
				});
		time_out = setTimeout(turret_direction, 25); // repeat myself
	}
	
	$(function(){ //fire cannon with mouse
		$(document).mousedown( function(event){
			if(player1_angle == turret_degree){ //only fire when cannon is in position
				fire_cannon = 0;
				tank_pos();
				$("#player1_laser").offset({left: tank_x, top: tank_y - 33});
				$("#player1_laser").fadeIn(300);
				$("#player1_laser").css("display", "block").animate({top: mouse_y, left: mouse_x}, 900);
			}
		});
	});
	
	//tank movement
      $(function(){
        $(document).keydown( function( event ){
          var keyCode = event.keyCode || event.which;
          var keyMap = { left: 65, up: 87, right: 68, down: 83}
          switch ( keyCode ) {
            case keyMap.left:
              $( "#player1" ).stop().animate({
                left: '-=40'
              }, 100 );
              tank_pos();
              break;
			
			case keyMap.right:
              $( "#player1" ).stop().animate({
                left: '+=40'
              }, 100 );
              tank_pos();
              break;
 
            case keyMap.up:
              $( "#player1" ).stop().animate({
                top: '-=40'
              }, 100 );
              tank_pos();
              break;
			
			case keyMap.down:
              $( "#player1" ).stop().animate({
                top: '+=40'
              }, 100 );
              tank_pos();
              break;
			}
		});
	});
