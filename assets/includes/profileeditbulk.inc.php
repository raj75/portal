<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cmpid=$_SESSION['company_id'];

//Add New Bulk User
//if(isset($user_one) and isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 5) and $user_one != "" and $user_one != 0 and isset($_POST["new"]) and $_POST["new"]=="new")
if(isset($user_one) and isset($_SESSION["group_id"]) and $user_one != "" and $user_one != 0 and isset($_POST["addbulkuser"]) and $_POST["addbulkuser"]=="addbulkuser")
{

	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);


	$error="Error Occurred";
	$sub_query=$usub_query=$tmperr=$tmperrexist=$tmparr=array();
	$temp_status=$temp_disabledate=null;

	//$tmp_usr=$mysqli->real_escape_string(@trim($_POST["usr"]));


  if(!count($_POST)){
    echo json_encode(array('error'=>'Error Occurred! Please enter user details.','err'=>'','errexist'=>''));
    exit();
  }
/*
  if(!isset($_POST["123"])){
    echo json_encode(array('error'=>'Error Occurred! Please enter user details1.'));
    exit();
  }
  $temparr=$_POST["123"];
  $testarr=json_decode($temparr,true);

  if(!isset($testarr[0]["email"])){
    echo json_encode(array('error'=>'Error Occurred! Please enter user details11.'));
    exit();
  }

  if(empty(@trim($testarr[0]["email"]))){
    echo json_encode(array('error'=>'Error Occurred! Please enter user details111.'));
    exit();
  }
*/

  foreach ($_POST as $key => $value) {
    $tarr=json_decode($value,true);
    //if($tarr===true){
      if(is_array($tarr) and count($tarr)) $tmparr[]=$tarr;
    //}
  }

  if(count($tmparr)){
    foreach($tmparr as $vvl){
      $email=@trim($vvl[0]["email"]);
      $password=@trim($vvl[0]["p"]);
      $pwd=@trim($vvl[0]["password"]);
      $fname=@trim($vvl[0]["fname"]);
      $lname=@trim($vvl[0]["lname"]);
      $title=@trim($vvl[0]["title"]);
      $company=$vvl[0]["company"];
      $usergroups=$vvl[0]["usergroups"];
      $gender=$vvl[0]["gender"];
      $status=$vvl[0]["status"];
      $address=@trim($vvl[0]["address"]);
      $country=@trim($vvl[0]["country"]);
      $state=@trim($vvl[0]["state"]);
      $zipcode=@trim($vvl[0]["zipcode"]);
      $phone=@trim($vvl[0]["phone"]);
      $mobile=@trim($vvl[0]["mobile"]);
      $csrusername=@trim($vvl[0]["csrusername"]);
      $csrpassword=@trim($vvl[0]["csrpassword"]);
      $ubmusername=@trim($vvl[0]["ubmusername"]);
      $ubmpassword=@trim($vvl[0]["ubmpassword"]);
			$ubmarchiveusername=@trim($vvl[0]["ubmarchiveusername"]);
      $ubmarchivepassword=@trim($vvl[0]["ubmarchivepassword"]);
      $disabledate=@trim($vvl[0]["disabledate"]);
      $sendtome=@trim($vvl[0]["sendtome"]);
      $sendtouser=@trim($vvl[0]["sendtouser"]);
      $uid=$vvl[0]["mlist"];




      if(!empty($email) and !empty($password) and !empty($fname) and !empty($lname) and !empty($title) and !empty($company) and !empty($usergroups) and !empty($gender) and $status != "" and !empty($uid)){
        //$tmperr[]=$uid;
////////////////////////////////////
            $sub_query=$usub_query=array();
            $_cid="";

              $email = filter_var($email, FILTER_VALIDATE_EMAIL);

              if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Not a valid email
                $tmperr[]=$uid;
                continue;
              }else{
                $stmtsk = $mysqli->prepare('SELECT user_id FROM user where email="'.@strtolower(@trim($email)).'" LIMIT 1');

            //('SELECT id FROM user where email="'.$email.'" LIMIT 1');

                if($stmtsk){
                  $stmtsk->execute();
                  $stmtsk->store_result();
                  if ($stmtsk->num_rows == 0)
                  {
                    $sub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($email))).'"';
                    $usub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($email))).'"';
                  }else{
                    $tmperrexist[]=$uid;
                    continue;
                  }
                }else{
                  $tmperr[]=$uid;
                  continue;
                }
              }

              //$usub_query[]='"password"="'.$mysqli->real_escape_string(@trim($_POST['password'])).'"';
              //$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
              if (strlen($password) != 128) {
                // The hashed pwd should be 128 characters long.
                // If it's not, something really odd has happened
                  $tmperr[]=$uid;
                  continue;
              }else{
                $tmp_password=password_generate($password);
                $tmp_=array();
                $tmp_=explode("@@@@",$tmp_password);
                if(count($tmp_))
                {
                  //$random_salt=$tmp_[0];
                  //$password=$tmp_[1];
                  $sub_query[]='password="'.$mysqli->real_escape_string(@trim($tmp_[1])).'",salt="'.$mysqli->real_escape_string(@trim($tmp_[0])).'"';
                }else{
                  $tmperr[]=$uid;
                  continue;
                }
              }

              $sub_query[]='firstname="'.$mysqli->real_escape_string(@trim($fname)).'"';

              $sub_query[]='lastname="'.$mysqli->real_escape_string(@trim($lname)).'"';

              //$sub_query[]='country="US"';

              $sub_query[]='title="'.$mysqli->real_escape_string(@trim($title)).'"';

            if(@trim($gender)=="F"){ $sub_query[]='gender="F"'; }else{ $sub_query[]='gender="M"'; }

              $sub_query[]='company_id="'.$mysqli->real_escape_string(@trim($company)).'"';

              $sub_query[]='usergroups_id="'.$mysqli->real_escape_string(@trim($usergroups)).'"';


              $sub_query[]='status="'.$mysqli->real_escape_string(@trim($status)).'"';
							/*if(!empty($address)){$sub_query[]='address="'.$mysqli->real_escape_string($address).'"'; }
							if(!empty($country)){$sub_query[]='country="'.$mysqli->real_escape_string($country).'"'; }
							if(!empty($state)){$sub_query[]='state="'.$mysqli->real_escape_string($state).'"'; }
							if(!empty($zipcode)){$sub_query[]='zipcode="'.$mysqli->real_escape_string($zipcode).'"'; }
							if(!empty($phone)){$sub_query[]='phone="'.$mysqli->real_escape_string($phone).'"'; }
							if(!empty($mobile)){$sub_query[]='mobile="'.$mysqli->real_escape_string($mobile).'"'; }
							if(!empty($csrusername)){$sub_query[]='address="'.$mysqli->real_escape_string($csrusername).'"'; }
							if(!empty($csrpassword)){$sub_query[]='csrpassword="'.$mysqli->real_escape_string($csrpassword).'"'; }
							if(!empty($ubmusername)){$sub_query[]='ubmusername="'.$mysqli->real_escape_string($ubmusername).'"'; }
							if(!empty($ubmpassword)){$sub_query[]='ubmpassword="'.$mysqli->real_escape_string($ubmpassword).'"'; }
							if(!empty($disabledate)){$sub_query[]='disabledate="'.$mysqli->real_escape_string($disabledate).'"'; }
							if(!empty($sendtome)){$sub_query[]='sendtome="'.$mysqli->real_escape_string($sendtome).'"'; }
							if(!empty($sendtouser)){$sub_query[]='sendtouser="'.$mysqli->real_escape_string($sendtouser).'"'; }*/




							if(!empty($address))
							{
								$sub_query[]='address="'.$mysqli->real_escape_string(@trim($address)).'"';
							}

							/*if(!empty($city))
							{
								$sub_query[]='city="'.$mysqli->real_escape_string(@trim($city)).'"';
							}*/

							if(!empty($state))
							{
								$sub_query[]='state="'.$mysqli->real_escape_string(@trim($state)).'"';
							}

							if(!empty($zipcode))
							{
								$sub_query[]='zip="'.$mysqli->real_escape_string(@trim($zipcode)).'"';
							}

							if(!empty($country))
							{
								$sub_query[]='country="'.$mysqli->real_escape_string(@trim($country)).'"';
							}

							if(!empty($phone))
							{
								$sub_query[]='phone="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($phone))).'"';
							}

							if(!empty($mobile))
							{
								$sub_query[]='mobile="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($mobile))).'"';
							}

							if(isset($_POST['fax']) and @trim($_POST['fax']) != "")
							{
								$sub_query[]='fax="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['fax']))).'"';
							}

							if(!empty($csrusername))
							{
								$sub_query[]='accuvio_user="'.$mysqli->real_escape_string(ed_crypt(@trim($csrusername),'e')).'"';
							}

							if(!empty($csrpassword))
							{
								$sub_query[]='accuvio_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($csrpassword),'e')).'"';
							}
							if(!empty($ubmusername))
							{
								$sub_query[]='capturis_user="'.$mysqli->real_escape_string(ed_crypt(@trim($ubmusername),'e')).'"';
							}

							if(!empty($ubmpassword))
							{
								$sub_query[]='capturis_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($ubmpassword),'e')).'"';
							}
							if(!empty($ubmarchiveusername))
							{
								$sub_query[]='capturis_archive_user="'.$mysqli->real_escape_string(ed_crypt(@trim($ubmarchiveusername),'e')).'"';
							}

							if(!empty($ubmarchivepassword))
							{
								$sub_query[]='capturis_archive_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($ubmarchivepassword),'e')).'"';
							}

							if(!empty($disabledate))
							{
								$sub_query[]='disable_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($disabledate)))).'"';
							}







            if(count($sub_query)){
              $sql='INSERT INTO user SET '.implode(",",$sub_query);//exit();
              $stmt = $mysqli->prepare($sql);
              if($stmt){
                $stmt->execute();
                $lastuaffectedID=$stmt->affected_rows;
                $insertid=$mysqli->insert_id;
                if($lastuaffectedID == 1){



                      //echo json_encode(array("error"=>""));

											if(isset($pwd) and @trim($pwd) != "" and isset($_SESSION['email'])){
												//$message="Hello,<br><br>For Vervantis user email: ".$email." the temporary password is : ".$pwd."	   <br><br><br> For any queiries please contact Vervantis at support@vervantis.com or (480) 550-9225.<br><br>Thank you,<br>Vervantis Support Team";
												$message="A new user has been created.<br><br>Username: ".$email."<br>Temporary password: ".$pwd."<br><br>Thank you,<br><br>Vervantis Support<br>Email: support@vervantis.com<br>Phone: (480) 550-9225.";
												//fireamail($_SESSION['email'],"Temporary new password for Vervantis user: ".$email."",$message,"","N");

												$mailArgs =  array('subject' => 'Temporary new password for Vervantis user: '.$email,
												    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
												    'toRecipients' => array( array('name' => '', 'address' => $_SESSION['email']) ),
												    'ccRecipients' => array(),
												    'importance' => 'normal',
												    'conversationId' => '',
												    'body' => $message,
												    'images' => array(),
												    'attachments' => array( )
												  );

												custommsmail('noreply@vervantis.com', $mailArgs,'');

		                    /*$message="Hello,<br><br>Your temporary new password for Vervantis: ".$pwd."	   <br><br><br> For any queiries please contact Vervantis at support@vervantis.com or (480) 550-9225.<br><br>Thank you,<br>Vervantis Support Team";
		                    //fireamail("support","Your temporary new password for Vervantis",$message,"","N");

												$mailArgs =  array('subject' => 'Your temporary new password for Vervantis',
												    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
												    'toRecipients' => array( array('name' => '', 'address' => $email) ),
												    'ccRecipients' => array(),
												    'importance' => 'normal',
												    'conversationId' => '',
												    'body' => $message,
												    'images' => array(),
												    'attachments' => array( )
												  );

												custommsmail('noreply@vervantis.com', $mailArgs,'');*/

												$message="Hello ".@ucfirst(@trim($fname)).",<br><br>Your temporary password for the Vervantis DataHub360 portal is: ".$pwd."<br>Username: ".$email."<br>You can access the portal here: https://portal.vervantis.com	 <br>Please contact the Vervantis Support Team should you have any questions. <br><br><br>Thank you,<br>Vervantis Support Team<br>Email: support@vervantis.com<br>Phone: (480) 550-9225";
		                    //fireamail("support","Your temporary new password for Vervantis",$message,"","N");

												$mailArgs =  array('subject' => 'New Vervantis DataHub360 login credentials',
												    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
												    'toRecipients' => array( array('name' => '', 'address' => $email) ),
												    'ccRecipients' => array(),
												    'importance' => 'normal',
												    'conversationId' => '',
												    'body' => $message,
												    'images' => array(),
												    'attachments' => array( )
												  );

												custommsmail('noreply@vervantis.com', $mailArgs,'');
		                  }
									continue;
                      //exit();
                    //if($lastaffectedID == 1){


                      //echo json_encode(array("error"=>""));
                    //}else
                      //echo json_encode(array("error"=>$error));

                    //exit();
                }else{
                  $tmperr[]=$uid;
                  continue;
                }
              }else{
                $tmperr[]=$uid;
                continue;
              }
              //exit();
            }


