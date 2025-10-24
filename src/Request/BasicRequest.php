<?php
namespace ThankSong\Lecang\Request;
use ThankSong\Lecang\Response\BasicResponse;

class BasicRequest extends Client {
    public function send(): BasicResponse{
        $this->validate();
        return BasicResponse::createFromArray($this->doRequest());
    }

    protected function validate(){
        //
    }
}