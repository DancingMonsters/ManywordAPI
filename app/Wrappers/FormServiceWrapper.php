<?php

namespace App\Wrappers;

use Exception;
use Illuminate\Http\Request;

class FormServiceWrapper
{
    public array $messages = [];
    protected Request $request;
    protected $dictionary;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->setDictionary();
        $this->check();
    }

    protected function putMessage(string $name)
    {
        $this->messages[] = $this->dictionary[$name];
    }

    protected function check() {}

    public function validate(): bool
    {
        return (count($this->messages) == 0);
    }

    protected function setDictionary() {}
}
