<?php
/**
 * @var \App\View\AppView $this
 */

$invoice = (!empty($invoice)) ? $invoice : [];

$this->assign('title', __("Hóa đơn số $invoice->id"));
$this->assign('description', '');
$this->assign('content_title', __("Hóa đơn số $invoice->id"));
?>

    <div class="box box-primary checkout-form">
        <div class="box-header with-border">
            <i class="fa fa-credit-card"></i>
            <h3 class="box-title">Hóa đơn số <?=$invoice->id?></h3>
        </div>
        <div class="box-body">

            <legend>Chi Tiết Hóa Đơn</legend>

            <table class="table table-hover table-striped">
                <tbody><tr>
                    <td>Trạng thái</td>
                    <td><?=($invoice->status == 1) ? "Đã" : "Chưa"?> Thanh toán</td>
                </tr>
                <tr>
                    <td>Mô Tả</td>
                    <td><?=$invoice->description?></td>
                </tr>
                <tr>
                    <td>Số tiền</td>
                    <td><?=currency_format($invoice->buyer_campaign->total_amount)?></td>
                </tr>
                <tr>
                    <td>Phương thức thanh toán</td>
                    <td><?=($invoice->payment_method == 'banktransfer') ? 'Bank Transfer' : null?></td>
                </tr>
                <tr>
                    <td>Ngày thanh toán</td>
                    <td><?=(!empty($invoice->date_payment)) ? \Cake\I18n\Time::parse($invoice->date_payment) : null?></td>
                </tr>
                <tr>
                    <td>Tạo</td>
                    <td><?=\Cake\I18n\Time::parse($invoice->created)?></td>
                </tr>
                </tbody>
            </table>

            <?= $this->Form->create('invoice-checkout',[
                'action' => 'checkout',
                'id' => 'checkout-form'
            ]); ?>

            <legend>Phương thức thanh toán</legend>

            <?=$this->Form->control('payment_method',[
                'label' => false,
                'type' => 'select',
                'options' => [
                    '' => 'Vui lòng chọn',
                    'banktransfer' => 'Bank Transfer'
                ]
            ])?>

            <?php if (!empty($banks['banktransfer'])) : ?>
                <div class="payment-method-details" data-paymentmethod="banktransfer" style="display: none;">
                    <?php $content = $banks['banktransfer'];
                        $search = ['[invoice_id]','[traffic_id]'];
                        $replace = [$invoice->id,$invoice->traffic_ids];
                        echo str_replace($search,$replace,$content);
                    ?>
                </div>
            <?php endif ?>

            <?=$this->Form->control('invoice_id',[
                'type' => 'hidden',
                'value' => $invoice->id
            ])?>

            <div class="text-center">
                <?=$this->Form->button('Hóa đơn thanh toán',[
                    'class' => 'btn btn-success btn-lg'
                ])?>
            </div>
            <?= $this->Form->end() ?>
        </div><!-- /.box-body -->
    </div>

<?php $this->start('scriptBottom'); ?>
    <script>
        var checkout_form = $('#checkout-form');

        /*checkout_form.on('submit', function(e) {
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
        });*/

        checkout_form.on('change', function(e) {
            var payment_method = $(this).find('select[name=payment_method]').val();
            var payment_method_details = $(this).find('.payment-method-details');

            payment_method_details.css('display', 'none');
            $(this).find('.payment-method-details[data-paymentmethod=\'' + payment_method + '\']').css('display', 'block');
        });
    </script>
<?php $this->end();
