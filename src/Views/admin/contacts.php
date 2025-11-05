<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contacts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-800">Contact Messages</h1>
                </div>
            </header>
            <main class="p-6">
                <div class="bg-white rounded-xl shadow border">
                    <div class="px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold">Messages</h2>
                                <p class="text-sm text-gray-500">Total: <?php echo count($messages ?? []); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (!empty($messages)): foreach ($messages as $m): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">#<?php echo (int)$m['id']; ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($m['name'] ?? ''); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($m['email'] ?? ''); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate"><?php echo htmlspecialchars($m['subject'] ?? ''); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo isset($m['created_at']) ? date('M d, Y H:i', strtotime($m['created_at'])) : ''; ?></td>
                                    <td class="px-6 py-4 text-sm text-right space-x-2">
                                        <a href="admin/contacts/view/<?php echo (int)$m['id']; ?>" class="inline-flex items-center px-3 py-1.5 rounded bg-blue-100 text-blue-700 hover:bg-blue-200"><i class="fas fa-eye mr-1"></i> View</a>
                                        <button onclick="deleteContact(<?php echo (int)$m['id']; ?>)" class="inline-flex items-center px-3 py-1.5 rounded bg-red-100 text-red-700 hover:bg-red-200"><i class="fas fa-trash mr-1"></i> Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No messages found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    function deleteContact(id) {
        if (!confirm('Delete this message?')) return;
        fetch('admin/contacts/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) location.reload();
            else alert(d.message || 'Failed to delete');
        })
        .catch(() => alert('Request failed'))
    }
    </script>
</body>
</html>

