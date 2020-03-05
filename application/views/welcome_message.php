<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Book Your Bus</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css" />


	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css" />

	<script src="<?php echo base_url();?>assets/js/jquery.js" type="text/javascript"/></script>
	<script src="<?php echo base_url();?>assets/js/jquery-ui.js" type="text/javascript"/></script>



	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<body>
	<div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="booking-cta">
							<h1 style="color:white;">Book Your Ticket</h1>
						</div>
					</div>
					<div class="col-md-7 col-md-offset-1">
						<div class="booking-form">
							<form method="get" action="<?php echo base_url();?>Buskoticket/search_bus">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<span class="form-label">From</span>
											<input class="form-control board-point" name="from" type="text" placeholder="City">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<span class="form-label">To</span>
											<input class="form-control drop-point" name="to" type="text" placeholder="City">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<span class="form-label">Departing</span>
											<input class="form-control" type="date" name="journey_date" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<span class="form-label">Number of Seats</span>
											<input class="form-control" type="number" name="seat" required>
										</div>
									</div>
								</div>
								<div class="form-btn">
									<button class="submit-btn">Search Bus</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
$(document).ready(function(){
	$( ".board-point" ).autocomplete({
		source:function(request,response){
			var place1 = new Array();
			$.ajax({
				url: "Buskoticket/get_board_points",
				data:{
					from: request.term
				},
				type:"post",
				success:function(result){
					let obj = JSON.parse(result);
					var len = obj.count;
					for( var i = 0; i<len; i++){
						place1[i]={
							label: obj.data[i]['title'],
						}
					}
					response(place1);
				}
			});
		}
	});
	$( ".drop-point" ).autocomplete({
		source:function(request,response){
			var place2 = new Array();
			$.ajax({
				url: "Buskoticket/get_drop_points",
				data:{
					to: request.term
				},
				type:"post",
				success:function(result){
					let obj = JSON.parse(result);
					var len = obj.count;
					for( var i = 0; i<len; i++){
						place2[i]={
							label: obj.data[i]['title'],
						}
					}
					response(place2);
				}
			});
		}
	});
});
</script>

</html>