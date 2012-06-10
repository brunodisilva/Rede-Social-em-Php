<?


include "header.php";

$email = $_POST['email'];
$existing_email = $database->database_query("SELECT user_email FROM se_users WHERE user_email='$email' LIMIT 1");

$existing_email = $database->database_num_rows($existing_email);

echo check_email($existing_email,$email);

function check_email($existing_email,$email){
   if ($email == ""){
    return '<span style="color:#f00">Lütfen E-Mail Adresinizi Giriniz.</span>';
	}
	if(!is_email_address($email)){
	return '<span style="color:#f00">Bu E-Mail Adresi Gecerli Degil.Lütfen Gecerli Bir Baska E-Mail Giriniz..</span>';
	} 
	elseif ($existing_email > 0) {
	  return '<span style="color:#f00">Bu E-Mail Adresi Kullanimdadir.Lütfen Bir Baska E-Mail Giriniz.</span>';
      }  
   return '<span style="color:#0c0">E-mail Adresi Tamam.</span>';
   }
?>