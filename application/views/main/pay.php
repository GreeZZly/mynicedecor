<div>Здесь будет платежная система!
<?#print_r($client_data);?>
<?
 $total = $this->cart->format_number($this->cart->total());
//foreach($this->cart->contents() as $items):
//$subtotal = $this->cart->format_number($this->cart->total()); 
//endforeach;

?>
<script type='text/javascript' src='https://paymaster.ru/widget/Basic/1?LMI_MERCHANT_ID=7e8a97c5-6b34-4ae2-9d14-29c7c7112748&LMI_PAYMENT_AMOUNT=<?=$total?>&LMI_PAYMENT_DESC=Товар+магазина+mynicedecor.com&LMI_CURRENCY=RUB'></script>
</div>