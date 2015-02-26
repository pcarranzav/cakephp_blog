<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

class User extends AppModel {
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            ),
            'valid' => array(
                'rule' => 'isUnique',
                'message' => 'Username already taken'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'author')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        )
    );
    
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }

    public function isAuthorizedForLogin($user) {

        $attempts = $user['User']['num_login_attempts'];
        $last_attempt = $user['User']['last_login_attempt'];

        if(($attempts >= 3) && (CakeTime::wasWithinLast("2 hours", $last_attempt))) {
            return false;
        }

        return true;
    }

    public function addLoginAttempt($user) {
        $attempts = $user['User']['num_login_attempts'];
        $attempts += 1;
        $user['User']['num_login_attempts'] = $attempts;
        $user['User']['last_login_attempt'] = DboSource::expression('NOW()');
        $this->save($user,true,array('num_login_attempts','last_login_attempt'));
    }

    public function clearLoginAttempts($user) {
        $user['User']['num_login_attempts'] = 0;

        $this->save($user,true,array('num_login_attempts'));
    }
}
