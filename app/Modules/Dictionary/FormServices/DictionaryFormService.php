<?php

namespace App\Modules\Dictionary\FormServices;

use App\Modules\Dictionary\Dictionaries\WordFormDictionary;
use App\Modules\Dictionary\Repositories\DictionaryRepository;
use App\Wrappers\FormServiceWrapper;

class DictionaryFormService extends FormServiceWrapper
{
    public DictionaryRepository $dictionaryRepository;

    protected function check()
    {
        $this->dictionaryRepository = new DictionaryRepository();

        $word = null;
        $particle = null;
        $language = null;

        if ($this->request->filled('word')) {
            $word = $this->request->post('word');
        } else {
            $this->putMessage('not_word');
        }

        if ($this->request->filled('particle')) {
            $particle = $this->request->post('particle');
        } else {
            $this->putMessage('not_particle');
        }

        if ($this->request->filled('language')) {
            $language = $this->request->post('language');
        } else {
            $this->putMessage('not_language');
        }

        if ($word !== null && $particle !== null && $language !== null) {
            if ($this->dictionaryRepository->getWordIdByName($word, $particle, $language) !== null) {
                $this->putMessage('word_exists');
            }
        }
    }

    protected function setDictionary()
    {
        $this->dictionary = WordFormDictionary::$messages;
    }
}
