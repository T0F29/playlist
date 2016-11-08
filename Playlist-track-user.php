<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Playlist_track_user
{
    private $playlist_id;
    private $track_id;
    private $user_id;

    function getPlaylist_id() {
        return $this->playlist_id;
    }

    function getTrack_id() {
        return $this->track_id;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function setPlaylist_id($playlist_id) {
        $this->playlist_id = $playlist_id;
    }

    function setTrack_id($track_id) {
        $this->track_id = $track_id;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }



}
