<?php

/**
 * Rah_debug plugin for Textpattern CMS.
 *
 * @author  Jukka Svahn
 * @date    2012-
 * @license GNU GPLv2
 * @link    https://github.com/gocom/rah_debug
 * 
 * Copyright (C) 2012 Jukka Svahn http://rahforum.biz
 * Licensed under GNU Genral Public License version 2
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

class rah_debug {

	/**
	 * @var string Current user
	 */

	public $user = false;
	
	/**
	 * @var array Users that see debugging information
	 */
	
	public $listed = array();

	/**
	 * Constructor
	 */
	
	public function __construct() {
		global $txp_user;
		
		register_callback(array($this, 'trace'), 'admin_side', 'body_end');
		
		if(!$txp_user) {
			$user = is_logged_in();

			if($user) {
				$this->user = $user['name'];
			}
		}
		
		else {
			$this->user = $txp_user;
		}
		
		if(defined('rah_debug')) {
			$this->listed = do_list(rah_debug);
		}
		
		$this->runner();
	}
	
	/**
	 * Turn debugging mode on for certain logged in users
	 */
	
	public function runner() {
		
		global $prefs, $production_status;
		
		if(!$this->user || !in_array($this->user, $this->listed, true)) {
			return;
		}
		
		$prefs['production_status'] = $production_status = 'debug';
		set_error_level('debug');
	}
	
	/**
	 * Adds tag trace to the admin-side footer
	 */
	
	public function trace() {
		global $txptrace, $production_status;
	
		if($txptrace && $production_status == 'debug') {
			echo n.comment('txp tag trace: '.n.str_replace('--', '&shy;&shy;', implode(n, $txptrace)).n);
		}
	}
}

new rah_debug();