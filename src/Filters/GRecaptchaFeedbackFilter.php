<?php

namespace Plugins\GRecaptcha\Filters;

// Сторонние зависимости.
use FeedbackFilter;
use Plugins\GRecaptcha\GRecaptcha;

class GRecaptchaFeedbackFilter extends FeedbackFilter
{
    /**
     * [protected description]
     * @var GRecaptcha
     */
    protected $recaptcha;

    public function __construct(GRecaptcha $recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    public function onShow($formID, $formStruct, $formData, &$tvars)
    {
        return $this->recaptcha->htmlVars();
    }

    public function onProcessEx($formID, $formStruct, $formData, $flagHTML, &$tVars, &$tResult)
    {
        if (! $this->recaptcha->verifying()) {
            $tResult['rawmsg'] = $this->recaptcha->rejectionReason();

            return false;
        }

        return true;
    }
}
