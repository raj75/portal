<?php
class msgraphMailer {

    var $tenantID;
    var $clientID;
    var $clientSecret;
    var $Token;
    var $baseURL;

    function __construct($sTenantID, $sClientID, $sClientSecret) {
        $this->tenantID = $sTenantID;
        $this->clientID = $sClientID;
        $this->clientSecret = $sClientSecret;
        $this->baseURL = 'https://graph.microsoft.com/v1.0/';
        $this->Token = $this->getToken();
    }

    function getToken() {
        $oauthRequest = 'client_id=' . $this->clientID . '&scope=https%3A%2F%2Fgraph.microsoft.com%2F.default&client_secret=' . $this->clientSecret . '&grant_type=client_credentials';
        $reply = $this->sendPostRequest('https://login.microsoftonline.com/' . $this->tenantID . '/oauth2/v2.0/token', $oauthRequest);
        $reply = json_decode($reply['data']);
        return $reply->access_token;
    }

    function sendMail($mailbox, $messageArgs ) {
        if (!$this->Token) {
            throw new Exception('No token defined');
        }

        foreach ($messageArgs['toRecipients'] as $recipient) {
            if ($recipient['name']) {
                $messageArray['toRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
            } else {
                $messageArray['toRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
            }
        }
        foreach ($messageArgs['ccRecipients'] as $recipient) {
            if ($recipient['name']) {
                $messageArray['ccRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
            } else {
                $messageArray['ccRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
            }
        }
        $messageArray['subject'] = $messageArgs['subject'];
        $messageArray['importance'] = ($messageArgs['importance'] ? $messageArgs['importance'] : 'normal');
        if (isset($messageArgs['replyTo'])) $messageArray['replyTo'] = array(array('emailAddress' => array('name' => $messageArgs['replyTo']['name'], 'address' => $messageArgs['replyTo']['address'])));
        $messageArray['body'] = array('contentType' => 'HTML', 'content' => $messageArgs['body']);
        $messageJSON = json_encode($messageArray);
        $response = $this->sendPostRequest($this->baseURL . 'users/' . $mailbox . '/messages', $messageJSON, array('Content-type: application/json'));

        $response = json_decode($response['data']);
        $messageID = $response->id;

        foreach ($messageArgs['images'] as $image) {
            $messageJSON = json_encode(array('@odata.type' => '#microsoft.graph.fileAttachment', 'name' => $image['Name'], 'contentBytes' => base64_encode($image['Content']), 'contentType' => $image['ContentType'], 'isInline' => true, 'contentId' => $image['ContentID']));
            $response = $this->sendPostRequest($this->baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/attachments', $messageJSON, array('Content-type: application/json'));
        }

        foreach ($messageArgs['attachments'] as $attachment) {
            $messageJSON = json_encode(array('@odata.type' => '#microsoft.graph.fileAttachment', 'name' => $attachment['Name'], 'contentBytes' => base64_encode($attachment['Content']), 'contentType' => $attachment['ContentType'], 'isInline' => false));
            $response = $this->sendPostRequest($this->baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/attachments', $messageJSON, array('Content-type: application/json'));
        }
        //Send
        $response = $this->sendPostRequest($this->baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/send', '', array('Content-Length: 0'));
        if ($response['code'] == '202') return true;
        return false;

    }

    function sendPostRequest($URL, $Fields, $Headers = false) {
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($Fields) curl_setopt($ch, CURLOPT_POSTFIELDS, $Fields);
        if ($Headers) {
            $Headers[] = 'Authorization: Bearer ' . $this->Token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return array('code' => $responseCode, 'data' => $response);
    }
}
?>
