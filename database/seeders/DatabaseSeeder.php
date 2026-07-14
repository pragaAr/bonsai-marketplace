<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PlantDetail;
use App\Models\Species;
use App\Models\PotDetail;
use App\Models\MediaDetail;
use App\Models\FertilizerDetail;
use App\Models\ToolDetail;
use App\Models\JournalEntry;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. Seed Roles ──
        $systemAdminRole = Role::create(['name' => 'system_admin', 'label' => 'System Administrator']);
        $adminRole = Role::create(['name' => 'admin',        'label' => 'Administrator']);
        $sellerRole = Role::create(['name' => 'seller',       'label' => 'Seller']);
        $userRole = Role::create(['name' => 'user',         'label' => 'User']);

        // ── 2. Seed Permissions ──
        // Product permissions
        $manageProducts = Permission::create(['name' => 'products.manage',  'label' => 'Kelola Produk']);
        $viewProducts = Permission::create(['name' => 'products.view',    'label' => 'Lihat Produk']);
        $createProducts = Permission::create(['name' => 'products.create',  'label' => 'Buat Produk']);
        $editProducts = Permission::create(['name' => 'products.edit',    'label' => 'Edit Produk']);
        $deleteProducts = Permission::create(['name' => 'products.delete',  'label' => 'Hapus Produk']);

        // Order permissions
        $manageOrders = Permission::create(['name' => 'orders.manage',    'label' => 'Kelola Pesanan']);
        $viewOrders = Permission::create(['name' => 'orders.view',      'label' => 'Lihat Pesanan']);
        $createOrders = Permission::create(['name' => 'orders.create',    'label' => 'Buat Pesanan']);

        // User permissions
        $manageUsers = Permission::create(['name' => 'users.manage',     'label' => 'Kelola Pengguna']);
        $viewUsers = Permission::create(['name' => 'users.view',       'label' => 'Lihat Pengguna']);

        // Role permissions
        $manageRoles = Permission::create(['name' => 'roles.manage',     'label' => 'Kelola Role']);

        // Settings permissions
        $manageSettings = Permission::create(['name' => 'settings.manage',  'label' => 'Kelola Pengaturan']);

        // ── 3. Assign Permissions to Roles ──
        // System Admin — akses penuh
        $systemAdminRole->givePermissionTo([
            $manageProducts, $viewProducts, $createProducts, $editProducts, $deleteProducts,
            $manageOrders, $viewOrders, $createOrders,
            $manageUsers, $viewUsers,
            $manageRoles,
            $manageSettings,
        ]);

        // Admin — kelola produk, order, user (tanpa role & settings)
        $adminRole->givePermissionTo([
            $manageProducts, $viewProducts, $createProducts, $editProducts, $deleteProducts,
            $manageOrders, $viewOrders,
            $viewUsers,
        ]);

        // Seller — kelola produk milik sendiri & lihat order
        $sellerRole->givePermissionTo([
            $viewProducts, $createProducts, $editProducts, $deleteProducts,
            $viewOrders,
        ]);

        // User — hanya lihat produk & buat order
        $userRole->givePermissionTo([
            $viewProducts,
            $createOrders, $viewOrders,
        ]);

        // ── 4. Seed Default Users ──
        $systemAdmin = User::create([
            'name' => 'System Administrator',
            'email' => 'sysadmin@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $systemAdmin->assignRole($systemAdminRole);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        // Seller 1
        $seller = User::create([
            'name' => 'Budi Santoso',
            'email' => 'seller@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $seller->assignRole($sellerRole);

        // Seller 2
        $seller2 = User::create([
            'name' => 'Ani Lestari',
            'email' => 'seller2@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $seller2->assignRole($sellerRole);

        $customer = User::create([
            'name' => 'Rian Wijaya',
            'email' => 'user@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole($userRole);

        // ── 5. Seed Categories ──
        $catTanaman = Category::create([
            'name' => 'Tanaman',
            'slug' => 'tanaman',
            'description' => 'Bonsai, bibit, dan bahan bonsai'
        ]);
        $catMedia = Category::create([
            'name' => 'Media Tanam',
            'slug' => 'media-tanam',
            'description' => 'Media tanam siap pakai, steril, dan subur'
        ]);
        $catPot = Category::create([
            'name' => 'Pot',
            'slug' => 'pot',
            'description' => 'Pilihan pot keramik, semen, tanah liat, dan plastik'
        ]);
        $catPupuk = Category::create([
            'name' => 'Pupuk',
            'slug' => 'pupuk',
            'description' => 'Pupuk penyubur daun, akar, dan bunga'
        ]);
        $catAlat = Category::create([
            'name' => 'Alat',
            'slug' => 'alat',
            'description' => 'Gunting, gergaji, catut kawat, dan aksesoris perawatan'
        ]);

        // ── 6. Seed Tag Produk ──
        $categoryTags = [
            $catTanaman->id => [
                ['name' => 'bonsai', 'slug' => 'bonsai', 'description' => 'Bonsai dan tanaman hias'],
                ['name' => 'bibit', 'slug' => 'bibit', 'description' => 'Bibit tanaman dan benih berkualitas'],
                ['name' => 'bahan-bonsai', 'slug' => 'bahan-bonsai', 'description' => 'Bahan dan perlengkapan bonsai'],
                ['name' => 'indoor', 'slug' => 'indoor', 'description' => 'Produk bonsai untuk dalam ruangan'],
                ['name' => 'outdoor', 'slug' => 'outdoor', 'description' => 'Produk bonsai untuk luar ruangan'],
                ['name' => 'mudah', 'slug' => 'mudah', 'description' => 'Perawatan mudah bagi pemula'],
                ['name' => 'sedang', 'slug' => 'sedang', 'description' => 'Tingkat perawatan sedang'],
                ['name' => 'sulit', 'slug' => 'sulit', 'description' => 'Tingkat perawatan sulit'],
                ['name' => 'tropis', 'slug' => 'tropis', 'description' => 'Tanaman tropis'],
                ['name' => 'subtropis', 'slug' => 'subtropis', 'description' => 'Tanaman subtropis'],
            ],
            $catPot->id => [
                ['name' => 'keramik', 'slug' => 'keramik', 'description' => 'Pot keramik berkualitas tinggi'],
                ['name' => 'semen', 'slug' => 'semen', 'description' => 'Pot semen tahan lama'],
                ['name' => 'tanah-liat', 'slug' => 'tanah-liat', 'description' => 'Pot tanah liat alami'],
                ['name' => 'plastik', 'slug' => 'plastik', 'description' => 'Pot plastik ringan dan tahan banting'],
                ['name' => 'oval', 'slug' => 'oval', 'description' => 'Bentuk pot oval'],
                ['name' => 'persegi', 'slug' => 'persegi', 'description' => 'Bentuk pot persegi'],
                ['name' => 'bulat', 'slug' => 'bulat', 'description' => 'Bentuk pot bulat'],
                ['name' => 'heksagonal', 'slug' => 'heksagonal', 'description' => 'Bentuk pot heksagonal'],
            ],
            $catMedia->id => [
                ['name' => 'pasir-malang', 'slug' => 'pasir-malang', 'description' => 'Pasir Malang bersih untuk media tanam'],
                ['name' => 'coco-peat', 'slug' => 'coco-peat', 'description' => 'Media tanam coco peat berkualitas'],
                ['name' => 'tanah-poros', 'slug' => 'tanah-poros', 'description' => 'Media tanam tanah poros'],
                ['name' => 'sekam-bakar', 'slug' => 'sekam-bakar', 'description' => 'Sekam bakar untuk aerasi media tanam'],
                ['name' => 'akar-pakis', 'slug' => 'akar-pakis', 'description' => 'Media akar pakis dan bahan organik'],
            ],
            $catPupuk->id => [
                ['name' => 'organik', 'slug' => 'organik', 'description' => 'Pupuk organik alami'],
                ['name' => 'kimia', 'slug' => 'kimia', 'description' => 'Pupuk kimia untuk pertumbuhan cepat'],
                ['name' => 'cair', 'slug' => 'cair', 'description' => 'Pupuk cair nutrisi tinggi'],
                ['name' => 'padat', 'slug' => 'padat', 'description' => 'Pupuk padat praktis digunakan'],
                ['name' => 'slow-release', 'slug' => 'slow-release', 'description' => 'Pupuk slow release untuk nutrisi berkala'],
            ],
            $catAlat->id => [
                ['name' => 'gunting', 'slug' => 'gunting', 'description' => 'Gunting untuk perawatan tanaman'],
                ['name' => 'gergaji', 'slug' => 'gergaji', 'description' => 'Gergaji kecil untuk pekerjaan detail'],
                ['name' => 'kawat', 'slug' => 'kawat', 'description' => 'Kawat bonsai untuk pembentukan cabang'],
                ['name' => 'pisau', 'slug' => 'pisau', 'description' => 'Pisau tajam untuk pemangkasan dan pembersihan'],
                ['name' => 'saleb-kambium', 'slug' => 'saleb-kambium', 'description' => 'Salep untuk menutup luka tanaman'],
                ['name' => 'pelindung-luka', 'slug' => 'pelindung-luka', 'description' => 'Pelindung luka untuk perawatan pasca pangkas']
            ],
        ];

        foreach ($categoryTags as $categoryId => $tags) {
            foreach ($tags as $tagData) {
                Tag::create(array_merge($tagData, ['category_id' => $categoryId]));
            }
        }

        // ── 7. Seed Tanaman (Bonsai) Products ──
        $productsData = [
            [
                'name' => 'Ficus Ginseng',
                'slug' => 'ficus-ginseng-01',
                'price' => 450000,
                'short_description' => 'Compact root form',
                'species' => 'Ficus microcarpa',
                'care_level' => 'Easy',
                'light' => 'Bright indirect light',
                'watering' => '2–3 times per week',
                'pot_size' => '18 cm',
                'description' => 'A striking bonsai with thick, bulbous exposed roots and a dense canopy of glossy green leaves. The Ficus Ginseng is one of the most forgiving bonsai species, making it ideal for beginners and seasoned collectors alike.',
                'image' => 'bonsai-1.png',
                'featured' => true,
            ],
            [
                'name' => 'Japanese Maple',
                'slug' => 'japanese-maple-01',
                'price' => 1250000,
                'short_description' => 'Autumn leaf color',
                'species' => 'Acer palmatum',
                'care_level' => 'Intermediate',
                'light' => 'Partial sun',
                'watering' => 'Daily in summer',
                'pot_size' => '22 cm',
                'description' => 'A graceful deciduous bonsai prized for its stunning seasonal color changes. Delicate palmate leaves shift from spring green to fiery autumn reds, creating a living artwork that marks the passage of time.',
                'image' => 'bonsai-2.png',
                'featured' => true,
            ],
            [
                'name' => 'Juniper Cascade',
                'slug' => 'juniper-cascade-01',
                'price' => 780000,
                'short_description' => 'Classic cascade style',
                'species' => 'Juniperus procumbens',
                'care_level' => 'Intermediate',
                'light' => 'Full sun',
                'watering' => 'When soil feels dry',
                'pot_size' => '20 cm',
                'description' => 'A dramatic cascade-style juniper with flowing branches that evoke a windswept mountain cliff. Dense, blue-green needle foliage provides year-round beauty and a powerful sense of movement.',
                'image' => 'bonsai-3.png',
                'featured' => true,
            ],
            [
                'name' => 'Chinese Elm',
                'slug' => 'chinese-elm-01',
                'price' => 380000,
                'short_description' => 'Fine leaf structure',
                'species' => 'Ulmus parvifolia',
                'care_level' => 'Easy',
                'light' => 'Bright indirect light',
                'watering' => '2–3 times per week',
                'pot_size' => '16 cm',
                'description' => 'A classic bonsai with a beautifully curved trunk, fine branching, and tiny serrated leaves. The Chinese Elm adapts well to indoor environments and responds beautifully to regular pruning.',
                'image' => 'bonsai-4.png',
                'featured' => true,
            ],
            [
                'name' => 'Bougainvillea',
                'slug' => 'bougainvillea-01',
                'price' => 520000,
                'short_description' => 'Vibrant blooms',
                'species' => 'Bougainvillea glabra',
                'care_level' => 'Intermediate',
                'light' => 'Full sun',
                'watering' => 'Let dry between watering',
                'pot_size' => '20 cm',
                'description' => 'A tropical bonsai bursting with papery purple-pink bracts that create a spectacular display. The twisted, woody trunk adds sculptural interest even during its brief dormancy period.',
                'image' => 'bonsai-5.png',
                'featured' => true,
            ],
            [
                'name' => 'Cherry Blossom',
                'slug' => 'cherry-blossom-01',
                'price' => 1450000,
                'short_description' => 'Spring sakura bloom',
                'species' => 'Prunus serrulata',
                'care_level' => 'Advanced',
                'light' => 'Full sun',
                'watering' => 'Keep soil moist',
                'pot_size' => '24 cm',
                'description' => 'An exquisite sakura bonsai that produces delicate pink blossoms each spring. This specimen embodies the Japanese philosophy of mono no aware — the beauty of transient things.',
                'image' => 'bonsai-6.png',
                'featured' => true,
            ],
            [
                'name' => 'Jade Bonsai',
                'slug' => 'jade-bonsai-01',
                'price' => 320000,
                'short_description' => 'Thick succulent leaves',
                'species' => 'Portulacaria afra',
                'care_level' => 'Easy',
                'light' => 'Full sun',
                'watering' => 'Once every 1-2 weeks',
                'pot_size' => '14 cm',
                'description' => 'A resilient succulent bonsai with a fleshy trunk and small, emerald-green leaves. It stores water in its trunk, making it incredibly drought-tolerant and easy to maintain indoor or outdoor.',
                'image' => 'bonsai-1.png',
                'featured' => false,
            ],
            [
                'name' => 'Satsuki Azalea',
                'slug' => 'satsuki-azalea-01',
                'price' => 950000,
                'short_description' => 'Spectacular summer flowers',
                'species' => 'Rhododendron indicum',
                'care_level' => 'Intermediate',
                'light' => 'Morning sun, afternoon shade',
                'watering' => 'Daily, loves humidity',
                'pot_size' => '18 cm',
                'description' => 'A highly prized flowering bonsai known for its abundant blossoms that appear in late spring. This azalea cultivar prefers acidic soil and consistent moisture to thrive.',
                'image' => 'bonsai-6.png',
                'featured' => false,
            ],
            [
                'name' => 'Japanese Black Pine',
                'slug' => 'black-pine-01',
                'price' => 1800000,
                'short_description' => 'Rugged bark & dark needles',
                'species' => 'Pinus thunbergii',
                'care_level' => 'Advanced',
                'light' => 'Full sun',
                'watering' => 'Allow to dry slightly',
                'pot_size' => '26 cm',
                'description' => 'The King of Bonsai. This Japanese Black Pine features thick, dark needles, rigid growth, and rough, fissured bark that exudes strength and maturity. Requires specialized pruning techniques.',
                'image' => 'bonsai-3.png',
                'featured' => false,
            ],
            [
                'name' => 'Snowrose Serissa',
                'slug' => 'serissa-01',
                'price' => 280000,
                'short_description' => 'Tiny white star flowers',
                'species' => 'Serissa foetida',
                'care_level' => 'Intermediate',
                'light' => 'Bright indirect light',
                'watering' => 'Keep moist but not soggy',
                'pot_size' => '14 cm',
                'description' => 'Commonly known as the "Tree of a Thousand Stars," this delicate bonsai produces white, star-shaped flowers throughout the summer. It features fine, small foliage and beautifully textured bark.',
                'image' => 'bonsai-4.png',
                'featured' => false,
            ],
            [
                'name' => 'Hawaiian Umbrella',
                'slug' => 'hawaiian-umbrella-01',
                'price' => 340000,
                'short_description' => 'Lush palmate canopy',
                'species' => 'Schefflera arboricola',
                'care_level' => 'Easy',
                'light' => 'Medium to bright light',
                'watering' => 'When top soil dries',
                'pot_size' => '16 cm',
                'description' => 'An easy-to-grow tropical bonsai with dark green leaves arranged in umbrella-like clusters. It develops attractive aerial roots under high humidity, giving it a mature banyan look.',
                'image' => 'bonsai-1.png',
                'featured' => false,
            ],
            [
                'name' => 'Brazilian Rain Tree',
                'slug' => 'brazilian-rain-01',
                'price' => 880000,
                'short_description' => 'Compounded delicate leaves',
                'species' => 'Chloroleucon tortum',
                'care_level' => 'Intermediate',
                'light' => 'Full sun to partial shade',
                'watering' => 'Regular watering',
                'pot_size' => '22 cm',
                'description' => 'An exotic tropical bonsai with a contorted trunk, light gray bark, and delicate bipinnate leaves that fold up at night. Branches have small thorns but form beautiful, layered clouds of foliage.',
                'image' => 'bonsai-5.png',
                'featured' => false,
            ],
            [
                'name' => 'Fukien Tea',
                'slug' => 'fukien-tea-01',
                'price' => 420000,
                'short_description' => 'Shiny leaves & white flowers',
                'species' => 'Carmona retusa',
                'care_level' => 'Intermediate',
                'light' => 'Bright indirect light',
                'watering' => 'Keep soil moist',
                'pot_size' => '18 cm',
                'description' => 'A beautiful indoor bonsai with small, dark, glossy leaves and occasional tiny white flowers followed by red berries. The rough-textured bark adds a sense of age and character.',
                'image' => 'bonsai-2.png',
                'featured' => false,
            ],
            [
                'name' => 'Dwarf Pomegranate',
                'slug' => 'pomegranate-01',
                'price' => 560000,
                'short_description' => 'Miniature fruit bearer',
                'species' => 'Punica granatum',
                'care_level' => 'Intermediate',
                'light' => 'Full sun',
                'watering' => 'Regular, don\'t let dry',
                'pot_size' => '18 cm',
                'description' => 'A delightful fruiting bonsai that produces vivid orange-red flowers followed by miniature pomegranate fruits. This deciduous specimen offers beauty in every season through flowers, fruit, and autumn color.',
                'image' => 'bonsai-5.png',
                'featured' => false,
            ],
            [
                'name' => 'Trident Maple',
                'slug' => 'trident-maple-01',
                'price' => 920000,
                'short_description' => 'Three-lobed leaves',
                'species' => 'Acer buergerianum',
                'care_level' => 'Intermediate',
                'light' => 'Full to partial sun',
                'watering' => 'Daily in summer',
                'pot_size' => '22 cm',
                'description' => 'A vigorous deciduous bonsai with distinctive three-pointed leaves that turn brilliant orange and red in autumn. Develops impressive bark texture and root flare with age.',
                'image' => 'bonsai-2.png',
                'featured' => false,
            ],
            [
                'name' => 'Money Tree Bonsai',
                'slug' => 'money-tree-01',
                'price' => 290000,
                'short_description' => 'Braided trunk style',
                'species' => 'Pachira aquatica',
                'care_level' => 'Easy',
                'light' => 'Medium indirect light',
                'watering' => 'Once per week',
                'pot_size' => '16 cm',
                'description' => 'A popular indoor bonsai featuring an elegantly braided trunk and lush, palmate leaves. According to feng shui tradition, it brings good luck and financial prosperity to its owner.',
                'image' => 'bonsai-4.png',
                'featured' => false,
            ],
            [
                'name' => 'Tiger Bark Ficus',
                'slug' => 'tiger-bark-ficus-01',
                'price' => 620000,
                'short_description' => 'Striped bark pattern',
                'species' => 'Ficus retusa',
                'care_level' => 'Easy',
                'light' => 'Bright indirect light',
                'watering' => 'When top soil dries',
                'pot_size' => '20 cm',
                'description' => 'A visually striking bonsai featuring distinctive tiger-striped bark patterns and dense, dark green foliage. Aerial roots develop beautifully over time, adding a banyan-like character.',
                'image' => 'bonsai-3.png',
                'featured' => false,
            ],
            [
                'name' => 'Wisteria Bonsai',
                'slug' => 'wisteria-01',
                'price' => 1350000,
                'short_description' => 'Cascading purple blooms',
                'species' => 'Wisteria floribunda',
                'care_level' => 'Advanced',
                'light' => 'Full sun',
                'watering' => 'Keep soil moist',
                'pot_size' => '24 cm',
                'description' => 'A spectacular flowering bonsai that produces cascading clusters of fragrant purple-blue blossoms. When in full bloom, the Wisteria creates a breathtaking display that is the pinnacle of bonsai artistry.',
                'image' => 'bonsai-6.png',
                'featured' => false,
            ],
        ];

        foreach ($productsData as $index => $data) {
            // Distribute products between seller 1 (Budi) and seller 2 (Ani)
            $sellerId = ($index % 2 === 0) ? $seller->id : $seller2->id;

            $speciesModel = Species::firstOrCreate(
                ['scientific_name' => $data['species']],
                ['common_name' => $data['common_name'] ?? null, 'slug' => Str::slug($data['species'])]
            );

            $plantDetail = PlantDetail::create([
                'species_id' => $speciesModel->id,
                'care_level' => $data['care_level'],
                'light' => $data['light'],
                'watering' => $data['watering'],
                'pot_size' => $data['pot_size'],
            ]);

            $imageName = $data['image'];

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'stock' => 1,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'category_id' => $catTanaman->id,
                'productable_id' => $plantDetail->id,
                'productable_type' => PlantDetail::class,
                'featured' => $data['featured'],
                'seller_id' => $sellerId,
                'status' => 'approved',
            ]);

            // Seed Image via Spatie MediaLibrary
            $imagePath = base_path('images/'.$imageName);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }

        // ── 7. Seed Pot Products ──
        $potsData = [
            [
                'name' => 'Pot Keramik Oval Biru',
                'slug' => 'pot-keramik-oval-biru',
                'price' => 120000,
                'short_description' => 'Impor, glasir biru mengkilap',
                'description' => 'Pot keramik impor berkualitas tinggi dengan lapisan glasir biru berkilau. Sangat cocok untuk bonsai gaya cascade atau informal upright.',
                'material' => 'Keramik',
                'shape' => 'Oval',
                'dimensions' => '24 x 18 x 6 cm',
                'color' => 'Biru',
                'image' => 'bonsai-2.png',
                'featured' => false,
            ],
            [
                'name' => 'Pot Semen Heksagonal',
                'slug' => 'pot-semen-heksagonal',
                'price' => 45000,
                'short_description' => 'Semen tebal, buatan lokal',
                'description' => 'Pot semen buatan pengrajin lokal dengan serat penguat. Memiliki lubang drainase lebar dan kaki pot yang kokoh untuk sirkulasi udara akar.',
                'material' => 'Semen',
                'shape' => 'Heksagonal',
                'dimensions' => '20 x 20 x 10 cm',
                'color' => 'Abu-abu',
                'image' => 'bonsai-4.png',
                'featured' => false,
            ],
        ];

        foreach ($potsData as $index => $data) {
            $sellerId = ($index % 2 === 0) ? $seller->id : $seller2->id;
            $potDetail = PotDetail::create([
                'material' => $data['material'],
                'shape' => $data['shape'],
                'dimensions' => $data['dimensions'],
                'color' => $data['color'],
            ]);

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'stock' => 1,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'category_id' => $catPot->id,
                'productable_id' => $potDetail->id,
                'productable_type' => PotDetail::class,
                'featured' => $data['featured'],
                'seller_id' => $sellerId,
                'status' => 'approved',
            ]);

            $imagePath = base_path('images/'.$data['image']);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)->preservingOriginal()->toMediaCollection('images');
            }
        }

        // ── 8. Seed Media Tanam Products ──
        $mediasData = [
            [
                'name' => 'Pasir Malang Steril Mesh 3-5mm',
                'slug' => 'pasir-malang-steril',
                'price' => 25000,
                'short_description' => 'Porositas tinggi, ukuran mesh 3-5mm',
                'description' => 'Pasir malang murni pilihan yang telah diayak dan disterilkan. Sangat baik untuk meningkatkan porositas media tanam agar akar tidak mudah busuk.',
                'type' => 'Pasir Malang',
                'weight' => '5 kg',
                'volume' => '6 Liter',
                'image' => 'bonsai-1.png',
                'featured' => false,
            ],
            [
                'name' => 'Premium Coco Peat Fermentasi',
                'slug' => 'coco-peat-premium',
                'price' => 15000,
                'short_description' => 'Bebas zat tanin berbahaya',
                'description' => 'Coco peat halus yang telah didekomposisi dan dicuci bersih untuk menurunkan kadar tanin. Menjaga kelembapan optimal pada perakaran bonsai.',
                'type' => 'Coco Peat',
                'weight' => '2 kg',
                'volume' => '4 Liter',
                'image' => 'bonsai-3.png',
                'featured' => false,
            ],
        ];

        foreach ($mediasData as $index => $data) {
            $sellerId = ($index % 2 === 0) ? $seller->id : $seller2->id;
            $mediaDetail = MediaDetail::create([
                'type' => $data['type'],
                'weight' => $data['weight'],
                'volume' => $data['volume'],
            ]);

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'stock' => 1,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'category_id' => $catMedia->id,
                'productable_id' => $mediaDetail->id,
                'productable_type' => MediaDetail::class,
                'featured' => $data['featured'],
                'seller_id' => $sellerId,
                'status' => 'approved',
            ]);

            $imagePath = base_path('images/'.$data['image']);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)->preservingOriginal()->toMediaCollection('images');
            }
        }

        // ── 9. Seed Pupuk Products ──
        $fertilizersData = [
            [
                'name' => 'Pupuk Dekastar Slow Release 17-11-10',
                'slug' => 'pupuk-dekastar-slow-release',
                'price' => 35000,
                'short_description' => 'Nutrisi stabil selama 6 bulan',
                'description' => 'Pupuk kimia terkendali (slow release) yang melepaskan nutrisi secara perlahan selama 6 bulan. Sangat praktis untuk pertumbuhan tunas dan daun baru.',
                'type' => 'Kimia',
                'form' => 'Slow Release',
                'weight' => '250 gram',
                'image' => 'bonsai-5.png',
                'featured' => false,
            ],
            [
                'name' => 'Pupuk Organik Cair Bio-Bonsai',
                'slug' => 'pupuk-organik-cair-bio-bonsai',
                'price' => 50000,
                'short_description' => 'Meningkatkan daya tahan pohon',
                'description' => 'Formula pupuk organik cair super pekat yang kaya akan unsur mikro dan hormon pertumbuhan. Mempercepat pertumbuhan kambium dan perakaran.',
                'type' => 'Organik',
                'form' => 'Cair',
                'weight' => '500 ml',
                'image' => 'bonsai-6.png',
                'featured' => false,
            ],
        ];

        foreach ($fertilizersData as $index => $data) {
            $sellerId = ($index % 2 === 0) ? $seller->id : $seller2->id;
            $fertilizerDetail = FertilizerDetail::create([
                'type' => $data['type'],
                'form' => $data['form'],
                'weight' => $data['weight'],
            ]);

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'stock' => 1,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'category_id' => $catPupuk->id,
                'productable_id' => $fertilizerDetail->id,
                'productable_type' => FertilizerDetail::class,
                'featured' => $data['featured'],
                'seller_id' => $sellerId,
                'status' => 'approved',
            ]);

            $imagePath = base_path('images/'.$data['image']);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)->preservingOriginal()->toMediaCollection('images');
            }
        }

        // ── 10. Seed Alat Products ──
        $toolsData = [
            [
                'name' => 'Gunting Ranting Ryuga Stainless',
                'slug' => 'gunting-ranting-ryuga-stainless',
                'price' => 350000,
                'short_description' => 'Original Ryuga Jepang, stainless',
                'description' => 'Gunting ranting kualitas profesional merek Ryuga buatan Jepang. Bilah tajam berbahan stainless steel memberikan potongan bersih tanpa merusak dahan.',
                'material' => 'Stainless Steel',
                'brand' => 'Ryuga',
                'weight' => '180 gram',
                'image' => 'bonsai-3.png',
                'featured' => false,
            ],
            [
                'name' => 'Kawat Aluminium Bonsai Set 1-3mm',
                'slug' => 'kawat-aluminium-bonsai-set',
                'price' => 95000,
                'short_description' => 'Lunak, kuat, paket bundling 3 ukuran',
                'description' => 'Kawat aluminium lunak impor berkualitas tinggi yang mudah dibentuk namun kuat menahan dahan. Paket terdiri dari diameter 1mm, 2mm, dan 3mm.',
                'material' => 'Aluminium',
                'brand' => 'Impor',
                'weight' => '500 gram',
                'image' => 'bonsai-1.png',
                'featured' => false,
            ],
        ];

        foreach ($toolsData as $index => $data) {
            $sellerId = ($index % 2 === 0) ? $seller->id : $seller2->id;
            $toolDetail = ToolDetail::create([
                'material' => $data['material'],
                'brand' => $data['brand'],
                'weight' => $data['weight'],
            ]);

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'stock' => 1,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'category_id' => $catAlat->id,
                'productable_id' => $toolDetail->id,
                'productable_type' => ToolDetail::class,
                'featured' => $data['featured'],
                'seller_id' => $sellerId,
                'status' => 'approved',
            ]);

            $imagePath = base_path('images/'.$data['image']);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)->preservingOriginal()->toMediaCollection('images');
            }
        }

        // ── 11. Seed Journal Entries ──
        $journalsData = [
            [
                'title' => 'Cara Menempatkan Bonsai di Dalam Ruangan',
                'slug' => 'journal-01',
                'excerpt' => 'Panduan singkat mengenai kebutuhan cahaya, sirkulasi udara, dan tata letak ideal untuk koleksi bonsai indoor Anda.',
                'category' => 'Perawatan',
                'author_id' => $systemAdmin->id,
                'content' => "Menempatkan pohon bonsai di dalam ruangan membutuhkan pemahaman mendalam tentang kebutuhan biologis pohon tersebut. Meskipun bonsai sering dianggap sebagai tanaman hias dalam ruangan biasa, pada kenyataannya mereka adalah pohon miniatur yang membutuhkan perhatian khusus.\n\nHal pertama yang harus diperhatikan adalah pencahayaan. Bonsai membutuhkan cahaya yang terang, idealnya dekat dengan jendela yang menghadap ke selatan atau timur. Tanpa cahaya yang cukup, pertumbuhan daun akan melemah dan cabang akan memanjang secara tidak wajar (etiolasi).\n\nSelain cahaya, kelembapan dan aliran udara sangat krusial. Udara di dalam ruangan cenderung kering karena pendingin ruangan (AC) atau pemanas. Letakkan nampan kelembapan (humidity tray) berisi kerikil dan air di bawah pot bonsai Anda untuk membantu menjaga kelembapan mikro di sekitar pohon. Hindari meletakkan bonsai langsung di depan hembusan AC atau kipas angin karena dapat mengeringkan daun dengan cepat.",
                'published_date' => '2026-03-15',
                'image' => 'journal-1.png',
            ],
            [
                'title' => 'Seni Pemangkasan Bonsai Musiman',
                'slug' => 'journal-02',
                'excerpt' => 'Memahami waktu dan teknik memangkas berbagai spesies bonsai untuk pertumbuhan dan bentuk estetis yang optimal.',
                'category' => 'Budi Daya',
                'author_id' => $admin->id,
                'content' => "Pemangkasan adalah inti dari seni bonsai. Melalui pemangkasan, kita tidak hanya mengontrol ukuran pohon tetapi juga mengarahkan energinya untuk membentuk estetika yang harmonis. Pemangkasan bonsai dibagi menjadi dua kategori utama: pemangkasan pemeliharaan dan pemangkasan struktural.\n\nPemangkasan pemeliharaan dilakukan sepanjang musim tumbuh untuk mempertahankan bentuk yang sudah ada. Ini melibatkan pemendekan tunas baru yang tumbuh terlalu panjang dan membuang daun-daun yang terlalu besar. Teknik ini merangsang pertumbuhan cabang yang lebih halus dan dedaunan yang lebih padat.\n\nPemangkasan struktural, di sini lain, biasanya dilakukan pada akhir musim gugur atau awal musim semi saat pohon dalam keadaan dorman. Pemangkasan ini melibatkan pemotongan cabang besar yang tidak diinginkan untuk mendesain ulang siluet dasar pohon. Selalu gunakan gunting khusus bonsai yang tajam untuk meminimalkan kerusakan pada jaringan kayu dan membantu luka sembuh lebih cepat.",
                'published_date' => '2026-02-28',
                'image' => 'bonsai-3.png',
            ],
            [
                'title' => 'Memilih Bonsai Pertama Anda',
                'slug' => 'journal-03',
                'excerpt' => 'Panduan lengkap bagi pemula untuk memilih spesies bonsai yang tepat sesuai kondisi ruangan dan gaya hidup.',
                'category' => 'Panduan Pemula',
                'author_id' => $seller->id,
                'content' => "Memulai perjalanan dalam dunia seni bonsai bisa terasa menakutkan dengan begitu banyaknya pilihan spesies. Kunci utama keberhasilan bagi pemula adalah memilih pohon yang tangguh dan memaafkan kesalahan perawatan kecil.\n\nSalah satu spesies terbaik untuk pemula adalah Ficus (seperti Ficus Ginseng atau Ficus Retusa). Pohon ini sangat toleran terhadap kelembapan rendah dan tingkat cahaya yang bervariasi di dalam ruangan. Ficus juga memiliki sistem akar yang kuat dan cepat pulih setelah pemangkasan.\n\nJika Anda ingin meletakkan bonsai di luar ruangan, Chinese Elm (Ulmus parvifolia) atau Juniper adalah pilihan klasik. Chinese Elm sangat adaptif dan memiliki pola percabangan yang indah. Sementara itu, Juniper menawarkan tampilan dramatis khas bonsai tradisional, meskipun membutuhkan sinar matahari penuh sepanjang hari untuk tetap sehat.",
                'published_date' => '2026-02-10',
                'image' => 'bonsai-1.png',
            ],
            [
                'title' => 'Konsep Wabi-Sabi dalam Seni Bonsai',
                'slug' => 'journal-04',
                'excerpt' => 'Menyelami filosofi estetika Jepang tentang keindahan dalam ketidaksempurnaan dan bagaimana hal itu membentuk karakter bonsai.',
                'category' => 'Estetika',
                'author_id' => $systemAdmin->id,
                'content' => "Wabi-Sabi adalah filosofi estetika Jepang yang menemukan keindahan dalam ketidaksempurnaan, ketidakkekalan, dan kesederhanaan. Filosofi ini sangat erat kaitannya dengan seni bonsai, di mana sebuah pohon tidak pernah benar-benar 'selesai' dan selalu berubah seiring berjalannya waktu.\n\nDalam bonsai, wabi-sabi tercermin pada batang pohon yang bengkok, kulit kayu yang kasar dan pecah-pecah, serta cabang yang asimetris. Alih-alih mencari simetri sempurna, seniman bonsai merangkul bekas luka alami dan tanda-tanda penuaan (seperti teknik Jin dan Shari, di mana sebagian kayu dibiarkan mati dan memutih untuk meniru pohon tua di alam liar).\n\nMerawat bonsai mengajarkan kita untuk menghargai proses pertumbuhan lambat dan menerima perubahan alami. Keindahan sejati bonsai tidak terletak pada kesempurnaan bentuknya, melainkan pada cerita kehidupan dan waktu yang tercermin melalui setiap lekukan dahan dan akar.",
                'published_date' => '2026-01-20',
                'image' => 'bonsai-2.png',
            ],
        ];

        foreach ($journalsData as $data) {
            $imageName = $data['image'];
            unset($data['image']);

            $journal = JournalEntry::create($data);

            // Seed Image via Spatie MediaLibrary
            $imagePath = base_path('images/'.$imageName);
            if (file_exists($imagePath)) {
                $journal->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
