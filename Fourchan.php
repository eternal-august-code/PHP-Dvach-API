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
        
    public function getThreadList(array $filter=[]) {
        $catalog = $this->getCatalog();
        $threadList = [];
        for ($i=0; $i < count($catalog); $i++) { 
            foreach ($catalog[$i]["threads"] as $thread) {
                array_push($threadList, $thread);
            }
        }
        if (count($filter) != 0) $this->filterThreadList($threadList, "subject", $filter);
        return $threadList;
    }

    public function getThread($num) {

    }

    public function getThreadFiles($num) {

    }
}

$fourch = new Fourchan("b");

dump( $fourch->getCatalog() );
