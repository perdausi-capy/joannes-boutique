<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Message</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-800">Message Details</h1>
                    <a href="admin/contacts" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Messages
                    </a>
                </div>
            </header>
            <main class="p-6">
                <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="text-sm text-gray-600">Name</div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($message['name'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="text-gray-900"><?php echo htmlspecialchars($message['email'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Phone</div>
                            <div class="text-gray-900"><?php echo htmlspecialchars($message['phone'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Date</div>
                            <div class="text-gray-900"><?php echo isset($message['created_at']) ? date('M d, Y H:i', strtotime($message['created_at'])) : ''; ?></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="text-sm text-gray-600">Subject</div>
                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($message['subject'] ?? ''); ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Message</div>
                        <div class="whitespace-pre-wrap text-gray-900 border rounded p-4 bg-gray-50"><?php echo nl2br(htmlspecialchars($message['message'] ?? '')); ?></div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <a href="mailto:<?php echo htmlspecialchars($message['email'] ?? ''); ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fas fa-reply mr-2"></i>Reply</a>
                        <button onclick="deleteContact(<?php echo (int)$message['id']; ?>)" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"><i class="fas fa-trash mr-2"></i>Delete</button>
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
            if (d.success) window.location.href = 'admin/contacts';
            else alert(d.message || 'Failed to delete');
        })
        .catch(() => alert('Request failed'))
    }
    </script>
</body>
</html>

