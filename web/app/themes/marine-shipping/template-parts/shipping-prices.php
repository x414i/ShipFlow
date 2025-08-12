<?php
/*
Template Name: قائمة الأسعار
*/

get_header();

$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
]);
?>

<style>
.prices-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border: 1px solid #ddd;
    font-family: 'Arial', sans-serif;
}

.prices-container h2 {
    text-align: center;
    margin-bottom: 30px;
}

.price-table {
    width: 100%;
    border-collapse: collapse;
}

.price-table th, .price-table td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

.price-table th {
    background-color: #f7f7f7;
}
</style>

<div class="prices-container">
    <h2>💲 قائمة أسعار الشحن</h2>

    <table class="price-table">
        <thead>
            <tr>
                <th>الدولة</th>
                <th>بري (لكل كجم)</th>
                <th>بحري</th>
                <th>جوي</th>
                <th>سريع</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($countries as $country): 
                $land  = get_post_meta($country->ID, 'price_land', true);
                $sea   = get_post_meta($country->ID, 'price_sea', true);
                $air   = get_post_meta($country->ID, 'price_air', true);
                $fast  = get_post_meta($country->ID, 'price_fast', true);
            ?>
            <tr>
                <td><?php echo esc_html($country->post_title); ?></td>
                <td><?php echo $land !== '' ? number_format($land, 2) . ' $' : '—'; ?></td>
                <td><?php echo $sea !== '' ? number_format($sea, 2) . ' $' : '—'; ?></td>
                <td><?php echo $air !== '' ? number_format($air, 2) . ' $' : '—'; ?></td>
                <td><?php echo $fast !== '' ? number_format($fast, 2) . ' $' : '—'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php get_footer(); ?>
