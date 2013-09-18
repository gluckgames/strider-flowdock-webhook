<?php
	/*
		Add a webhook for each project like this:
		Hook Title: Flowdock
		TargetURL for HTTP Post: http://wherever.this.file.is.hosted.com/strider.php?repo=repoName
		Shared secret for HMAC-SHA1 signatire: <doesn't matter>
	*/

	/*
	Define channels like this in config.php
	$flowdockChannels = array(
	    // this has to match the repo name you define in the webhook:
		'repoName' => 
			array(
				'flowdockUrl' => 'https://api.flowdock.com/v1/messages/chat/<yourFlowdockToken>',
				'striderUrl' => 'http://strider.yourcompany.com/yourProject/yourRepo/'
			)
	);
	*/
	include 'config.php';

	$payload = $_POST['payload'];

	$value = json_decode($payload);

	$testExitcode = $value->test_results->test_exitcode;
	$repoUrl = $value->test_results->repo_url;
	$commit = $value->test_results->github_commit_id;

	function sendToFlowdock($text, $repo) {
		global $flowdockChannels;
		$striderUrl = $flowdockChannels[$repo]['striderUrl'];
		$flowdockUrl = $flowdockChannels[$repo]['flowdockUrl'];

		$payload = array(
			"content" => $text . ' ' . $striderUrl,
			"external_user_name" => "Strider",
			"tags" => array("#strider")
		);

		$json = json_encode($payload);

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $flowdockUrl); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('content-type:application/json')); 
        curl_setopt($curl, CURLOPT_POST, true); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); 
        curl_exec($curl); 
        curl_close($curl); 
	}	

	if ($testExitcode == 0) {
		sendToFlowdock("Build ✓", $_GET['repo']);
	} else {
		sendToFlowdock("Build ☢", $_GET['repo']);
	}
