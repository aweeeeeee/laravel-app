<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function bot(Request $request) {
       
		if ($request->get('hub_mode') == 'subscribe' and $request->get('hub_verify_token') === 'aweaweawe') {
			return response($request->get('hub_challenge'));
		}
		return response('Error, verify token doesn\'t match', 400);
    }
    
    public function botpost (Request $request)
    {
	       $content = json_decode($request->getContent() , true);
		//check if the content of the request contain messaging property, if not exist set it as null
		$postArray = isset($content['entry'][0]['messaging']) ? $content['entry'][0]['messaging'] : null;
		$response = [];
		$has_message = false;
		$is_echo = true;
	
		if (!is_null($postArray)) {
			$sender = $postArray[0]['sender']['id'];
			$has_message = isset($postArray[0]['message']['text']);
			//if the message contain is_echo, it means it doesnt contain user message
			$is_echo = isset($postArray[0]['message']['is_echo']);
		}
		if ($has_message && !$is_echo) {
			//for now, we will just reply back the same thing as user send
			$reply = $postArray[0]['message']['text'];
			$response = $this->sendToFbMessenger($sender, $reply);
		}
		return response($response, 200);
    }
    protected function sendToFbMessenger($sender, $message)
	{
		//message		
		$data = ['json' => 
					[
						'recipient' => ['id' => $sender],
						'message' => ['text' => $message],
					]
				];
		$client = new \GuzzleHttp\Client;
		$res = $client->request('POST', 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAjRW2AejqsBAI6GVrHoiExh8YiaFbta3tFH2LEZBEZB1CEZBRqGN2AA7AuKZCXX8SFxFjCDJEzgCnWGEAcZBODxSG5vkFYRDEZAOxpa2xaEYsKi8hizZBhYDgED2ptHezypeaewT3vbVGqxdEczbCTfPcVoYAYfjPXrV18xgPh93dnNKZCZCZAduZA',  $data);
		return $res->getBody();
	}
}
