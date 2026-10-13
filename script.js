document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', event => {
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.setCustomValidity('');

            if (field.type !== 'hidden' &&
                field.required && !field.value.trim()) {
                field.setCustomValidity('Please complete this field.');
            }

            if (field.maxLength > 0 &&
                new TextEncoder().encode(field.value).length > field.maxLength) {
                field.setCustomValidity('This value is too long. Please shorten it.');
            }
        });

        const password = form.querySelector('#password');
        const confirm = form.querySelector('#confirm');

        if (confirm && confirm.value !== password.value) {
            confirm.setCustomValidity('Passwords must match.');
        }

        const upload = form.querySelector('input[type="file"]');
        const file = upload?.files[0];

        if (file && (
            !/\.(pdf|docx|txt)$/i.test(file.name) ||
            file.size === 0 ||
            file.size > 5 * 1024 * 1024
        )) {
            upload.setCustomValidity(
                'Choose a PDF, DOCX or TXT file between 1 byte and 5 MB.'
            );
        }

        if (!form.reportValidity()) {
            event.preventDefault();
        }
    });

    form.addEventListener('input', () => {
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.setCustomValidity('');
        });
    });
});

const search = document.getElementById('search');

if (search) {
    let controller;

    async function load() {
        if (controller) controller.abort();

        controller = new AbortController();

        const results = document.getElementById('results');
        results.textContent = 'Loading assignments...';

        try {
            const response = await fetch(
                'search.php?q=' + encodeURIComponent(search.value),
                {signal: controller.signal}
            );

            if (response.redirected) {
                location.href = response.url;
                return;
            }

            if (!response.ok) throw new Error();

            results.innerHTML = await response.text();
        } catch (error) {
            if (error.name !== 'AbortError') {
                results.textContent = 'Cannot load assignments. Please try again.';
            }
        }
    }

    search.addEventListener('input', load);
    load();
}