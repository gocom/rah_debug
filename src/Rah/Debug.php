<?php

/*
 * rah_debug - Plug-in for Textpattern CMS
 * https://github.com/gocom/rah_debug
 *
 * Copyright (C) 2019 Jukka Svahn
 *
 * This file is part of rah_debug.
 *
 * rah_debug is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, version 2.
 *
 * rah_debug is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with rah_debug. If not, see <http://www.gnu.org/licenses/>.
 */

final class Rah_Debug
{
    /**
     * Currently logged in user.
     *
     * @var string
     */
    private $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $txp_user;

        add_privs('rah_debug_visible', '1');

        if (!$txp_user) {
            $user = is_logged_in();

            if ($user) {
                $this->user = $user['name'];
            }
        } else {
            $this->user = $txp_user;
        }

        $this->runner();
    }

    /**
     * Turns debugging mode on for privileged users.
     */
    public function runner()
    {
        global $prefs, $production_status;

        if ($this->user && has_privs('rah_debug_visible', $this->user)) {
            $prefs['production_status'] = $production_status = 'debug';
            set_error_level('debug');
        }
    }
}
