<?php

namespace Plugins\GRecaptcha\Filters;

// Сторонние зависимости.
use FilterComments;
use Plugins\GRecaptcha\GRecaptcha;

class GRecaptchaCommentsFilter extends FilterComments
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

    public function addCommentsForm($newsID, &$tvars)
    {
        return $this->recaptcha->registerHtmlVars();
    }

    public function addComments($userRec, $newsRec, &$tvars, &$SQL)
    {
        if (! $this->recaptcha->verifying()) {
            return [
        		'errorText' => $this->recaptcha->rejectionReason(),

        	];
        }

        return true;
    }
}
