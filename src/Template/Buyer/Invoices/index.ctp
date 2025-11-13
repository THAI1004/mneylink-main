<?php
/**
 * @var \App\View\AppView $this
 */

$this->assign('title', __("Quản lý hoá đơn"));
$this->assign('description', '');
$this->assign('content_title', __("Quản lý hoá đơn"));
?>

    <div class="box box-primary">
        <div class="box-body no-padding">

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <tbody>
                    <tr>
                        <th><a class="desc" href="/buyer/invoices?sort=id&amp;direction=asc">ID</a></th>
                        <th><a href="/buyer/invoices?sort=status&amp;direction=asc">Trạng thái</a></th>
                        <th>Mô Tả</th>
                        <th><a href="/buyer/invoices?sort=amount&amp;direction=asc">Số tiền</a></th>
                        <th><a href="/buyer/invoices?sort=payment_method&amp;direction=asc">Phương thức thanh toán</a></th>
                        <th><a href="/buyer/invoices?sort=paid_date&amp;direction=asc">Ngày thanh toán</a></th>
                        <th><a href="/buyer/invoices?sort=created&amp;direction=asc">Tạo</a></th>
                        <th>Hành động</th>
                    </tr>

                    <?php if (!empty($invoices)) : ?>
                        <?php foreach ($invoices as $invoice) : ?>
                            <tr>
                                <td><a href="<?=$this->Url->build(['controller' => 'Invoices','action' => 'view','id' => $invoice->id])?>"><?=$invoice->id?></a></td>
                                <td><?=(!$invoice->status) ? "Chưa" : "Đã"?> thanh toán</td>
                                <td><?=$invoice->description?></td>
                                <td><?=currency_format($invoice->buyer_campaign->total_amount)?></td>
                                <td><?=$invoice->payment_method?></td>
                                <td><?=($invoice->date_payment) ? \Cake\I18n\Time::parse($invoice->date_payment) : null?></td>
                                <td><?=\Cake\I18n\Time::parse($invoice->created)?></td>
                                <td><a href="<?=$this->Url->build(['controller' => 'Invoices','action' => 'view','id' => $invoice->id])?>" class="btn btn-primary btn-xs">Lượt xem</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody></table>
            </div>

        </div><!-- /.box-body -->
    </div>

    <ul class="pagination">
        <?php
        $this->Paginator->setTemplates([
            'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
        ]);

        if ($this->Paginator->hasPrev()) {
            echo $this->Paginator->prev('«');
        }

        echo $this->Paginator->numbers([
            'modulus' => 4,
            'first' => 2,
            'last' => 2,
        ]);

        if ($this->Paginator->hasNext()) {
            echo $this->Paginator->next('»');
        }
        ?>
    </ul>

<?php $this->start('scriptBottom'); ?>
    <script>
        var checkout_form = $('#checkout-form');

        checkout_form.on('submit', function(e) {
            e.preventDefault();

            var checkoutForm = $(this);
            var submitButton = checkoutForm.find('button');

            $.ajax({
                dataType: 'json', // The type of data that you're expecting back from the server.
                type: 'POST', // he HTTP method to use for the request
                url: checkoutForm.attr('action'),
                data: checkoutForm.serialize(), // Data to be sent to the server.
                beforeSend: function(xhr) {
                    submitButton.attr('disabled', 'disabled');
                    $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>').
                    insertAfter($('.checkout-form .box-body'));
                },
                success: function(result, status, xhr) {
                    //console.log( result );
                    if (result.status === 'success') {

                        if (result.type === 'form') {
                            //console.log( result.message );
                            $(result.form).insertAfter(checkoutForm);
                            $('#checkout-redirect-form').submit();
                        }

                        if (result.type === 'url') {
                            //console.log( result.message );
                            window.location.href = result.url;
                        }

                        if (result.type === 'offline') {
                            //console.log( result.message );
                            window.location.href = result.url;
                        }

                    } else {
                        alert(result.message);
                        submitButton.removeAttr('disabled');
                        $('.checkout-form').find('.overlay').remove();
                        checkoutForm[0].reset();
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occured: ' + xhr.status + ' ' + xhr.statusText);
                },
                complete: function(xhr, status) {
                },
            });
        });

        checkout_form.on('change', function(e) {
            var payment_method = $(this).find('select[name=payment_method]').val();
            var payment_method_details = $(this).find('.payment-method-details');

            payment_method_details.css('display', 'none');
            $(this).find('.payment-method-details[data-paymentmethod=\'' + payment_method + '\']').css('display', 'block');
        });
    </script>
<?php $this->end();
