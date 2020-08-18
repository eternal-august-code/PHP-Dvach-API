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
    
    public function getCatalog() {
        return ($this->getJson($this->url."/catalog.json"));
    }

    protected function filterThreadList(array $threadList, string $key, array $filter) {
        $filteredThreadsArray = [];
        foreach ($threadList as $thread) {
            foreach ($filter as $word) {
                if (stripos(mb_strtolower($thread[$key]), $word) !== false ) $filteredThreadsArray[$thread["num"]] = $thread;
            }
        }
        return array_values($filteredThreadsArray);
    }



    abstract function getThreadList();
    abstract function getThread($num);
    abstract function getThreadFiles($num);
}