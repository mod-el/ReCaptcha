async function recaptchaGetToken(action) {
	return new Promise((resolve, reject) => {
		grecaptcha.ready(() => {
			grecaptcha.execute(RECAPTCHA_PUBLIC_KEY, {action}).then(token => {
				resolve(token);
			}).catch(error => {
				reject(error);
			});
		});
	});
}

async function recaptchaSendForm(form, action) {
	let submitButton = form.querySelector('input[type="submit"]');
	let oldText = '';
	if (submitButton) {
		oldText = submitButton.value;
		submitButton.value = 'Attendere...';
	}

	try {
		let token = await recaptchaGetToken(action);

		let input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'g-token';
		input.value = token;
		form.appendChild(input);
		form.submit();
	} catch (e) {
		if (submitButton)
			submitButton.value = oldText;
	}
}