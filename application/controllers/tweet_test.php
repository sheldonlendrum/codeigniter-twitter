<?php

	class Tweet_test extends CI_Controller {
		
		function __construct()
		{
			parent::__construct();
			
			// It really is best to auto-load this library!
			$this->load->library('tweet');
			
			if ( !$this->tweet->logged_in() )
			{
				// This is where the url will go to after auth.
				// ( Callback url )
				
				$url = site_url('tweet_test/auth');
				
				// Send the user off for login!
				$this->tweet->login($url);
			}
		}
		
		function auth()
		{
			if ( !$this->tweet->logged_in() )
			{
				die('some how you are not logged in');
			}
			
			$tokens = $this->tweet->get_tokens();
			
			$user 			= $this->tweet->call('get', 'account/verify_credentials');
			$relationship 	= $this->tweet->call('get', 'friendships/show', array('source_screen_name' => $user->screen_name, 'target_screen_name' => 'elliothaughin'));
			
			if ( $relationship->target->following === FALSE )
			{
				$this->tweet->call('post', 'friendships/create', array('screen_name' => $user->screen_name, 'follow' => TRUE));
			}
			
			$this->tweet->call('post', 'statuses/update', array('status' => 'Testing #CodeIgniter Twitter library by @elliothaughin - http://bit.ly/grHmua'));
			
			$options = array(
						'count' => 10,
						'page' 	=> 2,
						'include_entities' => 1
			);
			
			$timeline = $this->tweet->call('get', 'statuses/home_timeline');
			
			var_dump($timeline);
		}
	}