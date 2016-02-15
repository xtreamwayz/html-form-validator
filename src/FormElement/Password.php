<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StripNewlines;

class Password extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultFilters()
    {
        $this->attachFilterByName(StripNewlines::class);
    }

    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators()
    {
        $stringlengthOptions = [];
        if ($this->element->hasAttribute('maxlength')) {
            $stringlengthOptions['max'] = $this->element->getAttribute('maxlength');
        }
        if ($this->element->hasAttribute('minlength')) {
            $stringlengthOptions['min'] = $this->element->getAttribute('minlength');
        }
        if (!empty($stringlengthOptions)) {
            $this->attachValidatorByName('stringlength', $stringlengthOptions);
        }

        if ($this->element->hasAttribute('pattern')) {
            $this->attachValidatorByName('regex', [
                'pattern' => sprintf('/%s/', $this->element->getAttribute('pattern')),
            ]);
        }
    }
}
