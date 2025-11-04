<?php
// Admin Category Form - Create/Edit
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $category ? 'Edit' : 'Add'; ?> Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            scrollbar-width: thin;
            scrollbar-color: #d4af37 transparent;
        }
        
        *::-webkit-scrollbar {
            width: 8px;
        }
        
        *::-webkit-scrollbar-track {
            background: transparent;
        }
        
        *::-webkit-scrollbar-thumb {
            background: #d4af37;
            border-radius: 4px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif-elegant {
            font-family: 'Cormorant Garamond', serif;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(249, 250, 251, 1) 100%);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .form-card {
            transition: all 0.3s ease;
        }
        
        .form-card:hover {
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.1);
        }
        
        .input-enhanced {
            transition: all 0.3s ease;
        }
        
        .input-enhanced:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-gold-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gold-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
            transition: left 0.3s ease;
        }
        
        .btn-gold-gradient:hover::before {
            left: 0;
        }
        
        .btn-gold-gradient span {
            position: relative;
            z-index: 1;
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
        
        .alert-error {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            animation: slideInDown 0.4s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .image-preview {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }
        
        footer {
            display: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="header-gradient shadow-sm border-b px-8 py-6 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-serif-elegant font-bold text-gray-900">
                            <?php echo $category ? 'Edit Category' : 'Add New Category'; ?>
                        </h1>
                        <p class="text-gray-600 mt-1">
                            <?php echo $category ? 'Update category information' : 'Create a new product category'; ?>
                        </p>
                    </div>
                    <a href="<?php echo $this->baseUrl; ?>/admin/categories" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Categories
                    </a>
                </div>
            </header>

            <main class="p-8">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-error mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg fade-in">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                        <span class="font-semibold"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Category Form -->
                <div class="form-card bg-white rounded-2xl shadow-xl p-8 fade-in max-w-4xl mx-auto">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-<?php echo $category ? 'edit' : 'plus'; ?> text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif-elegant font-bold text-gray-900">
                                Category Information
                            </h2>
                            <p class="text-sm text-gray-600">Fill in the details below</p>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" 
                          action="<?php echo $this->baseUrl; ?>/admin/categories/<?php echo $category ? 'update/' . $category['id'] : 'store'; ?>">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-tag text-yellow-600 mr-1"></i>
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" 
                                       value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" 
                                       placeholder="Enter category name" required>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-link text-yellow-600 mr-1"></i>
                                    Slug
                                </label>
                                <input type="text" name="slug" 
                                       value="<?php echo htmlspecialchars($category['slug'] ?? ''); ?>" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" 
                                       placeholder="URL-friendly name (auto-generated if empty)">
                                <p class="text-xs text-gray-500 mt-2">Leave empty to auto-generate from name</p>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-align-left text-yellow-600 mr-1"></i>
                                    Description
                                </label>
                                <textarea name="description" rows="4" 
                                          class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none"
                                          placeholder="Enter category description"><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-image text-yellow-600 mr-1"></i>
                                    Category Image
                                </label>
                                <input type="file" name="image" accept="image/*" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-2">Upload an image for this category (JPEG, PNG, GIF, WebP - Max 5MB)</p>
                                
                                <?php if (!empty($category['image'])): ?>
                                    <div class="mt-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Current image:</p>
                                        <div class="image-preview inline-block">
                                            <img src="uploads/<?php echo htmlspecialchars($category['image']); ?>" 
                                                 alt="Current Category Image" 
                                                 class="h-32 w-32 object-cover rounded-lg shadow-lg">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" 
                                           <?php echo (!isset($category['is_active']) || $category['is_active']) ? 'checked' : ''; ?>
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                                    <span class="ml-3 text-gray-700 font-semibold">
                                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                        Active (Visible to customers)
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex gap-4">
                            <button type="submit" class="btn-gold-gradient px-8 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <?php echo $category ? 'Update Category' : 'Create Category'; ?>
                                </span>
                            </button>
                            <a href="<?php echo $this->baseUrl; ?>/admin/categories" 
                               class="px-8 py-4 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>