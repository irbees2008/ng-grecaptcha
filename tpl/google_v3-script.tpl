<script src="{{ api_render ~ site_key }}"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Получаем `HTMLCollection`, содержащую все формы документа.
    const FORMS = document.getElementsByTagName('form');

    // Перебираем все найденные формы в документе.
    [...FORMS].forEach(function(form) {
        // Если форма содержит поле ввода капчи:
        if ('g-recaptcha-response' in form.elements) {
            // то вешаем обработчик события отправки формы.
            form.addEventListener('submit', attachGRecaptchaToken);
        }
    });

    /**
     * Прикрепление токена к форме при отправке.
     * @param  {Event} event
     * @return {void}
     */
    function attachGRecaptchaToken(event) {
        // Отменяем стандартное поведение формы.
        event.preventDefault();

        // Выбираем элементы из события.
        const form = event.target;
        const input = form.elements['g-recaptcha-response'];

        // Если капча готова.
        grecaptcha.ready(function() {
            // Выполняем запрос к сервису Google для получения токена.
            grecaptcha.execute('{{ site_key }}', {
                    action: form.id || '{{ action }}'
                })
                .then(function(token) {
                    // Задаем полученный токен полю ввода капчи.
                    input.value = token;

                    // Отправляем форму.
                    form.submit();

                }, function (reason) {
                    console.log(reason);
                });
        });
    }
});

</script>
