<div id="cart_view">
<?php echo form_open('/main/update_cart'); ?>

<table cellpadding="6" cellspacing="1" border="0" id="cart">

<tr>
  <th>Количество</th>
  <th>Описание товара</th>
  <th>Цена за 1шт.</th>
  <th>Общая сумма</th>
</tr>

<?php $i = 1; $count = 0;?>

<?php foreach($this->cart->contents() as $items): ?>

	<?php echo form_hidden('rowid_'.$i, $items['rowid']); ?>
	<?php echo form_hidden('update_id', $i);?>

	<tr>
	  <td><?php echo form_input(array('name' => 'qty_'.$i, 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); $count = $count + $items['qty'];?></td>
	  <td>
		<?php echo $items['name']; ?>

			<?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>

				<p>
					<?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

						<strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />

					<?php endforeach; ?>
				</p>

			<?php endif; ?>

	  </td>
	  <td><?php echo $this->cart->format_number($items['price']); ?></td>
	  <td><?php echo $this->cart->format_number($items['subtotal']); ?> руб.</td>
	</tr>

<?php $i++; ?>

<?php endforeach; ?>

<tr>
  <td class="last_tr" colspan="2"> </td>
  <td class="last_tr"><strong>Всего</strong></td>
  <td class="last_tr"><?php echo $this->cart->format_number($this->cart->total()); ?> руб.</td>
</tr>

</table>

<div class="update_cart_submit"><?php echo form_submit('update_cart', 'Обновить карзину'); ?></div>
<div class="destroy_cart_submit"><a href="/index.php/main/destroy_cart">Очистить карзину</a></div>
<? if ($this->cart->format_number($this->cart->total()) != 0) {
		if($log_on == 0) {
			echo "<a href='/auth/order_login'><div id='order_submit'>Оформить заказ</div></a>";
		}
		else {
			echo "<a href='/main/order'><div id='order_submit'>Оформить заказ</div></a>";
		}
	}?>

</div>


</div>