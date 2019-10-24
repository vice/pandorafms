<?php
/**
 * Credential store
 *
 * @category   WelcomeWindow
 * @package    Pandora FMS
 * @subpackage New Installation Welcome Window
 * @version    1.0.0
 * @license    See below
 *
 *    ______                 ___                    _______ _______ ________
 *   |   __ \.-----.--.--.--|  |.-----.----.-----. |    ___|   |   |     __|
 *  |    __/|  _  |     |  _  ||  _  |   _|  _  | |    ___|       |__     |
 * |___|   |___._|__|__|_____||_____|__| |___._| |___|   |__|_|__|_______|
 *
 * ============================================================================
 * Copyright (c) 2005-2019 Artica Soluciones Tecnologicas
 * Please see http://pandorafms.org for full contribution list
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation for version 2.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * ============================================================================
 */

// Begin.

/**
 * Class WelcomeWindow.
 */

global $config;

require_once $config['homedir'].'/include/class/WelcomeWindow.class.php';

$ajaxPage = 'general/new_installation_welcome_window';

// Control call flow.
try {
    // User access and validation is being processed on class constructor.
    $welcome_actions = new WelcomeWindow($ajaxPage);
} catch (Exception $e) {
    if (is_ajax()) {
        echo json_encode(['error' => '[WelcomeWindow]'.$e->getMessage() ]);
        exit;
    } else {
        echo '[WelcomeWindow]'.$e->getMessage();
    }

    // Stop this execution, but continue 'globally'.
    return;
}

// Ajax controller.
if (is_ajax()) {
    $method = get_parameter('method', '');

    if (method_exists($welcome_actions, $method) === true) {
        if ($welcome_actions->ajaxMethod($method) === true) {
            $welcome_actions->{$method}();
        } else {
            $welcome_actions->error('Unavailable method.');
        }
    } else {
        $welcome_actions->error('Method not found. ['.$method.']');
    }


    // Stop any execution.
    exit;
} else {
    // Run.
    $welcome_actions->run();
}
