<?php
	session_start();

	if(isset($_POST['reg']))
	{
		$error = [];

		$login = trim($_POST["login"]); 
		$login = strip_tags($login);
		$login = htmlspecialchars($login, ENT_QUOTES); 
		$login = stripslashes($login);
		
		if(strlen($login) == "0"):
			$error['login'] = "Вы не указали свой логин!";
		endif;
		
		$xml = simplexml_load_file('db.xml');
			
			
			foreach($xml->user as $user)
			{
				$login_from_db = $user->login;
				
				if($login == $login_from_db):
					$error['login'] = "Такой логин уже существует! Придумайте новый!";
				endif;			
			}
		

		$password = trim($_POST["password"]); 
		$password = strip_tags($password);
		$password = htmlspecialchars($password, ENT_QUOTES); 
		$password = stripslashes($password);
		
		if(strlen($password) == "0"):
			$error['password'] = "Вы не указали свой пароль!";
		endif;
		

		$confirm_password = trim($_POST["confirm_password"]); 
		$confirm_password = strip_tags($confirm_password);
		$confirm_password = htmlspecialchars($confirm_password, ENT_QUOTES); 
		$confirm_password = stripslashes($confirm_password);
		
		if(strlen($confirm_password) == "0")
		{
			$error['confirm_password'] = "Вы не подтвердили свой пароль!";
		}
		elseif ($_POST['password'] != $_POST['confirm_password'])
		{
			$error['confirm_password'] = "Пароли не совпадают!";
		}
		else
		{
			$sault = uniqid();
			$password = md5($password . $sault);
			$confirm_password = md5($confirm_password . $sault);
		}
		
		$email = trim($_POST["email"]);
		$email = strip_tags($email);
		$email = htmlspecialchars($email, ENT_QUOTES);
		$email = stripslashes($email);
			
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
			$error['email'] =  "Некорректно введен email";
		endif;
		
		$xml = simplexml_load_file('db.xml');
		
			foreach($xml->user as $user)
			{
				$email_from_db = $user->email;
				
				if($email_from_db == $email):
					$error['email'] = "Такое имя электронной почты уже существует! Придумайте новое!";
				endif;			
			}
		
		$name = trim($_POST["name"]); 
		$name = strip_tags($name);
		$name = htmlspecialchars($name, ENT_QUOTES); 
		$name = stripslashes($name);
		
		if(strlen($name) == "0"):
			$error['name'] = "Вы не указали имя";
		endif;
			
		
		if(is_array($error) && count($error)>0)
		{
			$result = array ("errors" => $error);
			echo json_encode($result);
		}
		else
		{ 
		
			$sxml = simplexml_load_file ('db.xml'); 
			$xml_str = $sxml->asXML(); 
			$dom = new DOMDocument;	
			$dom->formatOutput=true;		
	 
			$dom->loadXML ($xml_str); 

			$parent = $dom->documentElement; 
			$first_item = $parent->getElementsByTagname('item')->item (0); 
		   
			$user = $dom->createElement ('user');
			$dom->appendChild($user);	
			
			$login = $dom->createElement ('login', $login);
			$user->appendChild ($login);
			
			$password = $dom->createElement ('password', $password);  
			$user->appendChild ($password); 
		   
			$confirm_password = $dom->createElement ('confirm_password', $confirm_password);
			$user->appendChild ($confirm_password);
			
			$sault = $dom->createElement ('sault', $sault);  
			$user->appendChild ($sault);
			
			$email = $dom->createElement ('email', $email);  
			$user->appendChild ($email); 
		   
			$name = $dom->createElement ('name', $name);
			$user->appendChild ($name);			
			
			$parent->insertBefore ($user, $first_item); 
			$dom->save('db.xml');
				
			$result = array ("result" => "ok");	
			echo json_encode($result);
		}
	}
	elseif(isset($_POST['auth']))
	{	
		$errorAuth = [];
		$login = trim($_POST["login"]); 
		$login = strip_tags($login);
		$login = htmlspecialchars($login, ENT_QUOTES); 
		$login = stripslashes($login);
			
		if(strlen($login) == "0"):
			$errorAuth['login'] = "Вы не указали свой логин!";
		endif;
			
		$password = trim($_POST["password"]); 
		$password = strip_tags($password);
		$password = htmlspecialchars($password, ENT_QUOTES); 
		$password = stripslashes($password);
			
		if(strlen($password) == "0"):
			$errorAuth['password'] = "Вы не указали свой пароль!";
		endif;
		
		$xml = simplexml_load_file('db.xml');
			
			
		foreach($xml->user as $user)
		{
			$login_from_db = $user->login;
				
			if($login == $login_from_db):
				
				$password_from_db = $user->password;
				$sault_from_db = $user->sault;

				$password = md5($password . $sault_from_db);
			
					
				if($password_from_db ==  $password)
				{
					$res = "ok";
					$name_from_db = (array)$user->name;;
					$_SESSION['user_name'] = $name_from_db[0];
				}
				else
				{
					$errorAuth['pasw'] = "Неверный пароль!";
				}
				endif;			
		}
			
		if(is_array($errorAuth) && count($errorAuth)>0)
		{
			$result = array ("errors" => $errorAuth);
			echo json_encode($result);
		}
		else
		{
			$result = array ("result" => $res);
			echo json_encode($result);
		}
	}
	else
	{
		session_destroy();
		
		$res = "ok";
		$result = array ("result" => $res);
		echo json_encode($result);
	}
?>