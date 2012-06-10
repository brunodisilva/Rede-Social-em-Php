<?

include "header.php";

$username = $_POST['username'];
$username = trim(htmlentities($username));
$existing_users = $database->database_query("SELECT user_username FROM se_users WHERE user_username='$username' LIMIT 1");

$existing_users = $database->database_num_rows($existing_users);

echo check_username($existing_users,$username);


function check_username($existing_users,$username){
   $username=strtolower($username);
   if ($username == ""){
    return '<span style="color:#f00">Lütfen Üye isminizi Giriniz</span>';
	}
	if(ereg('[^A-Za-z0-9]', $username)){
	return '<span style="color:#f00">Lütfen Üye isminde Tuaf Veya Türkce Karekterler Kullanmayiniz.</span>';
	}
	elseif ($existing_users > 0) {
	  return '<span style="color:#f00">Bu Üye ismi Malesef Kullanimda</span>';
      }  
   return '<span style="color:#0c0">Bu Üye ismini Kullanabilirsiniz.</span>';
   }
?>