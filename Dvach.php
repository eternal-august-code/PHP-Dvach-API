<?php

include_once "ImageboardParser.php";

class Dvach extends ImageboardParser {
    public function setBoard($board) {
        $this->board = $board;
        $this->url = "https://2ch.hk/".$this->board."/";
    }

    function __construct($board) {
        parent::__construct($board);
    }

    public function getThreadList(array $filter=[]) {
        $threadsArray = [];
        foreach ($this->getJson($this->url."/threads.json")["threads"] as $thread) {
            array_push($threadsArray, $thread);
        }
        if (count($filter) != 0) {
            $filteredThreadsArray = [];
            foreach ($this->getThreadList() as $thread) {
                foreach ($filter as $word) {
                    if (stripos(mb_strtolower($thread["subject"]), $word) !== false ) $filteredThreadsArray[$thread["num"]] = $thread;
                }
            }
            return array_values($filteredThreadsArray);
        } else return $threadsArray;
    }
    
    public function getThread($num) {
        return $this->getJson($this->url."/res/".$num.".json");
    }

    public function getThreadFiles($num, $type="") {
        $filesArray = [];
        foreach ($this->getThread($this->board, $num)["threads"][0]["posts"] as $post) {
            foreach ($post["files"] as $file) {
                if (array_key_exists("md5", $file)) $filesArray[$file["md5"]] = $file;
            }
        }
        if ($type == "") {
            return array_values($filesArray);
        } elseif ($type == "video") {
            $videoFilesArray = [];
            foreach ($filesArray as $file) {
                if (array_key_exists("duration", $file)) array_push($videoFilesArray, $file); 
            }
            return $videoFilesArray;
        } elseif ($type == "img") {
            $imgFilesArray = [];
            foreach ($filesArray as $file) {
                if (array_key_exists("duration", $file)) continue; 
                else array_push($imgFilesArray, $file); 
            }
            return $imgFilesArray;
        }
    }
}
