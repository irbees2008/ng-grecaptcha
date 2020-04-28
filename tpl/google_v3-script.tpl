<script>
document.addEventListener('DOMContentLoaded', function() {
    // �������� `HTMLCollection`, ���������� ��� ����� ���������.
    const FORMS = document.getElementsByTagName('form');

    // ���������� ��� ��������� ����� � ���������.
    [...FORMS].forEach(function(form) {
        // ���� ����� �������� ���� ����� �����:
        if ('g-recaptcha-response' in form.elements) {
            // ����� ������������ �� ��������� �������� ������� `onsubmit`.
            // ��� ������������ ����������������� �����
            // ������������ �������� ����� ��������.
            if (form.hasAttribute('onsubmit')) {
                form.setAttribute('data-onsubmit', form.getAttribute('onsubmit'))
                form.removeAttribute('onsubmit');
            }

            // �� ������ ���������� ������� �������� �����.
            form.addEventListener('submit', attachGRecaptchaToken);
        }
    });

    /**
     * ������������ ������ � ����� ��� ��������.
     * @param  {Event} event
     * @return {void}
     */
    function attachGRecaptchaToken(event) {
        // �������� ����������� ��������� �����.
        event.preventDefault();

        // �������� �������� �� �������.
        const form = event.target;
        const input = form.elements['g-recaptcha-response'];

        // ���� ����� ������.
        grecaptcha.ready(function() {
            // ��������� ������ � ������� Google ��� ��������� ������.
            grecaptcha.execute('{{ site_key }}', {
                    action: form.id || '{{ action }}'
                })
                .then(function(token) {
                    // ������ ���������� ����� ���� ����� �����.
                    input.value = token;

                    let result = true;

                    // ����� ������������ �� ��������� �������� ������� `onsubmit`.
                    // ��� ������������ ����������������� �����
                    // ��������� ����������� �������� ����� ��������.
                    if (form.hasAttribute('data-onsubmit')) {
                        result = (new Function(form.getAttribute('data-onsubmit')))();
                    }

                    // � ��������� ������� ������ ���������� �����.
                    if (result) {
                        form.submit();
                    }
                }, function (reason) {
                    console.log(reason);
                });
        });
    }
});

</script>
