<?php
public function ios_notification_get($sid, $msg, $token)
	{
		error_reporting(0);

		// $deviceToken ='e4cfa824a795e38820b615feb4dad1947cb50de1d86cdf979f4735f3118a7c92';

		$deviceToken = $token;

		// Put your private key's passphrase here:

		$passphrase = 'apple';

		// Put your alert message here:

		$message = $msg;

		// //////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server

		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp) exit("Failed to connect: $err $errstr" . PHP_EOL);

		// echo $fp;
		// echo 'Connected to APNS' . PHP_EOL;
		// Create the payload body

		$body['aps'] = array(
			'alert' => $message,
			'sound' => 'default'
		);
		$body['extra'] = array(
			'type' => 'chat',
			'senderid' => $sid
		);

		// Encode the payload as JSON

		$payload = json_encode($body);

		// Build the binary notification

		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		// Send it to the server

		$result = fwrite($fp, $msg, strlen($msg));
		if (!$result)
		{

			// echo 'Message not delivered' . PHP_EOL;
			// echo $fp;

		}
		else
		{

			// echo 'Message successfully delivered' . PHP_EOL;
			// echo $fp;

		}

		// Close the connection to the server

		fclose($fp);
	}
?>