<?php

/**
 * Rah_debug plugin for Textpattern CMS.
 *
 * @author  Jukka Svahn
 * @date    2012-
 * @license GNU GPLv2
 * @link    https://github.com/gocom/rah_debug
 * 
 * Copyright (C) 2013 Jukka Svahn http://rahforum.biz
 * Licensed under GNU Genral Public License version 2
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

class rah_debug
{
	/**
	 * Currently logged in user.
	 *
	 * @var string
	 */

	public $user = false;

	/**
	 * Constructor.
	 */

	public function __construct()
	{
		global $txp_user;

		add_privs('rah_debug_visible', '1');
		register_callback(array($this, 'trace'), 'admin_side', 'body_end');

		if (!$txp_user)
		{
			$user = is_logged_in();

			if ($user)
			{
				$this->user = $user['name'];
			}
		}
		else
		{
			$this->user = $txp_user;
		}

		$this->runner();
	}

	/**
	 * Turns debugging mode on for certain logged in users.
	 */

	public function runner()
	{
		global $prefs, $production_status;

		if ($this->user && has_privs('rah_debug_visible', $this->user))
		{
			$prefs['production_status'] = $production_status = 'debug';
			set_error_level('debug');
		}
	}

	/**
	 * Adds tag trace to the admin-side footer.
	 */

	public function trace()
	{
		global $txptrace, $production_status;

		if ($txptrace && $production_status == 'debug')
		{
			echo n.comment('txp tag trace: '.n.str_replace('--', '&shy;&shy;', implode(n, $txptrace)).n);
		}
	}
}

new rah_debug();