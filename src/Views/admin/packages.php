<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Packages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>[x-cloak]{display:none!important}</style>
</head>
<style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif-elegant {
            font-family: 'Cormorant Garamond', serif;
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #d4af37, #f4d03f);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            transform: scaleY(1);
        }
        
        .nav-link:hover {
            background: rgba(212, 175, 55, 0.1);
            padding-left: 1.5rem;
        }
        
        .nav-link.active {
            background: rgba(212, 175, 55, 0.15);
            color: #d4af37;
            font-weight: 600;
        }
        
        .stat-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }
        
        .gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        }
        
        .gradient-pink {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
        }
        
        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        }
        
        .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        footer{
            display: none;
        }
        .stat-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: rgba(212, 175, 55, 0.05);
            transform: scale(1.01);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(249, 250, 251, 1) 100%);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-text {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .action-link {
            transition: all 0.2s ease;
        }
        
        .action-link:hover {
            transform: translateX(3px);
        }
        nav{
            display:block;
        }
    </style> 
<body class="bg-gray-100 min-h-screen" x-data="packagesPage()">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-800">Manage Packages</h1>
                    <button @click="openModal()" class="px-4 py-2 bg-gold-400 text-white rounded hover:bg-gold-500"><i class="fas fa-plus mr-2"></i>Add Package</button>
                </div>
            </header>
            <main class="p-6">
                <script>
                window.packagesData = <?php echo json_encode(array_column(array_map(function($r){
                    $r['inclusions'] = json_decode($r['inclusions'] ?? '{}', true);
                    return $r;
                }, $packages ?? []), null, 'package_id'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
                </script>
                <?php if (!empty($message)): ?>
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-800"><?php echo $message; ?></div>
                <?php endif; ?>
                <!-- List existing packages -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Existing Packages</h2>
                        <button @click="openModal()" class="px-3 py-1 bg-gold-400 text-white rounded">+ Add Package</button>
                    </div>
                    <div class="overflow-x-auto" x-data x-init="window.packagesData = <?php echo json_encode(array_column(array_map(function($r){
                            $r['inclusions'] = json_decode($r['inclusions'] ?? '{}', true);
                            return $r;
                        }, $packages ?? []), null, 'package_id')); ?>">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hotel</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guests</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach (($packages ?? []) as $p): ?>
                                <tr>
                                    <td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($p['package_name']); ?></td>
                                    <td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($p['hotel_name']); ?></td>
                                    <td class="px-4 py-2 text-sm"><?php echo (int)$p['number_of_guests']; ?></td>
                                    <td class="px-4 py-2 text-sm">₱<?php echo number_format($p['price'] ?? 0, 2); ?></td>
                                    <td class="px-4 py-2 text-sm space-x-2">
                                        <button type="button" @click="prefillAndOpen(<?php echo (int)$p['package_id']; ?>)" class="px-3 py-1 border rounded">Edit</button>
                                        <form method="post" class="inline" onsubmit="return confirm('Delete this package?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="package_id" value="<?php echo (int)$p['package_id']; ?>">
                                            <button type="submit" class="px-3 py-1 border rounded text-red-600">Delete</button>
                                        </form>
                                        <a href="<?php echo rtrim(BASE_URL, '/'); ?>/packages/view/<?php echo (int)$p['package_id']; ?>" class="px-3 py-1 border rounded">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($packages ?? [])): ?>
                                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No packages yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal -->
                <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>
                    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-5xl p-6 max-h-[85vh] overflow-y-auto">
                        <h3 class="text-lg font-semibold mb-4" x-text="form.package_label || (editingId ? 'Edit Package' : 'Add Package')"></h3>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" :value="editingId ? 'edit' : 'create'">
                            <input type="hidden" name="package_id" :value="editingId">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium" x-text="form.package_label || 'Package Name'"></label>
                                    <input class="w-full border rounded px-3 py-2" name="package_name" x-model="form.package_name" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Number of Guests</label>
                                    <input type="number" class="w-full border rounded px-3 py-2" name="number_of_guests" x-model="form.number_of_guests" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Hotel Name</label>
                                    <input class="w-full border rounded px-3 py-2" name="hotel_name" x-model="form.hotel_name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Hotel Address</label>
                                    <input class="w-full border rounded px-3 py-2" name="hotel_address" x-model="form.hotel_address">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Hotel Description</label>
                                    <textarea rows="2" class="w-full border rounded px-3 py-2" name="hotel_description" x-model="form.hotel_description"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Package Price (₱)</label>
                                    <input type="number" step="0.01" class="w-full border rounded px-3 py-2" name="price" x-model="form.price" min="0">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Freebies</label>
                                    <textarea rows="2" class="w-full border rounded px-3 py-2" name="freebies" x-model="form.freebies"></textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Background Image</label>
                                    <input type="file" name="background_image" accept="image/*" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2 flex gap-2 items-end mb-2">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium">Add Custom Section</label>
                                        <input type="text" x-model="newSectionName" class="w-full border rounded px-3 py-2" placeholder="e.g., Entertainment">
                                    </div>
                                    <button type="button" @click="addCustomSection()" class="px-3 py-2 border rounded">Add Section</button>
                                </div>
                                <template x-for="section in inclusionSections" :key="section.key">
                                    <div class="border rounded-lg">
                                        <div class="flex items-center justify-between px-3 py-2 border-b">
                                            <input class="font-semibold flex-1 mr-2" :name="`inclusion_labels[${section.key}]`" x-model="section.label">
                                            <button type="button" @click="addInclusionForm(section.key)" class="text-sm px-2 py-1 border rounded">+ Add Item</button>
                                        </div>
                                        <div class="p-3 space-y-2">
                                            <template x-for="(item, i) in form.inclusions[section.key]" :key="i">
                                                <div class="flex items-center gap-2">
                                                    <input class="flex-1 border rounded px-3 py-2" :name="`inclusions[${section.key}][]`" x-model="form.inclusions[section.key][i]">
                                                    <button type="button" class="px-2 py-1 border rounded text-red-600" @click="removeInclusionForm(section.key, i)">Remove</button>
                                                </div>
                                            </template>
                                            <p x-show="form.inclusions[section.key].length === 0" class="text-sm text-gray-400">No items yet.</p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="mt-6 flex justify-end gap-2">
                                <button type="button" @click="closeModal()" class="px-4 py-2 border rounded">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-gold-400 text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

