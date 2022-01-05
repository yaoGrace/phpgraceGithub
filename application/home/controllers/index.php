<?php
class indexController extends grace{
    public function index(){ 
    } 
    
    public function create(){  
         crateModelGroup($this->gets[0]);
    }
     
}