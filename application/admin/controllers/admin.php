<?php 
/**
 * 后台除了登录不需要继承此类，其他的类建议继承此类
 */
class adminController extends grace{
    public  function __init(){
        parent::__init();
    }

}