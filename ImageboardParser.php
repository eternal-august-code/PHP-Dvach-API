<?php
function dump($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
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
    
    // public function getCatalog(array $filter=[]) {
    //     $threadsArray = [];
    //     foreach ($this->getJson($this->url."/catalog.json")["threads"] as $thread) {
    //         array_push($threadsArray, $thread);
    //     }
    //     if (count($filter) != 0) {
    //         $filteredThreadsArray = [];
    //         foreach ($this->getCatalog() as $thread) {
    //             foreach ($filter as $word) {
    //                 if (stripos(mb_strtolower($thread["subject"]), $word) !== false ) $filteredThreadsArray[$thread["num"]] = $thread;
    //             }
    //         }
    //         return array_values($filteredThreadsArray);
    //     } else return $threadsArray;
    // }

    public function getCatalog() {
        return $this->getJson($this->url."/catalog.json");
    }
    
    abstract function getThread($num);
    abstract function getThreadFiles($num);
}