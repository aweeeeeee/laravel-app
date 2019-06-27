<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function bot(Request $request) {
        $data = $request->all();
        //get the user’s id
        $id            = $data["entry"][0]["messaging"][0]["sender"]["id"];
        $senderMessage = $data["entry"][0]["messaging"][0]['message'];
        if (!empty($senderMessage)) {
            $this->sendTextMessage($id, "Hi buddy");
        }
    }
    
    private function sendTextMessage($recipientId, $messageText) {
        $messageData = [
            "recipient" => [
                "id" => $recipientId,
            ],
            "message"   => [
                "text" => $messageText,
            ],
        ];
        $ch = curl_init('https://graph.facebook.com/v3.3/me/messages?access_token=EAAjRW2AejqsBAPp6V7VCvZB0fXnZBgFUUbPjic9ui0EugVVoMndYWwB91ALYt8F6JKPVViRKFa7ug5fuyEmxSht542kmE4eVjHZBO2F7uuDPVsfQmPjSHWNDwtNY440QK8S5xZCTmtNsq1iEGkl5Iqo9rEZBmnx8Rtip1LTvIbLk8mtpxTrMi');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);
        
    }
}
