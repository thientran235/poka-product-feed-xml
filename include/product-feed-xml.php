<?php
$cats_array = '';

$args_cat = array(
    'taxonomy' => 'product_cat',
    'fields' => 'ids',
    'hide_empty' => true,
    'meta_query' => array(
        array(
           'key'       => 'poka_product_feed_xml_check',
           'value'     => 1,
           'compare'   => '='
        )
    )
);

$cats_array = get_terms( $args_cat );

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'offset' => $offset,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $cats_array
        ),
    ),
);

$products = new WP_Query($args);
$products_xml = array();

if ($products->have_posts()) :
    while ($products->have_posts()) : $products->the_post();
    $product = wc_get_product( get_the_ID() );
    
    $product_xml['productname'] = get_the_title();
    $product_xml['brandname'] = $product->get_attribute( 'thuong-hieu' );
    $product_xml['MPN'] = $product->get_sku();
    
    if($product->get_manage_stock())
        $stock = !empty($product->get_stock_quantity() ) ? $product->get_stock_quantity() : -99;
    else 
        $stock = 999999;
    $product_xml['stock'] = $stock;
    
    $product_xml['price'] = $product->get_price();
    
    $product_categories = get_the_terms(get_the_ID(), 'product_cat');
    $product_category = isset($product_categories[0]) ? $product_categories[0]->name : '';
    $product_xml['category_name'] = $product_category;
    
    $product_xml['your_product_ID'] = get_the_ID();
    $product_xml['image_URL'] = wp_get_attachment_url( $product->get_image_id() );
    $product_xml['product_URL'] = get_the_permalink();
    $product_xml['promo-text'] = '';
    
    $products_xml[] = $product_xml;
    
    endwhile;
endif;
wp_reset_query();

//echo "<pre>";
//print_r($products_xml);
//echo "</pre>";
//die();

$productfeeds = new SimpleXMLElement('<productfeed/>');
foreach($products_xml as $product_xml) {
    
    $productfeed = $productfeeds->addChild('product');
    $productfeed->addChild('productname', $product_xml['productname']);
    $productfeed->addChild('brandname', $product_xml['brandname']);
    $productfeed->addChild('MPN', $product_xml['MPN']);
    $productfeed->addChild('stock', $product_xml['stock']);
    $productfeed->addChild('price', $product_xml['price']);
    $productfeed->addChild('category_name', $product_xml['category_name']);
    $productfeed->addChild('your_product_ID', $product_xml['your_product_ID']);
    $productfeed->addChild('image_URL', $product_xml['image_URL']);
    $productfeed->addChild('product_URL', $product_xml['product_URL']);
    $productfeed->addChild('promo-text', $product_xml['promo-text']);
    
}

header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');
print($productfeeds->asXML());