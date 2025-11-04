<?php ?>
<div class="max-w-5xl mx-auto px-4 py-10">
	<h1 class="text-2xl font-semibold mb-6">My Orders</h1>

	<?php $hasStandard = !empty($orders); $hasBookings = !empty($bookings); ?>

	<?php if (!$hasStandard && !$hasBookings): ?>
		<div class="bg-white p-6 rounded-lg shadow text-gray-600">No orders found.</div>
	<?php else: ?>
		<?php if ($hasStandard): ?>
			<h2 class="text-lg font-semibold mb-3 mt-6">Store Orders</h2>
			<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						<?php foreach ($orders as $o): ?>
							<tr>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?php echo htmlspecialchars($o['order_number'] ?? (string)($o['id'] ?? '')); ?></td>
								<td class="px-6 py-4 whitespace-nowrap text-sm">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php 
										$status = $o['status'] ?? 'pending';
										echo $status === 'completed' ? 'bg-green-100 text-green-800' : ($status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800');
									?>">
										<?php echo ucfirst($status); ?>
									</span>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱<?php echo number_format((float)($o['total_amount'] ?? 0), 2); ?></td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo !empty($o['created_at']) ? date('M d, Y', strtotime($o['created_at'])) : ''; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ($hasBookings): ?>
			<h2 class="text-lg font-semibold mb-3">Bookings (Rentals & Packages)</h2>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<?php foreach ($bookings as $b): ?>
					<?php
						// Calculate penalty for rentals
						$penalty = 0;
						$daysLate = 0;
						$isOverdue = false;
						
						if (($b['order_type'] ?? '') === 'rental' && !empty($b['rental_end'])) {
							$rentalEnd = new DateTime($b['rental_end']);
							$today = new DateTime();
							
							// Only calculate penalty if past rental end date and not returned
							if ($today > $rentalEnd && empty($b['is_returned'])) {
								$daysLate = $today->diff($rentalEnd)->days;
								$penalty = $daysLate * 250; // ₱250 per day
								$isOverdue = true;
							}
						}
						
						// Check if penalty is already stored in database
						$storedPenalty = (float)($b['penalty_amount'] ?? 0);
						$isPenaltyPaid = !empty($b['is_penalty_paid']);
						
						// Use stored penalty if exists, otherwise use calculated
						$finalPenalty = $storedPenalty > 0 ? $storedPenalty : $penalty;
					?>
					
					<div class="bg-white rounded-lg shadow overflow-hidden <?php echo $isOverdue && !$isPenaltyPaid ? 'border-2 border-red-300' : ''; ?>">
						<div class="flex">
							<div class="w-32 h-32 bg-gray-100 flex items-center justify-center overflow-hidden">
								<?php if (!empty($b['item_image'])): ?>
									<img src="uploads/<?php echo htmlspecialchars($b['item_image']); ?>" alt="<?php echo htmlspecialchars($b['item_name'] ?? ''); ?>" class="w-full h-full object-cover">
								<?php else: ?>
									<div class="text-4xl text-gold-400">👗</div>
								<?php endif; ?>
							</div>
							<div class="flex-1 p-4">
								<div class="flex items-center justify-between mb-2">
									<div class="text-sm text-gray-500">#<?php echo (int)$b['order_id']; ?></div>
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php 
										if ($isOverdue && !$isPenaltyPaid) {
											echo 'bg-red-100 text-red-800';
										} else {
											$status = $b['payment_status'] ?? 'pending';
											echo $status === 'paid' ? 'bg-green-100 text-green-800' : ($status === 'verified' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800');
										}
									?>"><?php echo $isOverdue && !$isPenaltyPaid ? 'Overdue' : ucfirst($b['payment_status'] ?? 'pending'); ?></span>
								</div>
								<div class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($b['item_name'] ?? ''); ?></div>
								<div class="text-sm text-gray-600 mb-2">
									<?php if (($b['order_type'] ?? '') === 'rental'): ?>
										Rental: <?php echo date('M d, Y', strtotime($b['rental_start'])); ?> - <?php echo date('M d, Y', strtotime($b['rental_end'])); ?>
										<?php if (!empty($b['size'])): ?>
											<br>Size: <?php echo htmlspecialchars($b['size']); ?>
										<?php endif; ?>
									<?php else: ?>
										Event: <?php echo date('M d, Y', strtotime($b['event_date'])); ?>
										<?php if (!empty($b['quantity'])): ?>
											<br>Guests: <?php echo (int)$b['quantity']; ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								
								<!-- Penalty Warning -->
								<?php if ($isOverdue && !$isPenaltyPaid && $finalPenalty > 0): ?>
									<div class="bg-red-50 border border-red-200 rounded-md p-3 mb-2">
										<div class="flex items-start">
											<svg class="w-5 h-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
											</svg>
											<div class="flex-1">
												<p class="text-sm font-semibold text-red-800">Late Return Penalty</p>
												<p class="text-xs text-red-700 mt-1">
													₱250 per day × <?php echo $daysLate; ?> day<?php echo $daysLate > 1 ? 's' : ''; ?> = <span class="font-bold">₱<?php echo number_format($finalPenalty, 2); ?></span>
												</p>
												<p class="text-xs text-red-600 mt-1">
													Please pay the penalty on-site to avoid additional charges.
												</p>
											</div>
										</div>
									</div>
								<?php endif; ?>
								
								<div class="flex items-center justify-between">
									<div>
										<div class="text-gold-400 font-bold">₱<?php echo number_format((float)($b['total_amount'] ?? 0), 2); ?></div>
										<div class="text-xs text-gray-500 mt-1"><?php echo !empty($b['created_at']) ? date('M d, Y', strtotime($b['created_at'])) : ''; ?></div>
									</div>
									
									<?php if ($isOverdue && !$isPenaltyPaid && $finalPenalty > 0): ?>
										<a href="contact" class="text-xs bg-red-600 text-white px-3 py-1.5 rounded hover:bg-red-700 transition">
											Contact Us
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>