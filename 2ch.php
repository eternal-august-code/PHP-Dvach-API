<?php

function getJson($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $result;
}

function getThreadList($board) {
    $threadsArray = [];
    foreach (getJson("https://2ch.hk/".$board."/threads.json")["threads"] as $thread) {
        array_push($threadsArray, $thread);
    }
    return $threadsArray;
}

function getThreadListByDictionary($board, $dictionary) {
    $sortedThreadsArray = [];
    foreach (getThreadList($board) as $thread) {
        foreach ($dictionary as $word) {
            if (stripos(mb_strtolower($thread["subject"]), $word) !== false ) $sortedThreadsArray[$thread["num"]] = $thread;
        }
    }
    return array_values($sortedThreadsArray);
}

function getThread($board, $num) {
    return getJson("https://2ch.hk/".$board."/res/".$num.".json");
}

function getThreadFiles($board, $num, $type="") {
    $filesArray = [];
    foreach (getThread($board, $num)["threads"][0]["posts"] as $post) {
        foreach ($post["files"] as $file) {
            $filesArray[$file["md5"]] = $file;
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



