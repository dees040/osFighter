<?php

class error
{
    public $error = false;

    /**
     * Generates a big error message and returns it
     * @param $text
     * @return string
     */
    public function errorBig($text){
        $this->error = true;
        return '<br /><br /><div class="notifi" style="border: 3px solid red; color: #bb3838; background: #ffeeee;">
		<img src="files/images/stop.png" style="float: left; padding-right: 10px;" alt=""/>
		'.$text.'</div><br /><br />';
    }

    /**
     * Generates a small error message and returns it
     * @param $text
     * @return string
     */
    public function errorSmall($text){
        $this->error = true;
        return '<div class="notifi-small" style="border: 2px solid red; color: red; bakckground: #ffc9c9;">'.$text.'</div><br>';
    }

    /**
     * Generates a big success message and returns it
     * @param $text
     * @return string
     */
    public function successBig($text){
        $this->error = true;
        return '<br /><br /><div class="notifi" style="border: 3px solid #89dffb; color: Black; background: #d9f6ff;">
		<img src="files/images/success.png" style="float: left; padding-right: 10px;" alt=""/>
		'.$text.'</div><br /><br />';
    }

    /**
     * Generates a small success message and returns it
     * @param $text
     * @return string
     */
    public function succesSmall($text){
        $this->error = true;
        return '<div class="notifi-small" style="border: 2px solid #89dffb; color: black;">'.$text.'</div><br>';
    }
}