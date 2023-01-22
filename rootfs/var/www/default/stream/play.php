<?php
// Get variables
$get_name	=	filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
$get_pass	=	filter_input(INPUT_GET, 'pass', FILTER_SANITIZE_STRING);

// Only alphanumeric
$get_name	=	preg_replace('/[^a-zA-Z0-9]/', '', $get_name);
$get_pass	=	preg_replace('/[^a-zA-Z0-9]/', '', $get_pass);

// Accounts
$accounts	=	array(
	0	=>	array(
		"name"	=>	"user1",
		"pass"	=>	"xxxxxxxxxxxxxxxx"
	),
	1	=>	array(
		"name"	=>	"user2",
		"pass"	=>	"xxxxxxxxxxxxxxxx"
	),
	2	=>	array(
		"name"	=>	"user3",
		"pass"	=>	"xxxxxxxxxxxxxxxx"
	)
);

// Verification
foreach ($accounts as $key => $id) {
	if ( ($id['name'] === $get_name) && ($id['pass'] === $get_pass) ) {
		http_response_code(200);
		break;
	}
	http_response_code(403);
}
?>