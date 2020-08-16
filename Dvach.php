<?php

class Dvach {
    
    public $board;

    function __construct($board) {
        $this->board = $board;
    }

    private function getJson($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $result;
    }

    function getThreadList(array $filter=[]) {
        $threadsArray = [];
        foreach (getJson("https://2ch.hk/".$this->board."/threads.json")["threads"] as $thread) {
            array_push($threadsArray, $thread);
        }
        if (count($filter) != 0) {
            $filteredThreadsArray = [];
            foreach (getThreadList($board) as $thread) {
                foreach ($filter as $word) {
                    if (stripos(mb_strtolower($thread["subject"]), $word) !== false ) $filteredThreadsArray[$thread["num"]] = $thread;
                }
            }
            return array_values($filteredThreadsArray);
        } else return $threadsArray;
    }
    
    function getThread($num) {
        return getJson("https://2ch.hk/".$this->board."/res/".$num.".json");
    }

    function getThreadFiles($num, $type="") {
        $filesArray = [];
        foreach (getThread($this->board, $num)["threads"][0]["posts"] as $post) {
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
