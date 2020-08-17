<?php

abstract class ImageboardParser {
    protected $board;
    protected $url;
    
    public function getBoard() {
        return $this->board;
    }
    abstract function setBoard($board);

    function __construct($board) {
        $this->setBoard($board);
    }

    protected function getJson($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $result;
    }
    
    abstract function getThreadList(array $filter=[]);
    abstract function getThread($num);
    abstract function getThreadFiles($num, $type="");
}