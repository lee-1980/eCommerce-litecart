<?php
header('X-Robots-Tag: noindex');
document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
document::$snippets['title'][] = language::translate('title_order_success', 'Order Success');

breadcrumbs::add(language::translate('title_checkout', 'Checkout'), document::ilink('combine_order'));

ob_start();
?>

<h2>
    Thank you. New order was created!. Email notfication was sent to you!
</h2>

<?php

echo ob_get_clean();
