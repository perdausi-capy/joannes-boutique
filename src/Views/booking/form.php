<?php $pageTitle = "Booking | Joanne's"; ?>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
	<h1 class="text-3xl font-light text-gray-800 mb-6">Book a Consultation</h1>
	<?php if (!empty($error)): ?>
		<div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error); ?></div>
	<?php endif; ?>
	<form method="post" action="booking" class="bg-white rounded-xl shadow p-6 space-y-4">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<div>
				<label class="block mb-1 font-medium">First Name</label>
				<input name="first_name" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-medium">Last Name</label>
				<input name="last_name" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-medium">Email</label>
				<input type="email" name="email" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-medium">Phone</label>
				<input name="phone" class="w-full border rounded px-3 py-2" required>
			</div>
			<div>
				<label class="block mb-1 font-medium">Service Type</label>
				<select name="service_type" class="w-full border rounded px-3 py-2" required>
					<option value="consultation">Consultation</option>
					<option value="fitting">Fitting</option>
					<option value="alteration">Alteration</option>
					<option value="custom">Custom</option>
				</select>
			</div>
			<div>
				<label class="block mb-1 font-medium">Preferred Date</label>
				<input type="date" name="preferred_date" class="w-full border rounded px-3 py-2">
			</div>
		</div>
		<div>
			<label class="block mb-1 font-medium">Message</label>
			<textarea name="message" class="w-full border rounded px-3 py-2" rows="4"></textarea>
		</div>
		<button class="px-6 py-3 bg-gold-400 text-white rounded hover:bg-gold-500">Submit Booking</button>
	</form>
</div>
