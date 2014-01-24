<div>Здесь будет платежная система!
<?#print_r($client_data);?>
<?
 $total = $this->cart->format_number($this->cart->total());
//foreach($this->cart->contents() as $items):
//$subtotal = $this->cart->format_number($this->cart->total()); 
//endforeach;

?>
<?=$widget?>
</div>