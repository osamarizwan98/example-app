<h4>Orders</h4>

<table class="table table-bordered my-5">
	<thead>
		<tr>
			<th scope="col">Order #</th>
			<th scope="col">Email Address</th>
			<th scope="col">Customer Name</th>
			<th scope="col">Total Sale</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$orders = shopify_call($token, $shop_url, "/admin/api/2020-10/orders.json", array("status" => 'any'), 'GET');
			$orders = json_decode($orders['response'], JSON_PRETTY_PRINT);


			foreach ($orders as $order) {
				?>
					<tr>
					<?php
					foreach ($order as $key => $value) {
						?>
							<th><a href="#" data-toggle="modal" data-target="#printOrder<?php echo $value['order_number']; ?>"><?php echo $value['order_number']; ?></a></th>
							<td><?php echo $value['contact_email']; ?></td>
							<td><?php echo $value['billing_address']['name']; ?></td>
							<td><?php echo $value['total_price'] . $value['currency']; ?></td>

                            <div class="modal fade" id="printOrder<?php echo $value['order_number']; ?>" tabindex="-1" aria-labelledby="printOrderLabel" aria-hidden="true">
								<div class="modal-dialog modal-lg" style="max-width: 80% !important;">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="printOrderLabel">Print Order #<?php echo $value['order_number']; ?></h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="card border-0" id="printThisInvoice">
												<div class="card-body">
													<div class="row p-2">
														<div class="col-md-6"><h1>LOGO</h1></div>
														<div class="col-md-6 text-right"><p class="font-weight-bold mb-1">Invoice #00001</p></div>
													</div>
													<hr class="my-3">
													<div class="row p-2">
														<div class="col-md-6">
															<p class="font-weight-bold mb-4">Client Information</p>
								                            <p class="mb-1"><?php echo $value['billing_address']['name']; ?></p>
								                            <p class="mb-1"><?php echo $value['billing_address']['address1']; ?></p>
								                            <p class="mb-1"><?php echo $value['billing_address']['country']; ?></p></div>
														<div class="col-md-6 text-right">
															<p class="font-weight-bold mb-4">Payment Details</p>
								                            <p class="mb-1"><span class="text-muted">Payment Type: </span> <?php echo $value['payment_gateway_names'][0]; ?></p>
								                            <p class="mb-1"><span class="text-muted">Name: </span> <?php echo $value['billing_address']['name']; ?></p>
														</div>
													</div>
													<div class="row p-2">
								                        <div class="col-md-12">
								                        	<div class="row">
								                        		<div class="col-3 border">Item</div>
								                        		<div class="col-3 border">Description</div>
								                        		<div class="col-2 border">Quantity</div>
								                        		<div class="col-2 border">Unit Cost (<?php echo $value['currency']; ?>)</div>
								                        		<div class="col-2 border">Total (<?php echo $value['currency']; ?>)</div>
								                        	</div>
								                        	<?php
								                        		$totalPrice = 0;
									                        	foreach ($value['line_items'] as $index => $item) {
									                        		$totalPrice += $item['price'];
									                        		?>
									                        			<div class="row">
											                        		<div class="col-3 border"><?php echo $item['title']; ?></div>
											                        		<div class="col-3 border"><?php echo $item['name']; ?></div>
											                        		<div class="col-2 border"><?php echo $item['quantity']; ?></div>
											                        		<div class="col-2 border"><?php echo $item['price']; ?></div>
											                        		<div class="col-2 border"><?php echo ($item['price'] * $item['quantity']); ?></div>
											                        	</div>
									                        		<?php
									                        	}

									                        ?>
								                        </div>
								                    </div>
								                    <div class="row p-2">
														<div class="col-md-6">
															<p class="font-weight-bold mb-4">Other information</p>
								                            <p class="mb-1">Note: <?php echo $value['note']; ?></p>
								                        </div>
														<div class="col-md-6 text-right">
                                                            <p class="font-weight-bold mb-1">SUB TOTAL:</p>
								                            <p class="mb-4"><?php echo $value['current_subtotal_price']; ?> <small><?php echo $value['currency']; ?></small></p>
                                                            <p class="font-weight-bold mb-1">TAX:</p>
								                            <p class="mb-4"><?php echo $value['current_total_tax']; ?> <small><?php echo $value['currency']; ?></small></p>
															<p class="font-weight-bold mb-1">TOTAL:</p>
								                            <p class="mb-4"><?php echo $value['current_total_price']; ?> <small><?php echo $value['currency']; ?></small></p>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary" id="printBtn">Print</button>
										</div>
									</div>
								</div>
							</div>
						<?php
					}
					?>
					</tr>
				<?php
			}
		?>
	</tbody>
</table>