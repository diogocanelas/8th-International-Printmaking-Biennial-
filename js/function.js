var clicked=false;

$( "#add-artist" ).click(function() {
 	display=$(".new-artist").css("display");
 	if(display=="none"){
 		$(".new-artist").css("display","inherit");
 	}else{
 		$(".new-artist").css("display","none");
 	}
});

$( "#add-print" ).click(function() {
 	display=$(".new-print").css("display");
 	if(display=="none"){
 		$(".new-print").css("display","inherit");
 	}else{
 		$(".new-print").css("display","none");
 	}
});

$( "#add-inventory" ).click(function() {
 	if(!clicked){
		clicked=true;
 	}else{
 		clicked=false;
 	}
});


$( ".print-bienal" ).mouseover(function() {
	if(clicked){
		$(this).children().children(".print-title-pic").css("background","rgba(50, 155, 107, 0.72)");
 	}
});

$( ".print-bienal" ).mouseleave(function() {
	$(this).children().children(".print-title-pic").css("background","rgba(25,25,25,0.5)");
});

$( ".print-bienal" ).click(function() {
	if(clicked){
		print = $(this).attr("name");
		if (confirm('Tem a certeza que deseja adicionar a obra '+print+' ao Inventário ?')) {
		   	var form =$(this).children("form").attr("id");
			$( "#"+form).submit();
		} else {

		}
	}
});	

$( "#remove-inventory" ).click(function() {
 	if(!clicked){
		clicked=true;
 	}else{
 		clicked=false;
 	}
});

$( ".inventory-version-print" ).mouseover(function() {
	if(clicked){
		$(this).children("div").children(".print-title-pic-inventory-version").css("background","rgba(213, 46, 46, 0.86)");
 	}
});

$( ".inventory-version-print" ).mouseleave(function() {
	$(this).children("div").children(".print-title-pic-inventory-version").css("background","#199EC5");
});

$( ".inventory-version-print" ).click(function() {
	if(clicked){
		print = $(this).attr("name");
		if (confirm('Tem a certeza que deseja remover a obra '+print+' do Inventário ?')) {
		   	var form =$(this).children("form").attr("id");
			$( "#"+form).submit();
		} else {

		}
	}
});	



$( "#delete-artist").click(function() {
	if(!clicked){
 		$(".remove-form").css("display","inherit");
 		clicked=true;
 	}else{
 		$(".remove-form").css("display","none");
 		clicked=false;
 	}
});

$( "#delete-print").click(function() {
	if(!clicked){
		$(".artist-print").css("border","20px solid rgba(197,30,30,0.8)");
		$(".artist-print").css("cursor","pointer");
		$(".artist-print").addClass("neon");
		neonChange();
 		clicked=true;
 	}else{
 		$(".artist-print").removeClass("neon");  
 		$(".artist-print").css("cursor","inherit");
 		$(".artist-print").css("border","20px solid rgba(0,0,0,0.4)");
 		$(".artist-print").css("box-shadow","none");
 		clicked=false;
 	}
});

$( ".artist-print").click(function() {
	if(clicked){
		print = $(this).attr("name");
		$(".artist-print").css("border","20px solid rgba(0,0,0,0.4)");
 		$(".artist-print").css("box-shadow","none");
 		$(this).css("border","20px solid rgba(197,30,30,0.8)");
		if (confirm('Tem a certeza que deseja eliminar a obra '+print+' ?')) {
		   	var form =$(this).children("form").attr("id");
			$( "#"+form).submit();
		} else {

		}
	}
});	

$( ".remove-button").click(function() {
	print = $(this).attr("name");
	if (confirm('Tem a certeza que deseja eliminar o artista '+print+' ?')) {
	   	var form =$(this).parent().attr("id");
		$( "#"+form).submit();
	} else {

	}
});

$( "#logout").click(function() {
	var form =$(this).parent().attr("id");
	$( "#"+form).submit();
});

function neonChange() {
    x = 1;
    y=1;
    setInterval(neon, 200);
}

function neon() {
	if (y%2==0) {
		$(".neon").css("box-shadow","none");
		$(".neon").css("border","20px solid rgba(0,0,0,0.4)");
		y=1;
	}else if (x === 1) {
		$(".neon").css("border","20px solid rgba(197,30,30,0.8)");
        $(".neon").css("box-shadow","0 0 50px rgba(255,0,0,1), 0 0 20px FireBrick, 0 0 3px DarkRed");
    	$(".neon").css("opacity","0.95");
        x = 2;
        y = y+0.5;
    } else if (x === 2){
    	$(".neon").css("border","20px solid rgba(197,30,30,0.8)");
        $(".neon").css("box-shadow","0 0 50px rgba(255,0,0,0.6), 0 0 20px FireBrick, 0 0 3px DarkRed");
        $(".neon").css("opacity","0.97");
        x = 3;
        y = y+0.25;
    } else if (x === 3){
    	$(".neon").css("border","20px solid rgba(197,30,30,0.8)");
    	$(".neon").css("box-shadow","0 0 50px rgba(255,0,0,0.8), 0 0 20px FireBrick, 0 0 3px DarkRed");
    	$(".neon").css("opacity","0.98");
        x = 4;
        y = y+0.5;
    } else if (x === 4){
    	$(".neon").css("border","20px solid rgba(197,30,30,0.8)");
    	$(".neon").css("box-shadow","0 0 50px rgba(255,0,0,0.4), 0 0 20px FireBrick, 0 0 3px DarkRed");
    	$(".neon").css("opacity","1");
        x = 1;
        y = y+0.5;
    }
}

function goto(abc) {
  document.location.href=abc;
}