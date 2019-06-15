<?php 

function request($key = "") {
	$CI =& get_instance();
	if($key != "")
		return $CI->input->post($key);

	if(empty($CI->input->post())) 
		return "";

	return (object) $CI->input->post();
}


function dd($data) {
	die(json_encode($data));
}

function setSession($data)
{
	$CI =& get_instance();
	$CI->session->set_userdata($data);
}

function getSession($name)
{
	$CI =& get_instance();
	return $CI->session->userdata($name);
}

function setFlash($data)
{
	$CI =& get_instance();
	$CI->session->set_flashdata($data);
}

function getFlash($name)
{
	$CI =& get_instance();
	return $CI->session->flashdata($name);
}


function asset_url()
{
	return base_url("asset/");
}

function sendMail($to, $data = []) {
    if(empty($data)) {
        return "Error, Please provide all needed infomration";
    }

    extract($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAIL_API_KEY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.MAIL_API_DOMAIN.'/messages');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'from' => $from,
        'to' => $to,
        'subject' => $subject,
        'html' => $message
    ));

        //Todo: Open up so emails can go through
    $result = curl_exec($ch);

    if(! $result) {
        return "Error: Mail Sending Failed.";
    }
    curl_close($ch);
}

function user()
{
    return getSession('staffdata');
}