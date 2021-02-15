<?php
namespace Core\SRC;
trait Validation{
    /**
     * @var string[]
     */
    private $messages = [
        'required' => 'This field can not be blank',
        'email' => 'Please enter valid email address',
        'string' => 'This field must contain only letters',
        'strong' => 'Password is not strong enough. Try a combination of letters, numbers and symbols.',
        'min' => 'The :field must be at least :count characters.'
    ];

    /**
     * @var bool
     */
    private $_passed = false;
    private $_errors = array();
    private $data;
    private $rules;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($rules)
    {
        foreach ($rules as $key => $value) {
            $rule = explode('|', $value);
            foreach ($rule as $val) {
                $this->check($val, $key);
            }
        }
    }

    private function required($rule, $type)
    {
        if (empty($this->data[$type])) {
            $this->setError($type, $rule, $this->messages['required']);
        }
    }

    private function min($rule, $value, $type)
    {
        if (!empty($this->data[$type])) {
            if (mb_strlen($this->data[$type]) < $value) {
                $this->setError($type, $rule, str_replace([':field', ':count'], [$type, $value], $this->messages['min']));
            }
        }

    }

    private function max($rule, $value, $type)
    {
        if (!empty($this->data[$type])) {
            if (strlen($this->data[$type]) > $value) {
                $this->setError($type, $rule, "Maximum {$value} simbol");
            }
        }
    }

    private function email($rule, $type)
    {
        if (!empty($this->data[$type])) {
            if (!filter_var($this->data[$type], FILTER_VALIDATE_EMAIL)) {
                $this->setError($type, $rule, $this->messages['email']);
            }
        }
    }

    private function string($rule, $value, $type)
    {
        if (!ctype_alpha($this->data[$type])) {
            $this->setError($type, $rule, $this->messages['string']);
        }
    }

    private function strong($rule, $value, $type)
    {
        if (!preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $this->data[$type])) {
            $this->setError($type, $rule, $this->messages['strong']);
        }
    }

    /**
     * @param $type
     * @param $name
     * @param $message
     */
    private function setError($type, $name, $message)
    {
        $this->_errors[$type][] = $message;
    }

    private function check($rules, $type)
    {
        $rul = '';
        if (strpos($rules, ':')) {
            $rul = explode(':', $rules);
            $rules = $rul[0];
        }

        switch ($rules) {
            case 'required':
                $this->required($rules, $type);
                break;
            case 'min':
                $this->min($rules, $rul[1], $type);
                break;
            case 'max':
                $this->max($rules, $rul[1], $type);
                break;
            case 'email':
                $this->email($rules, $type);
                break;
            case 'strong':
                $this->strong($rules, $rul[1], $type);
                break;
            case 'string':
                $this->string($rules, $rul[1], $type);
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }

    public function success()
    {
        return empty($this->_errors);
    }
}