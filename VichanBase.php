<?php
function dump($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
abstract class VichanBase {
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
    
    public function getThread($num) {
        return $this->getJson($this->url."/res/".$num.".json");
    }

    public function getCatalog() {
        return ($this->getJson($this->url."/catalog.json"));
    }

    abstract function getThreadList(array $filter=[]);

    protected function filterThreadList(array $threadList, string $key, array $filter, string $uniqueKey) {
        $filteredThreadsArray = [];
        foreach ($threadList as $thread) {
            foreach ($filter as $word) {
                if (stripos(mb_strtolower($thread[$key]), mb_strtolower($word)) !== false ) $filteredThreadsArray[$thread[$uniqueKey]] = $thread;
            }
        }
        return array_values($filteredThreadsArray);
    }

    abstract function getThreadFiles($num);
}