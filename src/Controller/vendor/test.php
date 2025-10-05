<?php 
require("autoload.php");
use \DrewM\MailChimp\MailChimp;

$MailChimp = new MailChimp('d820a3bfa08ba63b761af322d9215939-us10');

$list_id = '15fc0b3bb9';

$result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => 'sanish@intensofy.com',
                'status'        => 'subscribed',
            ]);

print_r($result);