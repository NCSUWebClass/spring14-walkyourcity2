<?php 

require_once ('lib/OAuth.php');

$lat = $_GET['lat'];
$long = $_GET['long'];

// For example, request business with id 'the-waterboy-sacramento'
$unsigned_url = "http://api.yelp.com/v2/search?location=". $_GET['add'] ; //. "&cll=" . $lat . ',' . $long;

//$unsigned_url = "http://api.yelp.com/v2/search?ll=" . $lat . ',' . $long  ;

//echo "::::" .  $_GET['add'] . "::::";

// Set your keys here
$consumer_key = "e1YRu1_Q-M6H5zoN4zOFLg";
$consumer_secret = "-PlSxvnM_V5y7OZVCmcG-QIvBzY";
$token = "tRmuHiiCKScrgQriGtsesl0RHeKIBazH";
$token_secret = "BSX5WLFEnJGlpZ4Yhy6cLJe_oR0";

// Token object built using the OAuth library
$token = new OAuthToken($token, $token_secret);

// Consumer object built using the OAuth library
$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

// Yelp uses HMAC SHA1 encoding
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);

// Sign the request
$oauthrequest->sign_request($signature_method, $consumer, $token);

// Get the signed URL
$signed_url = $oauthrequest->to_url();

// Send Yelp API Call
$ch = curl_init($signed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch); // Yelp response
curl_close($ch);

// Handle Yelp response data
$response = json_decode($data);

//print_r( $response  ) ;
$street = explode( ',' , $_GET['add'] );
$test = false;
for( $i = 0 ;  $i < sizeOf($response -> {'businesses'}) ; $i++  ) {

	if( $street[0] == $response -> {'businesses'}[$i] ->{'location'} -> {'display_address'}[0]  ) {
		echo "Yelp Rating : " . $response -> {'businesses'}[$i] -> {'rating'} ; 
		$test = true;
		break;
		//echo "::::" . $response -> {'businesses'}[$i] ->{'location'} -> {'display_address'}[0] ;
	}
	
	if ( $i == sizeOf($response -> {'businesses'}) -1 && $test == false )  {
		echo "Sorry No Yelp Rating available";
	}
}


?>