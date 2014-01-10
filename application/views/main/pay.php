<div>Здесь будет платежная система!
<?print_r($client_data);?>
<?	
foreach($this->cart->contents() as $items):
$subtotal = $this->cart->format_number($items['subtotal']); 
endforeach;

?>
<script type='text/javascript' src='https://paymaster.ru/widget/Basic/1?LMI_MERCHANT_ID=7e8a97c5-6b34-4ae2-9d14-29c7c7112748&LMI_PAYMENT_AMOUNT=<?=$subtotal?>&LMI_PAYMENT_DESC=Товар+магазина+mynicedecor.com&LMI_CURRENCY=RUB'></script>
</div>