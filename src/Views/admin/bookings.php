<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Bookings</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
	<div class="flex">
		<div class="w-64 bg-white shadow-lg">
			<div class="p-4 border-b">
				<h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
			</div>
			<nav class="mt-4">
				<a href="/admin" class="block px-4 py-3 text-gray-700 hover:bg-gray-50"><i class="fas fa-dashboard mr-2"></i> Dashboard</a>
				<a href="/admin/products" class="block px-4 py-3 text-gray-700 hover:bg-gray-50"><i class="fas fa-box mr-2"></i> Products</a>
				<a href="/admin/bookings" class="block px-4 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500"><i class="fas fa-calendar-alt mr-2"></i> Bookings</a>
			</nav>
		</div>
		<div class="flex-1 overflow-y-auto">
			<header class="bg-white shadow-sm border-b px-6 py-4">
				<h1 class="text-2xl font-semibold text-gray-800">Bookings</h1>
			</header>
			<main class="p-6">
				<div class="bg-white rounded-lg shadow p-6">
					<h2 class="text-lg font-semibold mb-4">Recent Bookings</h2>
					<div class="overflow-x-auto">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Preferred Date</th>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
									<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200">
								<?php foreach ($bookings as $b): ?>
								<tr>
									<td class="px-4 py-2 text-sm"><?php echo htmlspecialchars(($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? '')); ?></td>
									<td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($b['email'] ?? ''); ?></td>
									<td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($b['phone'] ?? ''); ?></td>
									<td class="px-4 py-2 text-sm capitalize"><?php echo htmlspecialchars($b['service_type'] ?? ''); ?></td>
									<td class="px-4 py-2 text-sm"><?php echo !empty($b['preferred_date']) ? date('M j, Y', strtotime($b['preferred_date'])) : '—'; ?></td>
									<td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($b['status'] ?? 'pending'); ?></td>
									<td class="px-4 py-2 text-sm"><?php echo date('M j, Y', strtotime($b['created_at'])); ?></td>
								</tr>
								<?php endforeach; ?>
								<?php if (empty($bookings)): ?>
								<tr>
									<td colspan="7" class="px-4 py-6 text-center text-gray-500">No bookings found.</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</main>
		</div>
	</div>
</body>
</html>
