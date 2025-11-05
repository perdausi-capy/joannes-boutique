<?php
// src/Views/admin/partials/sidebar.php
// Dynamic admin sidebar - reusable component

// Get current page from URL to determine active link
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$currentPage = basename(parse_url($currentPath, PHP_URL_PATH));

// Define navigation links
$navLinks = [
    [
        'icon' => 'home',
        'label' => 'Dashboard',
        'href' => 'admin/dashboard',
        'page' => 'dashboard'
    ],
    [
        'icon' => 'box',
        'label' => 'Products',
        'href' => 'admin/products',
        'page' => 'products'
    ],
    [
        'icon' => 'tags',
        'label' => 'Categories',
        'href' => 'admin/categories',
        'page' => 'categories'
    ],
    [
        'icon' => 'shopping-cart',
        'label' => 'Orders',
        'href' => 'admin/orders',
        'page' => 'orders'
    ],
    [
        'icon' => 'users',
        'label' => 'Users',
        'href' => 'admin/users',
        'page' => 'users'
    ],
    [
        'icon' => 'comments',
        'label' => 'Testimonials',
        'href' => 'admin/testimonials',
        'page' => 'testimonials'
    ],
    [
        'icon' => 'gift',
        'label' => 'Packages',
        'href' => 'admin/packages',
        'page' => 'packages'
    ]
    // [
    //     'icon' => 'envelope',
    //     'label' => 'Contacts',
    //     'href' => 'admin/contacts',
    //     'page' => 'contacts'
    // ]
];

// Function to check if link is active
function isActivePage($linkPage, $currentPath) {
    return strpos($currentPath, $linkPage) !== false;
}
?>

<style>
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
        padding-left: 1.75rem;
    }
    
    .nav-link.active {
        background: rgba(212, 175, 55, 0.15);
        color: #d4af37;
        font-weight: 600;
    }
    
    .logo-text {
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<!-- Sidebar -->
<div class="w-72 fixed top-0 left-0 h-screen sidebar-gradient shadow-2xl flex flex-col z-20 pt-20">
    <div class="p-6 border-b border-gray-700">
        <h1 class="text-2xl font-serif-elegant font-bold logo-text mb-1">Joanne's Admin</h1>
        <p class="text-sm text-gray-400">Welcome back,</p>
        <p class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4">
        <?php foreach ($navLinks as $link): ?>
            <a href="<?php echo $link['href']; ?>" 
               class="nav-link flex items-center px-6 py-3 text-gray-300 hover:text-yellow-400 <?php echo isActivePage($link['page'], $currentPath) ? 'active' : ''; ?>">
                <i class="fas fa-<?php echo $link['icon']; ?> w-5 mr-3"></i>
                <span><?php echo $link['label']; ?></span>
            </a>
        <?php endforeach; ?>
        
        <div class="my-4 border-t border-gray-700"></div>
        
        <a href="<?php echo rtrim(BASE_URL, '/'); ?>" class="nav-link flex items-center px-6 py-3 text-gray-300 hover:text-yellow-400">
            <i class="fas fa-globe w-5 mr-3"></i>
            <span>View Website</span>
        </a>
        <a href="auth/logout" class="nav-link flex items-center px-6 py-3 text-red-400 hover:text-red-300 hover:bg-red-900/20">
            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
            <span>Logout</span>
        </a>
    </nav>   
</div>