<?

function twitter_connect_settings() { 

	return array(
		'twitter_api'=>'Twitter API Key',
		'twitter_secret'=>'Twitter API Secret',
	);

}


function twitterFriends($user) { 
		$POD = $user->POD;
		
		$key = $POD->libOptions('twitter_api');
		$secret = $POD->libOptions('twitter_secret');

		try {
		$oauth = new OAuth($key,$secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
		$oauth->enableDebug();  // This will generate debug output in your error_log
		$oauth->setToken($user->get('twitter_token'),$user->get('twitter_secret'));
		$oauth->fetch('https://twitter.com/friends/ids.json?cursor=-1&user_id=' . $user->get('twitter_id')); 
		$json = json_decode($oauth->getLastResponse());
		} 
		catch (Exception $e) { 
		
		}
		// contains the first 5000 twitter friends
		
		
		$tweeps = array();
		foreach ($json->ids as $id) { 
			$tweeps[] = $id;
		}
		
		if (sizeof($tweeps)>0) { 
			return $POD->getPeople(array('twitter_id'=>$tweeps));
		} else { 
			return $POD->getPeople();		
		}	
}


Person::registerMethod('twitterFriends');




?>