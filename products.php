<?php
// Set the page title/height and include the header file:
define('TITLE', 'Products | ');
include('shared/header.php');
include('products_lib.php');

$product_1 = new product();
$product_1->setName('kits');
$product_1->setTitle('Full Kits');

$product_2 = new product();
$product_2->setName('jerseys');

$product_3 = new product();
$product_3->setName('shorts');

$product_4 = new product();
$product_4->setName('womens');
$product_4->setTitle("Women's Wear");

$product_5 = new product();
$product_5->setName('athletic');
$product_5->setTitle('Athletic Wear');

$product_6 = new product();
$product_6->setName('accessories');

$products = array($product_1, $product_2, $product_3, $product_4, $product_5, $product_6);
?>
    

    <!-- product Logos -->
    <div class='container'>
        <div class='row product-thumbs'>
            <?php
                for($i=0; $i<count($products); $i++) {
                    $products[$i]->printThumb();
                }
            ?>
        </div>

        <?php
            for($i=0; $i<count($products); $i++) {
                $products[$i]->printInfo();
            }
        ?>
    </div>


<?php
include('shared/footer.php');
?>