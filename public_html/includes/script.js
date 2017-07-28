function de_cider(bind) {
	if (bind == 0) {
		document.write("No");
	} else {
		document.write("Yes");
	}
}

function status(sta) {	
	if (sta == 1) {		
		document.write("Research");
	} else if (sta == 2) {
		document.write("Locate");
	} else if (sta == 3) {
		document.write("In Progress");
	} else if (sta == 4) {
		document.write("On Hold");
	} else if (sta == 5) {
		document.write("Set Corners");
	} else if (sta == 6) {
		document.write("Finalizing");
	} else if (sta == 7) {
		document.write("Completed");
	} else {
		document.write("Start");
	}
}

/*function flash(due){	if (due => time()) {
		$(due).color('red');
		document.write(due);
	}}*/

/*$(document).ready(function() {
    $('.pull-me').click(function() {
		$('.panel').slideToggle('slow');
	});
});*/

    function flashtext(ele,col) {
    var tmpColCheck = document.getElementById( ele ).style.color;

      if (tmpColCheck === 'silver') {
        document.getElementById( ele ).style.color = col;
      } else {
        document.getElementById( ele ).style.color = 'silver';
      }
    } 

    setInterval(function() {
        flashtext('flashingtext','red');
        flashtext('flashingtext2','orange');
        flashtext('flashingtext3','yellow');
    }, 500 ); //set an interval timer up to repeat the function
	
    

$(function() {	
	$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
});

$(function() {
	$( "#datepicker2" ).datepicker({ dateFormat: "yy-mm-dd" });
});