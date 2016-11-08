<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Playlist
{
    private $id;
    private $name;
    private $description;


 function getId() {
     return $this->id;
 }

 function getName() {
     return $this->name;
 }

 function getDescription() {
     return $this->description;
 }

 function setId($id) {
     $this->id = $id;
 }

 function setName($name) {
     $this->name = $name;
 }

 function setDescription($description) {
     $this->description = $description;
 }



}
