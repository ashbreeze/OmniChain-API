<?php
use App\Http\Controllers\ApiController;

//TODO: Unfinished
class ApiKeyController extends ApiController {



}
/*
public function generateAddress() {
    $user = \Request::user();

    if (strtotime($user->next_address_generate_time) > time()) {
        return $this->setStatusCode(403)->respond(['errors' => ['too-fast']]);
    }

    $generateAddress = sendRpcCommand($this->client, 'getnewaddress', [$user->id]);

    if ($generateAddress === false || $generateAddress->result === false) {
        return $this->setStatusCode(500)->respond(['errors' => 'unknown-error']);
    }

    return $this->setStatusCode(201)->respond(['address' => ]);
}

public function importAddress() {

}

public function signMessage() {

}
*/