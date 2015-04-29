<?php


    class product {

        public $name, $title, $info;

        public function setName($val) {
            $this->name = $val;
            // Set the default value of title to the name
            $this->title = $val;
        }

        // Optional function to set title to something different than name
        public function setTitle($val) {
            $this->title = $val;
        }

        public function printThumb() {
            echo "
                <div class='col-xs-4 col-sm-2 products' id='".$this->name."'>
                    <img src='img/products/".$this->name.".png' alt='".ucfirst($this->title)."'>
                </div>
            ";
        }

        public function printInfo() {
            echo"
                <div class='row product-info' id='".$this->name."-info'>

                    <div class='col-xs-12'><h2 class='text-center'>".ucfirst($this->title)."</h2></div>

                    <div class='col-sm-3 product-item'>
                        <img src='img/temp.png' alt='temp' class='img-responsive img-rounded center-block'>
                        <h3>Lorem Ipsum</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa.</p>
                    </div>

                    <div class='col-sm-3 product-item'>
                        <img src='img/temp.png' alt='temp' class='img-responsive img-rounded center-block'>
                        <h3>Lorem Ipsum</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa.</p>
                    </div>

                    <div class='col-sm-3 product-item'>
                        <img src='img/temp.png' alt='temp' class='img-responsive img-rounded center-block'>
                        <h3>Lorem Ipsum</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa.</p>
                    </div>

                    <div class='col-sm-3 product-item'>
                        <img src='img/temp.png' alt='temp' class='img-responsive img-rounded center-block'>
                        <h3>Lorem Ipsum</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa.</p>
                    </div>

                </div>
            ";
        }
    }


?>