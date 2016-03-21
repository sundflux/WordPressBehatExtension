<?php

/**
 * Fake sending email. In fact just write a file to the filesystem, so
 * a test service can read it.
 *
 * @param string|array $to Array or comma-separated list of email addresses to send message.
 * @param string $subject Email subject
 * @param string $message Message contents
 *
 * @return bool True if the email got sent (i.e. if the fake email file was written)
 */
function wp_mail( $to, $subject, $message ) {
	$file_name = sanitize_file_name( time() . "-$to" );
	$file_path = trailingslashit( WORDPRESS_FAKE_MAIL_DIR ) . $file_name;
	$content = "TO: $to" . PHP_EOL;
	$content .= "SUBJECT: $subject" . PHP_EOL;
	$content .= WORDPRESS_FAKE_MAIL_DIVIDER . PHP_EOL . $message;
	if ( !is_dir( WORDPRESS_FAKE_MAIL_DIR ) ) {
		mkdir( WORDPRESS_FAKE_MAIL_DIR, 0777, true );
	}

	$result = (bool) file_put_contents( $file_path, $content );

	if ( ! $result ) {
		var_dump( scandir( rtrim(WORDPRESS_FAKE_MAIL_DIR, DIRECTORY_SEPARATOR) ) );
		var_dump( $result );
	}

	echo "Emailing {$to}; {$subject}";

	return $result;
}