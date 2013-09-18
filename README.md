strider-flowdock-webhook
========================

A webhook that posts strider build messages into flowdock

Setup
=====

Add a webhook for each project like this:
* Hook Title: Flowdock
* TargetURL for HTTP Post: http://wherever.this.file.is.hosted.com/strider.php?repo=repoName
* Shared secret for HMAC-SHA1 signatire: <doesn't matter>


Define channels like this in config.php
```ruby
$flowdockChannels = array(
    // this has to match the repo name you define in the webhook:
    'repoName' => 
       array(
         'flowdockUrl' => 'https://api.flowdock.com/v1/messages/chat/<yourFlowdockToken>',
         'striderUrl' => 'http://strider.yourcompany.com/yourProject/yourRepo/'
       )
);
```
