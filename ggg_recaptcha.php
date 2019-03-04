<?php

// Protect against hack attempts.
if (! defined('NGCMS')) {
    die('HAL');
}

global $userROW;
if (is_array($userROW)) {
    return true;
}

loadPluginLibrary('ggg_recaptcha', 'lib');

$ggg_recaptcha = GGGRecaptcha::getInstance();

if (pluginGetVariable('ggg_recaptcha', 'modal_support')) {
    $ggg_recaptcha->htmlVars();
}

class GGGRecaptchaCore extends CoreFilter
{
    protected $recaptcha;

    public function __construct($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    public function registerUserForm(&$tvars)
    {
        return $this->recaptcha->htmlVars();
    }

    public function registerUser($params, &$msg)
    {
        if (! $this->recaptcha->verifying()) {
            $msg = 'Registration failed. Google recaptcha protected.';

            return false;
        }

        return true;
    }
}

pluginRegisterFilter('core.registerUser', 'ggg_recaptcha', new GGGRecaptchaCore($ggg_recaptcha));

if (getPluginStatusActive('comments')) {
    loadPluginLibrary('comments', 'lib');
    
    class GGGRecaptchaComments extends FilterComments
    {
        protected $recaptcha;

        public function __construct($recaptcha)
        {
            $this->recaptcha = $recaptcha;
        }

        public function addCommentsForm($newsID, &$tvars)
        {
            return $this->recaptcha->htmlVars();
        }

        public function addComments($userRec, $newsRec, &$tvars, &$SQL)
        {
            return $this->recaptcha->verifying();
        }
    }
    
    pluginRegisterFilter('comments', 'ggg_recaptcha', new GGGRecaptchaComments($ggg_recaptcha));
}

if (getPluginStatusActive('feedback')) {
    loadPluginLibrary('feedback', 'common');
    
    class GGGRecaptchaFeedback extends FeedbackFilter
    {
        protected $recaptcha;

        public function __construct($recaptcha)
        {
            $this->recaptcha = $recaptcha;
        }

        public function onShow($formID, $formStruct, $formData, &$tvars)
        {
            return $this->recaptcha->htmlVars();
        }

        public function onProcessEx($formID, $formStruct, $formData, $flagHTML, &$tVars, &$tResult)
        {
            return $this->recaptcha->verifying();
        }
    }
    
    pluginRegisterFilter('feedback', 'ggg_recaptcha', new GGGRecaptchaFeedback($ggg_recaptcha));
}
