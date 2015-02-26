<?php
class AppController extends Controller {
    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'posts',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'posts',
                'action' => 'index'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            )
        )
    );

    public function beforeFilter() {
        $this->Auth->allow('index', 'view');

        if($this->Auth->user()) {
            $this->set('current_user',$this->Auth->user('username'));
            $this->set('log_text', "Logout");
            $this->set('log_url', array('controller' => 'users','action' => 'logout'));
        }else{
            $this->set('current_user','Not logged in');
            $this->set('log_text', "Login");
            $this->set('log_url', array('controller' => 'users','action' => 'login'));
        }
    }
}