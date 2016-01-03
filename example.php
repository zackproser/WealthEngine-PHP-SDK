<?php 

use WealthEngine\API\HttpClient;

require './lib/WealthEngine-SDK.php'; 

//Instantiate the WealthEngine API Client - set API environment to production - set API match type to full
$WeAPI = new HttpClient('ddb26e11-9348-4ead-9e2a-5a3b80a01b52', 'prod', 'full');

//Get profile by email and [names]
$result = $WeAPI->getProfileByEmailAddress('zackproser@gmail.com', 'zack', 'proser'); 

var_dump($result);

/*

object(stdClass)#3 (3) {
["status"]=>
string(8) "NO_MATCH"
["message"]=>
string(15) "No Result Found"
["status_code"]=>
int(555)
}

 */

//Get profile by address and names
$result = $WeAPI->getProfileByAddress('Hamburt', 'Porkington', '451 E West ST', 'Lost Angeles', 'CA', 90011); 

var_dump($result);

/*

object(stdClass)#4 (7) {
  ["giving"]=>
  object(stdClass)#5 (3) {
    ["estimated_annual_donations"]=>
    object(stdClass)#6 (6) {
      ["min"]=>
      int(1)
      ["text"]=>
      string(7) "$1K-$5K"
      ["max"]=>
      int(7)
      ["text_low"]=>
      string(3) "$1K"
      ["value"]=>
      int(2)
      ["text_high"]=>
      string(3) "$5K"
    }
    ["gift_capacity"]=>
    object(stdClass)#7 (6) {
      ["min"]=>
      int(1)
      ["text"]=>
      string(9) "$30K-$40K"
      ["max"]=>
      int(20)
      ["text_low"]=>
      string(4) "$30K"
      ["value"]=>
      int(11)
      ["text_high"]=>
      string(4) "$40K"
    }
    ["p2g_score"]=>
    object(stdClass)#8 (2) {
      ["text"]=>
      string(10) "4|0 - Fair"
      ["value"]=>
      string(2) "40"
    }
  }
  ["id"]=>
  int(17773)
  ["identity"]=>
  object(stdClass)#9 (6) {
    ["age"]=>
    int(78)
    ["first_name"]=>
    string(8) "HAMBURT"
    ["full_name"]=>
    string(18) "HAMBURT PORKINGTON"
    ["gender"]=>
    object(stdClass)#10 (2) {
      ["text"]=>
      string(4) "Male"
      ["value"]=>
      string(1) "M"
    }
    ["last_name"]=>
    string(7) "PORKINGTON"
    ["middle_name"]=>
    string(1) "C"
  }
  ["locations"]=>
  array(1) {
    [0]=>
    object(stdClass)#11 (1) {
      ["address"]=>
      object(stdClass)#12 (4) {
        ["city"]=>
        string(11) "LOS ANGELES"
        ["postal_code"]=>
        string(5) "90011"
        ["state"]=>
        object(stdClass)#13 (2) {
          ["text"]=>
          string(10) "California"
          ["value"]=>
          string(2) "CA"
        }
        ["street_line1"]=>
        string(13) "451 E West ST"
      }
    }
  }
  ["realestate"]=>
  object(stdClass)#14 (2) {
    ["total_num_properties"]=>
    int(1)
    ["total_realestate_value"]=>
    object(stdClass)#15 (6) {
      ["min"]=>
      int(1)
      ["text"]=>
      string(11) "$250K-$500K"
      ["max"]=>
      int(8)
      ["text_low"]=>
      string(5) "$250K"
      ["value"]=>
      int(2)
      ["text_high"]=>
      string(5) "$500K"
    }
  }
  ["wealth"]=>
  object(stdClass)#16 (3) {
    ["accredited_investor"]=>
    bool(false)
    ["networth"]=>
    object(stdClass)#17 (6) {
      ["min"]=>
      int(1)
      ["text"]=>
      string(11) "$100K-$500K"
      ["max"]=>
      int(12)
      ["text_low"]=>
      string(5) "$100K"
      ["value"]=>
      int(4)
      ["text_high"]=>
      string(5) "$500K"
    }
    ["total_income"]=>
    object(stdClass)#18 (6) {
      ["min"]=>
      int(1)
      ["text"]=>
      string(7) "$1-$50K"
      ["max"]=>
      int(5)
      ["text_low"]=>
      string(2) "$1"
      ["value"]=>
      int(1)
      ["text_high"]=>
      string(4) "$50K"
    }
  }
  ["status_code"]=>
  int(200)
}
	
 */

//Get profile by phone number and [names]
$result = $WeAPI->getProfileByPhone('1231231233', 'Zack', 'Proser'); 

var_dump($result); 

/*

object(stdClass)#3 (3) {
  ["status"]=>
  string(8) "NO_MATCH"
  ["message"]=>
  string(15) "No Result Found"
  ["status_code"]=>
  int(555)
}

 */

//Create a session with a 10 minute duration
$result = $WeAPI->createSession(600000); 

var_dump($result); 

/*
object(stdClass)#4 (7) {
  ["_id"]=>
  string(24) "55972c22e4b0be0cd736a172"
  ["customer_id"]=>
  string(24) "552951c934f9b38cfd87f524"
  ["create_ts"]=>
  int(1435970594708)
  ["user_id"]=>
  string(24) "55908a89e4b0be0cd735eca8"
  ["expire_ts"]=>
  int(1436570594708)
  ["app_id"]=>
  string(22) "com.we.third-party-api"
  ["status_code"]=>
  int(201)
}
 */

?>