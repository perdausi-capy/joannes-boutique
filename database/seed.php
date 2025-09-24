<?php
require_once __DIR__ . '/../src/Utils/Database.php';

class Seeder {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    public function run() {
        echo "Seeding database with sample data...\n";
        
        $this->seedUsers();
        $this->seedCategories();
        $this->seedProducts();
        $this->seedTestimonials();
        
        echo "Database seeding completed!\n";
    }
    
    private function seedUsers() {
        // Check if admin exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@joannesgowns.com']);
        
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("\n                INSERT INTO users (first_name, last_name, email, password, phone, role) \n                VALUES (?, ?, ?, ?, ?, ?)\n            ");
            $stmt->execute([
                'Joanne', 'Administrator', 'admin@joannesgowns.com', 
                $hashedPassword, '+63 2 8123 4567', 'admin'
            ]);
            echo "✓ Admin user created (email: admin@joannesgowns.com, password: admin123)\n";
        }
        
        // Sample customer
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['customer@example.com']);
        
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('customer123', PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("\n                INSERT INTO users (first_name, last_name, email, password, phone, role) \n                VALUES (?, ?, ?, ?, ?, ?)\n            ");
            $stmt->execute([
                'Jane', 'Smith', 'customer@example.com', 
                $hashedPassword, '+63 917 123 4567', 'customer'
            ]);
            echo "✓ Sample customer created\n";
        }
    }
    
    private function seedCategories() {
        $categories = [
            ['Gowns', 'gowns', 'Elegant evening and formal gowns'],
            ['Wedding Dresses', 'wedding-dresses', 'Beautiful bridal gowns for your special day'],
            ['Suits', 'suits', 'Professional and formal suits'],
            ['Accessories', 'accessories', 'Matching accessories and jewelry']
        ];
        
        foreach ($categories as $category) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
            $stmt->execute([$category[1]]);
            
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->db->prepare("\n                    INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)\n                ");
                $stmt->execute($category);
            }
        }
        echo "✓ Categories seeded\n";
    }
    
    private function seedProducts() {
        // Get category IDs
        $stmt = $this->db->query("SELECT id, name FROM categories");
        $categories = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        $products = [
            [
                'category' => 'Gowns',
                'name' => 'Elegant Evening Gown',
                'description' => 'Sophisticated floor-length gown perfect for formal events and galas',
                'price' => 1299.00,
                'stock_quantity' => 5,
                'is_featured' => true
            ],
            [
                'category' => 'Wedding Dresses',
                'name' => 'Classic Bridal Gown',
                'description' => 'Timeless wedding dress with intricate lace details and cathedral train',
                'price' => 2499.00,
                'stock_quantity' => 3,
                'is_featured' => true
            ],
            [
                'category' => 'Suits',
                'name' => 'Premium Business Suit',
                'description' => 'Tailored three-piece suit with premium wool fabric',
                'price' => 899.00,
                'stock_quantity' => 10,
                'is_featured' => true
            ],
            [
                'category' => 'Suits',
                'name' => 'Formal Tuxedo',
                'description' => 'Black-tie ready tuxedo with satin lapels and cummerbund',
                'price' => 1199.00,
                'stock_quantity' => 7,
                'is_featured' => false
            ],
            [
                'category' => 'Gowns',
                'name' => 'Cocktail Dress',
                'description' => 'Chic midi-length dress for semi-formal occasions',
                'price' => 699.00,
                'stock_quantity' => 8,
                'is_featured' => true
            ],
            [
                'category' => 'Accessories',
                'name' => 'Designer Accessories Set',
                'description' => 'Matching accessories including bags, shoes, and jewelry',
                'price' => 299.00,
                'stock_quantity' => 15,
                'is_featured' => false
            ]
        ];
        
        foreach ($products as $product) {
            $categoryId = null;
            foreach ($categories as $id => $name) {
                if ($name === $product['category']) {
                    $categoryId = $id;
                    break;
                }
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE name = ?");
            $stmt->execute([$product['name']]);
            
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->db->prepare("\n                    INSERT INTO products (category_id, name, description, price, stock_quantity, is_featured) \n                    VALUES (?, ?, ?, ?, ?, ?)\n                ");
                $stmt->execute([
                    $categoryId,
                    $product['name'],
                    $product['description'],
                    $product['price'],
                    $product['stock_quantity'],
                    $product['is_featured']
                ]);
            }
        }
        echo "✓ Products seeded\n";
    }
    
    private function seedTestimonials() {
        $testimonials = [
            [
                'name' => 'Sarah Martinez',
                'email' => 'sarah@example.com',
                'message' => 'Absolutely stunning work! Joanne created the perfect wedding dress for me. The attention to detail was incredible, and the fit was flawless. I felt like a princess on my special day.',
                'rating' => 5,
                'status' => 'approved'
            ],
            [
                'name' => 'Michael Rodriguez',
                'email' => 'michael@example.com',
                'message' => 'The custom suit I ordered exceeded all my expectations. Professional, elegant, and perfectly tailored. I have received countless compliments and will definitely be returning.',
                'rating' => 5,
                'status' => 'approved'
            ],
            [
                'name' => 'Emma Thompson',
                'email' => 'emma@example.com',
                'message' => 'From consultation to final fitting, the service was exceptional. Joanne understood my vision perfectly and created a gown that made me feel confident and beautiful.',
                'rating' => 5,
                'status' => 'approved'
            ]
        ];
        
        foreach ($testimonials as $testimonial) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM testimonials WHERE email = ?");
            $stmt->execute([$testimonial['email']]);
            
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->db->prepare("\n                    INSERT INTO testimonials (name, email, message, rating, status) \n                    VALUES (?, ?, ?, ?, ?)\n                ");
                $stmt->execute([
                    $testimonial['name'],
                    $testimonial['email'],
                    $testimonial['message'],
                    $testimonial['rating'],
                    $testimonial['status']
                ]);
            }
        }
        echo "✓ Testimonials seeded\n";
    }
}

// Run seeder
if (php_sapi_name() === 'cli') {
    $seeder = new Seeder();
    $seeder->run();
}