<script>
function packagesPage() {
    return {
        modalOpen: false,
        editingId: null,
        form: newForm(),
        inclusionSections: [],
        openModal() { this.editingId = null; this.form = newForm(); this.inclusionSections = []; this.modalOpen = true; },
        closeModal() { this.modalOpen = false; },
        addInclusionForm(key) { this.form.inclusions[key].push(''); },
        removeInclusionForm(key, i) { this.form.inclusions[key].splice(i,1); },
        prefillAndOpen(id) {
            this.editingId = id;
            const p = (window.packagesData && window.packagesData[id]) ? window.packagesData[id] : null;
            this.form = newForm();
            if (p) {
                this.form.package_name = p.package_name || '';
                this.form.hotel_name = p.hotel_name || '';
                this.form.hotel_address = p.hotel_address || '';
                this.form.hotel_description = p.hotel_description || '';
                this.form.number_of_guests = p.number_of_guests || 0;
                this.form.price = p.price || '';
                this.form.freebies = p.freebies || '';
                // Build sections from inclusions labels
                this.inclusionSections = [];
                this.form.inclusions = {};
                const inc = p.inclusions || {};
                Object.keys(inc).forEach(label => {
                    const key = label.toLowerCase().replace(/\s+/g,'_');
                    this.inclusionSections.push({ key, label });
                    this.form.inclusions[key] = Array.isArray(inc[label]) ? [...inc[label]] : [];
                });
            }
            this.modalOpen = true;
        },
        newSectionName: '',
        addCustomSection() {
            const key = this.newSectionName.trim().toLowerCase().replace(/\s+/g,'_');
            const label = this.newSectionName.trim();
            if (!key) return;
            if (!this.inclusionSections.find(s => s.key === key)) {
                this.inclusionSections.push({ key, label });
                this.form.inclusions[key] = [];
            }
            this.newSectionName = '';
        },
    };
}
function newForm() {
    return {
        package_name: '',
        package_label: '',
        hotel_name: '',
        hotel_address: '',
        hotel_description: '',
        number_of_guests: 0,
        price: '',
        freebies: '',
        inclusions: {},
    };
}
</script>
</body>
</html>


