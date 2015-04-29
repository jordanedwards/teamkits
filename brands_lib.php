<?php


    class brand {

        public $name, $info;

        public function setName($val) {
            $this->name = $val;
        }

        public function setInfo($val) {
            $this->info = $val;
        }

        public function printThumb() {
            echo "
                <div class='col-xs-4 col-sm-2 brands' id='".$this->name."'>
                    <img src='img/brands/thumb-".$this->name.".png' alt='".ucfirst($this->name)." Logo'>
                </div>
            ";
        }

        public function printInfo() {
            echo"
                <div class='row brand-info' id='".$this->name."-info'>
                    <div class='col-sm-7'>
                        <img src='img/brands/".$this->name.".png' alt='".ucfirst($this->name)." Logo' class='img-responsive center-block brand-cropped'>
                        <p>".$this->info."</p>
                    </div>
                    
                    <div class='col-sm-5'>
                        <img src='img/jerseys/jersey-macron.jpg' alt='".ucfirst($this->name)." Jersey' class='img-responsive center-block'>".
                        //<img src='img/jerseys/jersey-".$this->name.".jpg' alt='".ucfirst($this->name)." Jersey' class='img-responsive center-block'>
                    "</div>
                </div>
            ";
        }
    }


?>