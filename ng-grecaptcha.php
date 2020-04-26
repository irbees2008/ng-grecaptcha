<?php

// Защита от попыток взлома.
if (!defined('NGCMS')) {
    die('HAL');
}

// Подгрузка библиотек-файлов плагина.
loadPluginLibrary('ng-grecaptcha', 'autoload');

// Подгрузка языкового файла плагина.
LoadPluginLang('ng-grecaptcha', 'main', '', '', ':');

// Плагин использует отрисовку шаблонов, подгружаем трейт.
loadPluginLibrary('ng-helpers', 'renderable');

// Используем функции из пространства `Plugins`.
use function Plugins\setting;

// Проверяем, что капчу нужно использовать только для гостей сайта.
if (setting('ng-grecaptcha', 'guests_only', true)) {
    global $userROW;

    if (is_array($userROW) && is_numeric($userROW['id'])) {
        return true;
    }
}

$grecaptcha = new Plugins\GRecaptcha\GRecaptcha();

// Если включена поддержка модальных окон.
if (setting('ng-grecaptcha', 'modal_support', false)) {
    $grecaptcha->registerHtmlVars();
}

pluginRegisterFilter('core.registerUser', 'ng-grecaptcha', new Plugins\GRecaptcha\Filters\GRecaptchaCoreFilter($grecaptcha));

// Если активирован плагин комментариев.
if (getPluginStatusActive('comments')) {
    loadPluginLibrary('comments', 'lib');

    pluginRegisterFilter('comments', 'ng-grecaptcha', new Plugins\GRecaptcha\Filters\GRecaptchaCommentsFilter($grecaptcha));
}

// Если активирован плагин обратной связи.
if (getPluginStatusActive('feedback')) {
    loadPluginLibrary('feedback', 'common');

    pluginRegisterFilter('feedback', 'ng-grecaptcha', new Plugins\GRecaptcha\Filters\GRecaptchaFeedbackFilter($grecaptcha));
}
