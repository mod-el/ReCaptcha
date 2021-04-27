<?php namespace Model\ReCaptcha;

use Model\Core\Module;

class ReCaptcha extends Module
{
	public function headings()
	{
		$config = $this->retrieveConfig();
		?>
		<script src="https://www.google.com/recaptcha/api.js?render=<?= entities($config['public']) ?>"></script>
		<script>
			var RECAPTCHA_PUBLIC_KEY = <?=json_encode($config['public'])?>;
		</script>
		<?php
	}

	public function verifyPost(string $tokenName = 'g-token')
	{
		if (empty($_POST[$tokenName])) {
			http_response_code(401);
			throw new \Exception('Missing token');
		}

		$this->verify($_POST[$tokenName]);

		unset($_POST[$tokenName]);
	}

	public function verify(string $token)
	{
		$config = $this->retrieveConfig();

		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $config['private'] . "&response=" . $token . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
		$response = json_decode($response);

		if ($response->success === false)
			throw new \Exception('Unauthorized');
		elseif ($response->score <= 0.5)
			throw new \Exception('Unauthorized');
	}
}
