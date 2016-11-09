<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Track
{
    private $id;
    private $title;
    private $duration;
    private $year;
    private $artist;

    /*function __construct($id, $title, $duration, $year, $artist) {
        $this->id = $id;
        $this->title = $title;
        $this->duration = $duration;
        $this->year = $year;
        $this->artist = $artist;
    }*/



    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getDuration() {
        return $this->duration;
    }

    function getYear() {
        return $this->year;
    }

    function getArtist() {
        return $this->artist;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDuration($duration) {
        $this->duration = $duration;
    }

    function setYear($year) {
        $this->year = $year;
    }

    function setArtist($artist) {
        $this->artist = $artist;
    }

}
