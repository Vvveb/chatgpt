<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

/*
Name: ChatGPT
Slug: chatgpt
Category: tools
Url: https://www.vvveb.com
Description: Add ChatGPT prompt toolbar button to content editor on post and product edit page and chatgpt prompt component in site editor.
Author: givanz
Version: 0.1
Thumb: chatgpt.svg
Author url: https://www.vvveb.com
Settings: /admin/index.php?module=plugins/chatgpt/settings
*/

use \Vvveb\System\Event as Event;

if (! defined('V_VERSION')) {
	die('Invalid request!');
}

class ChatgptPlugin {
	function addTinyMce() {
		Event::on('Vvveb\System\Core\View', 'compile', __CLASS__, function ($template, $htmlFile, $tplFile, $vTpl, $view) {
			//insert js on post and product page
			if ($template == 'content/post.html' || $template == 'product/product.html' || $template == 'editor/editor.html' || $template == 'content/menus/menu.html') {
				$defaults = [
					'key'             => '',
					'model'           => 'gpt-3.5-turbo-instruct',
					'temperature'     => 0,
					'max_tokens'      => 300,
				];

				$options = Vvveb\getSetting('chatgpt', ['key', 'model', 'temperature', 'max_tokens']);
				$options = $options + $defaults;
				$json = json_encode($options);
				$script = "'<script>chatgptOptions = $json;</script>";

				if ($template == 'content/post.html' || $template == 'product/product.html' || $template == 'content/menus/menu.html') {
					$script .= '<script src="../../plugins/chatgpt/chatgpt-tinymce.js"></script>\'';

				//insert script
					//$vTpl->loadTemplateFile(__DIR__ . '/app/template/common.pst');
				} else {
					if ($template == 'editor/editor.html') {
						$script .= '<script src="../../plugins/chatgpt/chatgpt-vvvebjs.js"></script>\'';
					}
				}

				$vTpl->addCommand('body|append', $script);
			}

			return [$template, $htmlFile, $tplFile, $vTpl, $view];
		});
	}

	function admin() {
		// add admin menu item
		$admin_path = \Vvveb\adminPath();
		Event::on('Vvveb\Controller\Base', 'init-menu', __CLASS__, function ($menu) use ($admin_path) {
			$menu['plugins']['items']['chatgpt'] = [
				'name'     => 'ChatGPT',
				'url'      => $admin_path . 'index.php?module=plugins/chatgpt/settings',
				'icon-img' => PUBLIC_PATH . 'plugins/chatgpt/chatgpt.svg',
			];

			return [$menu];
		});

		//insert ask chatgpt button in tinymce editor toolbar for post and product admin templates
		$this->addTinyMce();
	}

	function app() {
	}

	function __construct() {
		if (APP == 'admin') {
			$this->admin();
		} else {
			if (APP == 'app') {
				$this->app();
			}
		}
	}
}

$chatgptPlugin = new ChatgptPlugin();
