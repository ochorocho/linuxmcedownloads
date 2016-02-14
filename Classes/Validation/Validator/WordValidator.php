<?php

namespace Schulung\Simpleblog\Validation\Validator;

class WordValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {

    protected $supportedOptions = array(
        'max' => array(3, 'Maximum word length for a valid string', 'integer'),
    );

    /**
     * @param mixed $property
     */
    public function isValid($property){
        $max = $this->options['max'];

        if (str_word_count($property,0) <= $max){
            return TRUE;
        } else {
            $this->addError('Verringern Sie die Anzahl der Worte - es sind nur ' . $max . ' Worte erlaubt',12515212415);
            return FALSE;
        }
    }

}