## Защита форм сайта от интернет-ботов с Google reCAPTCHA v3

Предназначен для плагинов `comments`, `feedback`, а также для формы регистрации пользователей на сайте.

Плагин запрашивает у сервиса Google оценку действий пользователя без его участия для блокирования отправки форм ботами, которые чаще всего спамят.

### Системные требования

Перед использованием плагина вам необходимо убедиться, что ваш сервер соответствует следующим требованиям:

 - PHP >= 7.0.0
 - [russsiq/ng-helpers](https://github.com/russsiq/ng-helpers)

### Подключение

> Перед обновлением плагина, отключите его в панели управлении.

Плагин выпускается в двух вариациях, каждая из которых поддерживает свой тип кодировки: UTF-8 и Windows-1251. Для подключения вы можете просто скачать плагин в необходимой для вашего проекта кодировке:

 - [UTF-8](https://codeload.github.com/russsiq/ng-grecaptcha/zip/master)
 - [Windows-1251](https://codeload.github.com/russsiq/ng-grecaptcha/zip/windows-1251)

Либо воспользуйтесь менеджером Composer:

```bash
composer require russsiq/ng-grecaptcha:dev-master
```

> Обратите внимание, что кодировка UTF-8 является основной. Вы можете указать кодировку Windows-1251:

```bash
composer require russsiq/ng-grecaptcha:dev-windows-1251
```

### Настройка

 1. Перед использованием плагина зарегистрируйтесь и получите Ключ и Секретный ключ reCAPTCHA v3 [здесь](https://g.co/recaptcha/v3).
 1. Активируйте плагин `ng-grecaptcha` в админ. панели.
 1. Вставьте Ключ и Секретный ключ в соответствующие поля.
 1. Никаких дополнительных действий с плагином не требуется.

Теперь на вашем сайте в правом нижнем углу информационный блок от reCAPTCHA.

> Обратите внимание, зарегистрированные пользователи проверку не проходят.

### Использование

#### Плагин `comments`

Для использования в плагине `comments`, отредактируйте шаблон формы, добавив скрытое поле перед закрывающим тегом `</form>`:

```html
<!-- \templates\ВАШ_ШАБЛОН\plugins\comments\comments.form.tpl -->

<input type="hidden" name="g-recaptcha-response" value="" />
```

В этом же шаблоне добавить в секции `script` между `[not-logged] ... [/not-logged]`:

```javascript
cajax.setVar("g-recaptcha-response", form['g-recaptcha-response'].value);
```

Там же, до тегов `[captcha] ... [/captcha]`

```javascript
grecaptcha_reload();
```

#### Плагин `feedback`

Для использования в плагине `feedback`, отредактируйте шаблон формы, добавив скрытое поле перед закрывающим тегом `</form>`:

```html
<!-- \templates\ВАШ_ШАБЛОН\plugins\feedback\site.form.tpl -->

{% if not global.flags.isLogged %}
    <input type="hidden" name="g-recaptcha-response" value="" />
{% endif %}
```

#### Регистрационная форма

Для использования в форме регистрации пользователей, отредактируйте шаблон формы, добавив скрытое поле перед закрывающим тегом `</form>`:

```html
<!-- \templates\ВАШ_ШАБЛОН\registration.tpl -->

<input type="hidden" name="g-recaptcha-response" value="" />
```

#### Формы в модальных окнах

Для использования в формах модальных окон, отредактируйте шаблон формы, добавив скрытое поле перед закрывающим тегом `</form>`:

```html
{% if not global.flags.isLogged %}
    <input type="hidden" name="g-recaptcha-response" value="" />
{% endif %}
```

### Лицензия

`ng-grecaptcha` - программное обеспечение с открытым исходным кодом, распространяющееся по лицензии [MIT](https://choosealicense.com/licenses/mit/).