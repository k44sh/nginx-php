<?php
// Get variables
$get_name	=	filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
$get_key	=	filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);

// Only alphanumeric
$get_name	=	preg_replace('/[^a-zA-Z0-9]/', '', $get_name);
$get_key	=	preg_replace('/[^a-zA-Z0-9]/', '', $get_key);

// Accounts
$accounts	=	array(
	0	=>	array(
		"name"	=>	"user1",
		"key"	=>	"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
	),
	1	=>	array(
		"name"	=>	"user2",
		"key"	=>	"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
	),
	2	=>	array(
		"name"	=>	"user3",
		"key"	=>	"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
	)
);

// Verification
foreach ($accounts as $key => $id) {
	if ( ($id['name'] === $get_name) && ($id['key'] === $get_key) ) {
		http_response_code(200);
		break;
	}
	http_response_code(403);
}
?>