///////////////////////////////////
      }else{
        $tmperr[]=$uid;
      }



    }
    //For loop ends

    if(count($tmperr) or count($tmperrexist)){
      echo json_encode(array('error'=>'Please recheck the user details','err'=>implode("@@",$tmperr),'errexist'=>implode("@@",$tmperrexist)));
      exit();
    }

    echo json_encode(array("error"=>""));
    exit();
  }
  //print_r($tmparr);
  //$temparr=$_POST["123"];
//print_r(json_decode($temparr,true));
//print_r(json_decode($_POST,true));
  echo json_encode(array('error'=>'Error Occurred! Please enter user details correctly.','err'=>'','errexist'=>''));
  exit();
}

echo false;
exit();

function checks3img($keyname,$foldername="",$noimage=""){
	global $s3Client;
	if($foldername=="") return false;
	$keyname=@trim($keyname);
	$infotarget = $s3Client->doesObjectExist('datahub360', $foldername.$keyname);
	if($keyname != "" and $infotarget)
	{
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => 'datahub360',
			'Key'    => $foldername.$keyname
		]);

		$request = $s3Client->createPresignedRequest($cmd, '+3 minutes');
		return (string) $request->getUri();
	}elseif($noimage !=""){
		$infotarget = $s3Client->doesObjectExist('datahub360',$foldername.$noimage);
		if($infotarget)
		{
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360',
				'Key'    => $foldername.$noimage
			]);

			$request = $s3Client->createPresignedRequest($cmd, '+2 minutes');
			return (string) $request->getUri();
		}else{
			return false;
			//logoff
		}
	}else{
		return false;
		//logoff
	}
}


function password_generate($password){
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

        // Create salted password
        $password = hash('sha512', $password . $random_salt);
		return $random_salt."@@@@".$password;
}
?>
