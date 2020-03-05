<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE hmtl>
<html>
<head>
	<title>Web Api Test</title>
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.js"></script>
	
</head>
<body>
	<form method="post">
		<label>From</label>
		<input type="text" placeholder="From" class="board-point"/>
		<label>To</label>
		<input type="text" placeholder="To" class="drop-point"/>
		<select id="drop_dropdown"></select>
		<input type="submit" value="Search bus">
	</form>
	
</body>
<script>
$(document).ready(function(){
	// $(".board-point").keyup(function(){
	// 	var board_point = $(this).val();
	// 	$.ajax({
	// 		url: "Buskoticket/get_board_points",
	// 		type: "post",
	// 		data:{
	// 			'from': board_point
	// 		},
	// 		success:function(response)
	// 		{
	// 			let obj = JSON.parse(response);
	// 			var len = obj.count;
    //             for( var i = 0; i<len; i++){
    //                 var place = obj.data[i]['title'];
    //                 $("#board_dropdown").append("<option value='"+place+"'>"+place+"</option>");
    //             }
				
	// 		}
	// 	});
	// });
	$('.board-point').autocomplete({
    source: function (request, response){
      $.ajax( {
        url: "Buskoticket/get_board_points",
		type: "post",
		data:{
			'from': $(this).val()
		},
        success: function(response) {
			let obj = JSON.parse(response);
			console.log(obj);
        //   aCountries = data.split('\n').map(function(currentValue, index, arr) { 
        //     var labelValuePair = currentValue.split(':');
        //     return {
        //       label: labelValuePair[1],
        //       value: labelValuePair[0]
        //     }; 
        //   }); 
        }
      });
    }
});
	$(".drop-point").keyup(function(){
		var drop_point = $(this).val();
		$.ajax({
			url: "Buskoticket/get_drop_points",
			type: "post",
			data:{
				'to': drop_point
			},
			success:function(response)
			{
				let obj = JSON.parse(response);
				var len = obj.count;

                $("#drop_dropdown").empty();
                for( var i = 0; i<len; i++){
                    var place = obj.data[i]['title'];
                    $("#drop_dropdown").append("<option value='"+place+"'>"+place+"</option>");

                }
			}
		});
	});
});
</script>
</html>