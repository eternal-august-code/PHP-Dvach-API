<?php

include_once "ImageboardParser.php";

class Fourchan extends ImageboardParser {
    public function setBoard($board) {
        $this->board = $board;
        $this->url = "https://a.4cdn.org/".$this->board."/";
    }

    function __construct($board) {
        parent::__construct($board);
    }
        
    public function getThread($num) {

    }

    public function getThreadFiles($num) {

    }
}

$fourch = new Fourchan("b");

dump( $fourch->getCatalog() );
