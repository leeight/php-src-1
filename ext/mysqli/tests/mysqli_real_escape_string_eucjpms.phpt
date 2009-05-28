--TEST--
mysqli_real_escape_string() - eucjpms
--SKIPIF--
<?php
if (ini_get('unicode.semantics'))
	die("skip Test cannot be run in unicode mode");

require_once('skipif.inc');
require_once('skipifemb.inc');
require_once('skipifconnectfailure.inc');
require_once('connect.inc');

if (!$link = mysqli_connect($host, $user, $passwd, $db, $port, $socket)) {
	die(sprintf("skip Cannot connect to MySQL, [%d] %s\n",
		mysqli_connect_errno(), mysqli_connect_error()));
}
if (!mysqli_set_charset($link, 'eucjpms'))
	die(sprintf("skip Cannot set charset 'eucjpms'"));
mysqli_close($link);
?>
--FILE--
<?php
	require_once("connect.inc");
	require_once('table.inc');

	var_dump(mysqli_set_charset($link, "eucjpms"));

	if ('この組み合わせでは\\\\この組み合わせでは' !== ($tmp = mysqli_real_escape_string($link, 'この組み合わせでは\\この組み合わせでは')))
		printf("[004] Expecting \\\\, got %s\n", $tmp);

	if ('この組み合わせでは\"この組み合わせでは' !== ($tmp = mysqli_real_escape_string($link, 'この組み合わせでは"この組み合わせでは')))
		printf("[005] Expecting \", got %s\n", $tmp);

	if ("この組み合わせでは\'この組み合わせでは" !== ($tmp = mysqli_real_escape_string($link, "この組み合わせでは'この組み合わせでは")))
		printf("[006] Expecting ', got %s\n", $tmp);

	if ("この組み合わせでは\\nこの組み合わせでは" !== ($tmp = mysqli_real_escape_string($link, "この組み合わせでは\nこの組み合わせでは")))
		printf("[007] Expecting \\n, got %s\n", $tmp);

	if ("この組み合わせでは\\rこの組み合わせでは" !== ($tmp = mysqli_real_escape_string($link, "この組み合わせでは\rこの組み合わせでは")))
		printf("[008] Expecting \\r, got %s\n", $tmp);

	if ("この組み合わせでは\\0この組み合わせでは" !== ($tmp = mysqli_real_escape_string($link, "この組み合わせでは" . chr(0) . "この組み合わせでは")))
		printf("[009] Expecting %s, got %s\n", "この組み合わせでは\\0この組み合わせでは", $tmp);

	var_dump(mysqli_query($link, 'INSERT INTO test(id, label) VALUES (100, "こ")'));

	mysqli_close($link);
	print "done!";
?>
--EXPECTF--
bool(true)
bool(true)
done!