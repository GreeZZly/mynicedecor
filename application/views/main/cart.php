<div id="news_content">
<?php echo form_open('/main/update_cart'); ?>

<table cellpadding="6" cellspacing="1" border="0" id="cart">

<tr>
  <th>Количество</th>
  <th>Описание товара</th>
  <th style="text-align:right">Цена за 1шт.</th>
  <th style="text-align:right">Общая сумма</th>
</tr>

<?php $i = 1; ?>

<?php foreach($this->cart->contents() as $items): ?>

	<?php echo form_hidden('rowid_'.$i, $items['rowid']); ?>
	<?php echo form_hidden('update_id', $i);?>

	<tr>
	  <td><?php echo form_input(array('name' => 'qty_'.$i, 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?></td>
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
	  <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
	  <td style="text-align:right"><?php echo $this->cart->format_number($items['subtotal']); ?> руб.</td>
	</tr>

<?php $i++; ?>

<?php endforeach; ?>

<tr>
  <td colspan="2"> </td>
  <td class="right"><strong>Всего</strong></td>
  <td class="right"><?php echo $this->cart->format_number($this->cart->total()); ?> руб.</td>
</tr>

</table>

<p><?php echo form_submit('update_cart', 'Обновить карзину'); ?></p>
<p><a href="/index.php/main/destroy_cart">Очистить карзину</p>

</div>
</div>