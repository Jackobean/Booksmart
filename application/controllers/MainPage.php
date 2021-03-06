<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mathieumolinengo
 * Date: 17/02/2016
 * Time: 17:50
 */
class MainPage extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }


    public function index()
    {
        $this->load->view('static_page');
    }


    public function login(){

        $this->load->library('facebook'); // Automatically picks appId and secret from config
        // OR
        // You can pass different one like this
        //$this->load->library('facebook', array(
        //    'appId' => 'APP_ID',
        //    'secret' => 'SECRET',
        //    ));

        $user = $this->facebook->getUser();

        if ($user) {
            try {
                $data['user_profile'] = $this->facebook->api('/me');
            } catch (FacebookApiException $e) {
                $user = null;
            }
        }else {
            $this->facebook->destroySession();
        }

        if ($user) {

            $data['logout_url'] = site_url('welcome/logout'); // Logs off application
            // OR
            // Logs off FB!
            // $data['logout_url'] = $this->facebook->getLogoutUrl();

        } else {
            $data['login_url'] = $this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('welcome/login'),
                'scope' => array("email") // permissions here
            ));
        }
        $this->load->view('login',$data);

    }

    public function logout(){

        $this->load->library('facebook');

        // Logs off session from website
        $this->facebook->destroySession();
        // Make sure you destory website session as well.

        redirect('welcome/login');
    }

}