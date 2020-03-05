<html>
<head>
    <title>Choose your bus</title>
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
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
    <style>
    .seater{
        background-image: url(<?php echo base_url();?>assets/img/available_seat.png);
        margin-top: 6px !important;
        background-repeat: no-repeat;
        width: 20px;
        height: 35px;
        cursor: pointer;
        font-size: 15px;
        text-align:center;
    }
    .seat-selected{
        background-image: url(<?php echo base_url();?>assets/img/seat_select.png);
        margin-top: 6px !important;
        background-repeat: no-repeat;
        width: 20px;
        height: 35px;
        cursor: pointer
        }
    </style>
</head>
<body>
 <?php 
 $search_results_array = json_decode($search_results);
//  echo '<pre>';print_r($search_results_array->data);exit;
  if($search_results_array->count > 0)
  {
     foreach($search_results_array->data as $key => $search_result)
     {
        $seat_details = stripslashes($search_result->seat_details[0]->seat_layout);
        $seats_array = json_decode($seat_details);
     }
  }
  else
  {
      echo 'No search results';exit;
  }
 ?>
 <?php foreach($search_results_array->data as $bus){?>
    <div class="route-details">
        <h3><?php echo $bus->bus_name;?></h3>
        <?php echo $bus->board_point;?> (<?php echo $bus->board_time ?>) - <?php echo $bus->drop_point;?> (<?php echo $bus->drop_time ?>)
        Journey Date: <?php echo $bus->jdate;?>
        Total Seat:<?php echo $bus->total_seats;?>
        Fare:  <?php echo $bus->symbol; echo $bus->fare?>
        <button class="btn btn-primary <?php echo $bus->bus_id ?> show-detail" value ="<?php echo $bus->bus_id ?>">Show Details</button>
        <br>
        <div class="more-details">
            <?php
            echo "<table>";
            for($i=0;$i<count($seats_array);$i++){
                echo "<tr>";
                foreach($seats_array[$i] as $col){
                    if($col->isDriver==1){
                        echo '<td><img src='.base_url()."assets/img/driver.png".'></td>';
                    }
                    elseif($col->check == null && $col->isDriver == null){
                        echo '<td><img src='.base_url()."assets/img/empty.png".'></td>';
                    }
                    elseif($col->check == 1 && $col->isDriver == null){
                        
                        echo '<td class="seater" value="'.$col->seat_name.'">'.$col->seat_name.'</td>';
                        
                    }
                }
                echo '</tr>';
            }
            echo "</table><form class='book-form'>";
            echo "<input type='hidden' name='bus_id' value='".$bus->bus_id."'>";
            echo "<input type='hidden' name='route_id' value='".$bus->route_id."'>";
            echo 'Board Points';
            echo '<select class="board_point" name="board_point">';
                foreach($bus->board_points as $board_point){
                    echo '<option value="'.$board_point->id.'">'.$board_point->pickup_point.'</option>';
            }
            echo '</select><br>';
            echo 'Drop Points';
            echo '<select class="drop_point" name="drop_point">';
                foreach($bus->drop_points as $drop_point){
                    echo '<option value="'.$drop_point->id.'">'.$drop_point->stoping_point.'</option>';
            }
            echo '</select>';
            ?>
            
            Name:<input type= "text" name="name" placeholder="fullname">
            Age:<input type="number" name="age" min="0" max="110">
            Mobile: <input type="number" name="mobile" max_length="10">
            No of Seats:<input type="text" class="no-of-seats" name="seats" value="" readonly>
            Nationality:
            <select class="nationality" name="nationality">
            <option value="Nepali">Nepali</option>
            <option value="Indian">Indian</option>
            <option value="Others">Others</option>
            </select>
            Gender
            <select class="gender" name="gender">
            <option value="M">Male</option>
            <option value="F">Female</option>
            <option value="0">Others</option>
            </select>
            <input type="hidden" name="fare" value="<?php echo $bus->fare?>">
            <input type="hidden" name="journey_date" value="<?php echo $bus->jdate?>">

            </form>
            <input type="submit" class="book" value="book">
        </div>
    </div>
<?php } ?>
<script>
$(document).ready(function(){

    function getUrlVars() {
        var vars = {};

        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });

        return vars.seat;
    }

    var seat_limit = parseInt(getUrlVars());
    let newseats="";
    var seat_count = 0;

    $(".seater").click(function()
    {
        if($(this).hasClass("seat-selected"))
        {
            $(this).removeClass("seat-selected");
            var removeseat = $(this).attr('value');

            if(newseats.includes(removeseat))
            {
                remove_seat=","+removeseat;
                if(newseats.includes(remove_seat))
                {
                    newseats=newseats.replace(remove_seat,"");
                    $(".no-of-seats").attr('value',newseats);
                    seat_count--;
                }
                else
                {
                    removes_seat=removeseat+",";
                    newseats=newseats.replace(removes_seat,"");
                    newseats=newseats.replace(removeseat,"");
                    $(".no-of-seats").attr('value',newseats);
                    seat_count--;
                }
            }
        }
        else
        {
            if(seat_count < seat_limit)
            {                
                $(this).addClass("seat-selected");
                let oldseat = $(this).attr('value');
                
                $.post("check_seat",{
                    seats   : oldseat,
                    bus_id  : $("input[name='bus_id']").attr('value'),
                    j_date  : $("input[name='journey_date']").attr('value')
                  },function(data, status){
                  });

                newseats    = newseats+","+oldseat;
                newseats    = newseats.replace(/^,/, '');
                $(".no-of-seats").attr('value',newseats);
                seat_count++;
            }
            else
            {
                alert("Seat Limit Exceeded");
            }
        }
    });



    $(".book").click(function(){
        var bus_id = $("input[name='bus_id']").attr('value');
        var route_id = $("input[name='route_id']").attr('value');
        var board_id = $('option:selected', '.board_point').attr('value');
        var drop_id = $('option:selected', '.drop_point').attr('value');
        var nationality = $('option:selected', '.nationality').attr('value');
        var gender = $('option:selected', '.gender').attr('value');   
        var fare = $("input[name='fare']").attr('value');
        var journey_date = $("input[name='journey_date']").attr('value');
        var name = $("input[name='name']").val();
        var age = $("input[name='age']").val();
        var mobile = $("input[name='mobile']").val();
        var seats = $("input[name='seats']").attr('value');
        var seat_array =  seats.split(',');
        var count = seat_array.length;
        var seat_limit = parseInt(getUrlVars());
        var data = $('.book-form').serializeArray();
        paywithKhalti(data);
        // if(seat_limit == count){
        //     $.ajax({
        //     data: {
        //         'bus_id': bus_id,
        //         'route_id':route_id,
        //         'board_id': board_id,
        //         'drop_id': drop_id,
        //         'fare': fare,
        //         'nationality': nationality,
        //         'gender':gender,
        //         'journey_date':journey_date,
        //         'seats': seats,
        //         'name':name,
        //         'age':age,
        //         'mobile':mobile
        //     },
        //     url: "book_ticket",
        //     type: 'post',
        //     datatype: 'json',
        //     success:function(response){
        //         let obj = JSON.parse(response);
        //         console.log(obj);
        //     }
        //     });
        // }
        // else{
        //     alert('Select enough seat');
        // }
    });
    function paywithKhalti(_details) {
        var obus_id = '';
        var orout_id = '';
        var oboarding_point_id = '';
        var odrop_point_id = '';
        var booking_date = '';
        var user_id = '';
        var totals = '';

        $.each(_details, function(ind, val) {
            var field_name = val.name;

            if (field_name == 'bus_id') {
                obus_id = val.value;
            }

            if (field_name == 'route_id') {
                orout_id = val.value;
            }

            if (field_name == 'board_point') {
                oboarding_point_id = val.value;
            }

            if (field_name == 'drop_point') {
                odrop_point_id = val.value;
            }

            if (field_name == 'journey_date') {
                booking_date = val.value;
            }

            if (field_name == 'fare') {
                totals = val.value;
            }
        });

    var config = {
        "publicKey": "test_public_key_e20070a1a21948e691fd2c9cd75c7be3",
        "productIdentity": obus_id + "/" + orout_id,
        "productName": obus_id + "/" + orout_id,
        "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
        "eventHandler": {
            onSuccess(payload) {
                _details.push({
                    name: 'token',
                    value: payload.token
                });
                _details.push({
                    name: 'khaltiAmount',
                    value: payload.amount
                });
                verifyKhaltiPayment(_details);
                $(".loader").hide();
            },
            onError(error) {
                alert('error');
                if (error.payload.detail) {
                    $.growl.error({
                        message: error.payload.detail
                    });
                } else if (error.payload.mobile) {
                    $.growl.error({
                        message: error.payload.mobile
                    });
                }
            },
            onClose() {
                console.log('widget is closing');
            }
        }
    };
    var checkout = new KhaltiCheckout(config);

    _details.find(function(ele) {
        if (ele.name && ele.name === 'fare') {
            total = ele.value;
            total = total * 100;
            if (total < 1000) {
                alert("Minimum amount should be Rs 10");
                checkout.hide();
            } else {
                checkout.show({
                    amount: total
                });
            }
        }
    });
}
function verifyKhaltiPayment(payload) {
    var token = payload.token;
    var amount = payload.amount;

    $.ajax({
        url: 'khaltipayverify',
        data: payload,
        type: "POST",
        dataType: "JSON",
        success: function(responseData) {
            if (responseData.status == true) {
            }
        }
    });
}
});
</script>
</body>
</html>