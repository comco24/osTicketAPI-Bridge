<?php

$config = array(
        'url'=>'http://osticket.e1studio.sk/api/tickets.json',
        'key'=>'9D90873D2A3FB3935CE121647023E5EE'
        );

# Fill in the data for the new ticket, this will likely come from $_POST.

$data = array(
    'name'      =>      'John Weo',
    'email'     =>      'jw@host.com',
    'subject'   =>      'Test 1',
    'message'   =>      'Toto je test ticket',
    'ip'        =>      $_SERVER['REMOTE_ADDR'],
    'topicId'   =>      '12', // toto je ID help topicu (nan su naviazane custom forms a fields - nasledne pouzite variables posielame cez APInu)
    'v_farba'   =>      '2',
    'v_popis'   =>      '123456789',
    'attachments' => array(),
);

/*
 * Add in attachments here if necessary

$data['attachments'][] =
array('filename.pdf' =>
        'data:image/png;base64,' .
            base64_encode(file_get_contents('/path/to/filename.pdf')));
 */

#pre-checks
function_exists('curl_version') or die('CURL support required');
function_exists('json_encode') or die('JSON support required');

#set timeout
set_time_limit(30);

#curl post
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $config['url']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.7');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result=curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code != 201)
    die('Unable to create ticket: '.$result);

$ticket_id = (int) $result;

# Continue onward here if necessary. $ticket_id has the ID number of the
# newly-created ticket

?>
