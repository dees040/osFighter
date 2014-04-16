<?php

class error
{
    /**
     * Generates a big error message and returns it
     * @param $text
     * @return string
     */
    public function errorBig($text){
        return '<br /><br /><div class="notifi" style="border: 3px solid red; color: #bb3838; background: #ffeeee;">
		<img src="files/images/1391740998_stop.png" style="float: left; padding-right: 10px;" alt=""/>
		'.$text.'</div><br /><br />';
    }

    /**
     * Generates a small error message and returns it
     * @param $text
     * @return string
     */
    public function errorSmall($text){
        return '<div class="notifi-small" style="border: 2px solid red; color: red; bakckground: #ffc9c9;">'.$text.'</div>';
    }

    /**
     * Generates a big success message and returns it
     * @param $text
     * @return string
     */
    public function successBig($text){
        return '<br /><br /><div class="notifi" style="border: 3px solid #89dffb; color: Black; background: #d9f6ff;">
		<img src="files/images/1391741332_semi_success.png" style="float: left; padding-right: 10px;" alt=""/>
		'.$text.'</div><br /><br />';
    }

    /**
     * Generates a small success message and returns it
     * @param $text
     * @return string
     */
    public function succesSmall($text){
        return '<div class="notifi-small" style="border: 2px solid #89dffb; color: black;">'.$text.'</div>';
    }
}