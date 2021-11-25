<div id="sidebar">
    <div id="column-left">
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_customer_service_links.inc.php'); ?>
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_account_links.inc.php'); ?>
    </div>
</div>

<div id="content">
    {snippet:notices}

    <section id="box-order-history" class="box">

        <h1 class="title"><?php echo language::translate('title_archived_order', 'Archived Order'); ?></h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover data-table">
                <thead>
                <tr>
                    <th class="text-center"><?php echo language::translate('title_order', 'Order'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_order_status', 'Order Status'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_amount', 'Amount'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_date', 'Date'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_restore', 'Restore'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($orders) foreach ($orders as $order) { ?>
                    <tr>
                        <td class="text-center"><a href="<?php echo htmlspecialchars($order['link']); ?>" class="lightbox-iframe"><?php echo language::translate('title_order', 'Order'); ?> #<?php echo $order['id']; ?></a></td>
                        <td class="text-center"><?php echo $order['order_status']; ?></td>
                        <td class="text-center"><?php echo $order['payment_due']; ?></td>
                        <td class="text-center"><?php echo $order['date_created']; ?></td>
                        <td class="text-center">
                            <a title="Restore" href="#" class="restore_order" data-id="<?php echo $order['id']; ?>">
                                <?php echo functions::draw_fonticon('fa-reply-all'); ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php echo $pagination; ?>
    </section>
</div>

<script>
    var ReStoreLock = true;
    $(document.body).on('click', 'a.restore_order', function(e){
        e.preventDefault();
        var order_id = $(this).data('id');
        if(ReStoreLock) restore_order(order_id);
    });

    function restore_order(order_id){
        ReStoreLock = false;

        var url = '<?php echo document::ilink('ajax/archive_ajax'); ?>';

        var data = {
            action: 'restore',
            order_id: order_id,
            token: '<?php echo form::session_post_token(); ?>'
        };

        if (!$('body > .loader-wrapper').length) {
            var loader = '<div class="loader-wrapper" style="position: fixed; top: 50%; left: 10%; right: 10%; text-align: center; margin-top: -128px; opacity: 0.1; z-index: 999999;">'
                + '  <div class="loader" style="width: 256px; height: 256px;"></div>'
                + '</div>';
            $('body').append(loader);
        }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            },
            success: function(response) {
                var res = JSON.parse(response);
                if(res.status && res.status == 'success'){
                    $('a[data-id="' + order_id + '"]').closest('tr').fadeTo('fast', 0.1);
                    $('a[data-id="' + order_id + '"]').closest('tr').remove();
                }

                $('body > .loader-wrapper').fadeOut('fast', function(){
                    $(this).remove();
                });
                ReStoreLock = true;
            }
        });
    }
</script>
