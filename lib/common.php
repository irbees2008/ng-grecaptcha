<?php

/**
 *
 */
final class GGGRecaptcha
{
    /**
     * @var Singleton
     */
    private static $instance;

    protected $plugin = 'ggg_recaptcha';
    protected $apiRender = 'https://www.google.com/recaptcha/api.js?render=';
    protected $apiVerify = 'https://www.google.com/recaptcha/api/siteverify';

    protected $siteKey;
    protected $secretKey;
    protected $score;

    protected $response;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from GGGRecaptcha::getInstance() instead
     */
    private function __construct()
    {
        $this->siteKey = trim(secure_html(pluginGetVariable($this->plugin, 'site_key'))) ?: null;
        $this->secretKey = trim(secure_html(pluginGetVariable($this->plugin, 'secret_key'))) ?: null;
        $this->score = (double) pluginGetVariable($this->plugin, 'score') ?: 0.5;
        $this->response = isset($_POST['g-recaptcha-response']) ? (trim(secure_html($_POST['g-recaptcha-response'])) ?: null) : null; // ?? to >= PHP 7.0
    }

    public function htmlVars()
    {
        if (empty($this->siteKey)) {
            return false;
        }

        register_htmlvar('js', $this->apiRender.$this->siteKey);
        register_htmlvar('plain', '<script>function grecaptcha_reload(){grecaptcha.ready(function(){grecaptcha.execute(\''.$this->siteKey.'\',{action:\'send_form\'}).then((token)=>{let elements=document.getElementsByName("g-recaptcha-response");for(let i=0;i<elements.length;i++){elements[i].value = token;}});});}grecaptcha_reload();</script>');

        return true;
    }

    public function verifying()
    {
        if (empty($this->secretKey) or empty($this->response)) {
            return false;
        }

        $verified = $this->touchAnswer();

        if (is_array($verified) and $verified['success'] and $verified['score'] >= $this->score) {
            return true;
        }

        return false;
    }

    protected function touchAnswer()
    {
        $query = $this->prepareQuery();

        if (extension_loaded('curl') and function_exists('curl_init')) {
            $answer = $this->getCurlAnswer($query);
        } elseif (ini_get('allow_url_fopen')) {
            $answer = $this->getFopenAnswer($query);
        } else {
            throw new Exception(
                'Not supported: cURL, allow_fopen_url.'
            );
        }

        $answer = json_decode($answer);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception('JSON answer error.');
        }

        return (array) $answer;
    }

    protected function prepareQuery()
    {
        return http_build_query([
            'secret' => $this->secretKey,
            'response' => $this->response
        ]);
    }

    protected function getCurlAnswer(string $query)
    {
        $ch = curl_init();
        if (curl_errno($ch) != 0) {
            throw new Exception(
                'err_curl_'.curl_errno($ch).' '.curl_error($ch)
            );
        }
        curl_setopt($ch, CURLOPT_URL, $this->apiVerify);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (404 == $status) {
            throw new Exception(
                'Source file not found.'
            );
        } elseif ($status != 200) {
            throw new Exception(
                'err_curl_'.$status
            );
        }

        curl_close($ch);

        return $answer;
    }

    protected function getFopenAnswer(string $query)
    {
        return file_get_contents(
            $this->apiVerify.'?'.$query
        );
    }

    /**
     * Prevent the instance from being cloned (which would create a second instance of it).
     */
    private function __clone()
    {
    }

    /**
     * Prevent from being unserialized (which would create a second instance of it).
     */
    private function __wakeup()
    {
    }
}
