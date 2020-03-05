<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buskoticket extends CI_Controller
{
    private $api_token = ''; //

    public function __construct() 
    {
        parent:: __construct();
        $this->getAccessToken();
    }

    public function getAccessToken()
    {
        $merchant_key = BUSKOTICKET_MERCHANT_KEY;
        $origin       = base_url();

        $headers = array(
            "Content-Type:application/json",
            "Merchant-Key:".$merchant_key,
            "Origin:".$origin //replace with your registered domain
        );

        $url            = 'https://dev.buskoticket.com/webapi/v1/getAccessToken';
        $ch 			= curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result			= curl_exec ($ch);
        $status_code 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        
        if($status_code == 200)
        {
            $result_temp = json_decode($result);
            $this->api_token = $result_temp->token;
        }
        else
        {
            echo 'error';exit;
        }
        
        curl_close ($ch);
    }

    public function get_board_points()
    {
        $query_params = http_build_query(array(
            'type' 			=> 'board_point',
            'place_name'	=> $_POST['from']
        ));
        
        $headers = array(
            'Content-Type:application/json',
            'Access-Token:'.$this->api_token
        );
    
        $url 	= 'https://dev.buskoticket.com/webapi/v1/get_boarding_drop_point?'.$query_params;
        $ch 			= curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result			= curl_exec ($ch);
        $status_code 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        if($status_code == 200)
        {
            echo $result;
        }
        else
        {
            
        }
        
        curl_close ($ch);
    }
    public function get_drop_points()
    {
        $query_params = http_build_query(array(
            'type' 			=> 'drop_point',
            'place_name'	=> $_POST['to']
        ));
        
        $headers = array(
            'Content-Type:application/json',
            'Access-Token:'.$this->api_token
        );
    
        $url 	= 'https://dev.buskoticket.com/webapi/v1/get_boarding_drop_point?'.$query_params;
        $ch 			= curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result			= curl_exec ($ch);
        $status_code 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        if($status_code == 200)
        {
            echo $result;
        }
        else
        {
            
        }
        
        curl_close ($ch);
    }
    public function search_bus()
    {
        $jdate = $this->input->get('journey_date', true);

        $newDate = date("d/m/Y", strtotime($jdate));
        
        $query_params = http_build_query(array(
            'from' 					=> $this->input->get('from'),
            'to'					=> $this->input->get('to'),
            'journey_date'			=> $newDate,
            'journey_date_nepali'	=> '2076/11/09',
            'num_passengers'		=> $this->input->get('seat')
        ));
        
        $headers = array(
            'Content-Type:application/json',
            'Access-Token:'.$this->api_token
        );
        
        $url 	= 'https://dev.buskoticket.com/webapi/v1/searchBus?'.$query_params;
        
        $ch 			= curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result			= curl_exec ($ch);
        $status_code 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        if($status_code == 200)
        {
            $result = array(
                'search_results' => $result
            );

            $this->load->view('bus_detail',$result);
        }
        else
        {
        
        }
        curl_close ($ch);
    }

    public function book_ticket($data){
        // $bus_id         = $_POST['bus_id'];
        // $route_id       = $_POST['route_id'];
        // $board_id       = $_POST['board_id'];
        // $drop_id        = $_POST['drop_id'];
        // $fare           = $_POST['fare'];
        // $journey_date   = $_POST['journey_date'];
        $newDate        = date("d-m-Y", strtotime($data['journey_date']));  
        // $nationality    = $_POST['nationality'];
        // $gender         = $_POST['gender'];
        // $name           = $_POST['name'];
        // $age            = $_POST['age'];
        // $mobile         = $_POST['mobile'];
        $seats          = $data['seats'];
        $seat_no        = explode(",",$seats);

        $postdata       = array(
            'amount'             => $data['fare'],
            'bus_id'             => $data['bus_id'],
            'rout_id'            => $data['route_id'],
            'boarding_point_id'  => $data['board_point'],
            'drop_point_id'      => $data['drop_point'],
            'seat_no'            => $seat_no, 
            'payment_option'     => 'Khalti',
            'booking_date'       => $newDate,
            'booking_date_bs'    => '2076/11/11',
            'customer_name'      => $data['name'],
            'age'                => $data['age'],
            'gender'             => $data['gender'],
            'email'              => 'neu.santosh@gmail.com',
            'mobile'             => $data['mobile'],
            'nationality'        => $data['nationality'],
            'minor_kid'          => 'N',
            'booked_by'          => BUSKOTICKET_MERCHANT_KEY
        );

        $payload = http_build_query($postdata);
        
        $headers = array(
            'Access-Token:'.$this->api_token,
            'Content-Length: ' . strlen($payload)
        );
        
        $url 	= 'https://dev.buskoticket.com/webapi/v1/book_ticket';
        // Prepare new cURL resource
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
         
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         
        // Submit the POST request
        $result = curl_exec($ch);
         print_r($result);exit;
        // Close cURL session handle
        curl_close($ch);
    }

    public function check_seat()
    {
        if(isset($_POST))
        {
            $payload = http_build_query(array(
                'seat_no' => $this->input->post('seats', true),
                'bus_id'  => $this->input->post('bus_id', true),
                'jdate'   => $this->input->post('j_date', true),  
            ));

            $headers = array(
                'Access-Token:'.$this->api_token,
                'Content-Length: ' . strlen($payload)
            );

            $url    = 'https://dev.buskoticket.com/webapi/v1/checkseatavailability';

             // Prepare new cURL resource
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
             
            // Set HTTP Header for POST request 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
             
            // Submit the POST request
            $result = curl_exec($ch);
             print_r($result);exit;
            // Close cURL session handle
            curl_close($ch);
        }
    }
    public function khaltipayverify(){
        $data = $this->input->post();

		if($data){
			
			$args = http_build_query(array(
				'token' => $data['token'],
				'amount'  => $data['khaltiAmount']
			));

			$url = "https://khalti.com/api/v2/payment/verify/";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$headers = ['Authorization: Key test_secret_key_2ad23b1679bb4b918b4f4a34c4617b20'];
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$response = curl_exec($ch);
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			$response_decoded = json_decode($response, true);

			if (!empty($response_decoded['state'])) {
				if ($response_decoded['state']['name'] == "Completed") {

					// $insertResult = $this->insertBookingRecord($data, 'khalti');
                    // echo json_encode($insertResult);
                    $this->book_ticket($data);
					
				}
			}
			else{
				$custom_response['status'] = false;
				$custom_response['message'] = $response_decoded;
				echo json_encode($custom_response);	         
			}

			
		}
	}

}    