<?php


namespace WpPlugins\Winipayer\Controller;

use WpPlugins\Winipayer\Winipayer;

class AdminController
{

    public function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }


    public function admin_menu()
    {
        add_submenu_page('woocommerce', '', __('WiniPayer Plugins', 'winipayer-woocommerce'), 'manage_woocommerce', 'wc-winipayer-plugins', [$this, 'output']);
    }

    public function output()
    {
        Winipayer::render('html-main-page', []);
    }

}