<?php

// Set the page title/height and include the header file:
define('TITLE', 'Brands | ');
include('shared/header.php');
include('brands_lib.php');

$brand_1 = new brand();
$brand_1->setName('givova');
$brand_1->setInfo('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis. Donec vel quam eu felis efficitur egestas rutrum ut velit. Donec accumsan malesuada massa, vel sodales sem suscipit vel. Nulla nec aliquet lacus. Nullam elementum condimentum luctus.');

$brand_2 = new brand();
$brand_2->setName('joma');
$brand_2->setInfo('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis. Donec vel quam eu felis efficitur egestas rutrum ut velit. Donec accumsan malesuada massa, vel sodales sem suscipit vel. Nulla nec aliquet lacus. Nullam elementum condimentum luctus.');

$brand_3 = new brand();
$brand_3->setName('lotto');
$brand_3->setInfo('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis. Donec vel quam eu felis efficitur egestas rutrum ut velit. Donec accumsan malesuada massa, vel sodales sem suscipit vel. Nulla nec aliquet lacus. Nullam elementum condimentum luctus.');

$brand_4 = new brand();
$brand_4->setName('macron');
$brand_4->setInfo('Enthusiasm, creativity, results, enjoyment and a very strong commitment: these are the rules that Macron shares with athletes. Macron produces for sports and shares the passion, joy and commitment of the athletes, aware of the fundamental importance of their apparel and accessories.');

$brand_5 = new brand();
$brand_5->setName('umbro');
$brand_5->setInfo('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis. Donec vel quam eu felis efficitur egestas rutrum ut velit. Donec accumsan malesuada massa, vel sodales sem suscipit vel. Nulla nec aliquet lacus. Nullam elementum condimentum luctus.');

$brand_6 = new brand();
$brand_6->setName('xara');
$brand_6->setInfo('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor pretium eros, vitae laoreet massa. Pellentesque posuere sed nisi ac elementum. Etiam nisi libero, placerat quis turpis eu, eleifend elementum dui. Quisque sit amet convallis erat. Phasellus a consectetur ante. Duis at enim quam. Vivamus dignissim posuere est, sed egestas turpis blandit quis. Donec vel quam eu felis efficitur egestas rutrum ut velit. Donec accumsan malesuada massa, vel sodales sem suscipit vel. Nulla nec aliquet lacus. Nullam elementum condimentum luctus.');

$brands = array($brand_1, $brand_2, $brand_3, $brand_4, $brand_5, $brand_6);
?>
    

    <!-- Brand Logos -->
    <div class='container'>
        <div class='row brand-thumbs'>
            <?php
                for($i=0; $i<count($brands); $i++) {
                    $brands[$i]->printThumb();
                }
            ?>
        </div>

        <?php
            for($i=0; $i<count($brands); $i++) {
                $brands[$i]->printInfo();
            }
        ?>
    </div>


<?php
include('shared/footer.php');
?>