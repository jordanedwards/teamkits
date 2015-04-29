<?php
// Set the page title/height and include the header file:
define('TITLE', '');
include('shared/header.php');
?>

    <!-- Header -->
    <header>
        <!-- Amazing Slider -->
         <div id="amazingslider-1" style="display:block;position:relative;margin:0px auto 0px;">
            <ul class="amazingslider-slides" style="display:none;">
                <li><img src="img/slider/andromeda.png" alt="Andromeda" /></li>
                <li><img src="img/slider/jupiter.png" alt="Jupiter" /></li>
                <li><img src="img/slider/skoll.png" alt="Skoll" /></li>
            </ul>
            <ul class="amazingslider-thumbnails" style="display:none;">
                <li><img src="img/slider/andromeda-tn.png" /></li>
                <li><img src="img/slider/jupiter-tn.png" /></li>
                <li><img src="img/slider/skoll-tn.png" /></li>
            </ul>
        </div>
    </header>

    <div class='border-outer gradient-outer'><div class='border-inner gradient-inner'></div></div>

    <div class='container'>
        <div class='row'>
            <div class='col-sm-12 text-center'>
                <h2>Team Kits Info</h2>
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 text-center'>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis. Donec vel quam eu felis efficitur egestas rutrum ut velit. Donec accumsan malesuada massa, vel sodales sem suscipit vel. Nulla nec aliquet lacus. Nullam elementum condimentum luctus.</p>
                <p>Nulla pellentesque tempor auctor. Mauris ut turpis a eros porttitor scelerisque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac erat lorem. Aenean dictum rutrum vulputate. Donec sed finibus diam, et ultricies magna. Sed lectus turpis, semper a porttitor at, cursus non orci. Morbi mattis nec nulla non imperdiet. Ut pulvinar bibendum ante. Vestibulum at tempor ipsum, a ultrices quam. Nullam non mauris in nulla semper vestibulum. Aenean in vulputate justo, vitae dapibus felis. Aenean quis arcu elit.</p>
            </div>
        </div>
    </div>
    
    <div class='border-outer gradient-outer'><div class='border-inner gradient-inner'></div></div>

    <section id='kit-builder'>
        <div class='container'>
            <div class='row'>
                <div class='col-sm-12 text-center'>
                    <h2>Kit Builder</h2>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-4'>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis.</p>
                </div>
                <div class='col-sm-8'>
                    <div class='img-rounded' id='kit-builder-window'>
                    </div>
                </div>
            </div>
        </div>
    <section>

<?php
include('shared/footer.php');
?>