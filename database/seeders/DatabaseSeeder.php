<?php

namespace Database\Seeders;

use App\Models\JournalEntry;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        $seller = User::create([
            'name' => 'Budi Santoso',
            'email' => 'seller@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $seller->assignRole($sellerRole);

        $customer = User::create([
            'name' => 'Rian Wijaya',
            'email' => 'user@bonsaiku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole($userRole);

        // ── 3. Seed Products ──
        $productsData = [
            [
                'name' => 'Ficus Ginseng',
                'slug' => 'ficus-ginseng-01',
                'price' => 450000,
                'short_description' => 'Compact root form',
                'category' => 'Indoor',
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
                'category' => 'Outdoor',
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
                'category' => 'Outdoor',
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
                'category' => 'Indoor',
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
                'category' => 'Flowering',
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
                'category' => 'Flowering',
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
                'short_description' => 'Succulent trunk form',
                'category' => 'Indoor',
                'species' => 'Crassula ovata',
                'care_level' => 'Easy',
                'light' => 'Bright direct light',
                'watering' => 'Once per week',
                'pot_size' => '14 cm',
                'description' => 'A unique succulent bonsai with thick, fleshy leaves and a stout trunk that stores water. Extremely low maintenance and perfect for bright windowsills. Symbolizes prosperity and good fortune.',
                'image' => 'bonsai-1.png',
                'featured' => false,
            ],
            [
                'name' => 'Satsuki Azalea',
                'slug' => 'satsuki-azalea-01',
                'price' => 890000,
                'short_description' => 'Abundant pink flowers',
                'category' => 'Flowering',
                'species' => 'Rhododendron indicum',
                'care_level' => 'Intermediate',
                'light' => 'Partial shade',
                'watering' => 'Keep soil consistently moist',
                'pot_size' => '22 cm',
                'description' => 'A breathtaking flowering bonsai that erupts in masses of delicate pink blooms. The Satsuki Azalea is a cornerstone of Japanese bonsai culture, treasured for centuries by master growers.',
                'image' => 'bonsai-6.png',
                'featured' => false,
            ],
            [
                'name' => 'Japanese Black Pine',
                'slug' => 'black-pine-01',
                'price' => 1680000,
                'short_description' => 'Classic pine form',
                'category' => 'Outdoor',
                'species' => 'Pinus thunbergii',
                'care_level' => 'Advanced',
                'light' => 'Full sun',
                'watering' => 'When soil is slightly dry',
                'pot_size' => '26 cm',
                'description' => 'The quintessential bonsai species. A powerful trunk with textured bark, paired with compact needle clusters, creates a miniature that perfectly mirrors an ancient coastal pine.',
                'image' => 'bonsai-3.png',
                'featured' => false,
            ],
            [
                'name' => 'Serissa Japonica',
                'slug' => 'serissa-01',
                'price' => 410000,
                'short_description' => 'Tiny star flowers',
                'category' => 'Indoor',
                'species' => 'Serissa foetida',
                'care_level' => 'Intermediate',
                'light' => 'Bright indirect light',
                'watering' => 'Keep soil moist',
                'pot_size' => '14 cm',
                'description' => 'Known as the Tree of a Thousand Stars, this elegant bonsai produces tiny white star-shaped flowers throughout the growing season. Its fine, dark green foliage creates a lush, detailed canopy.',
                'image' => 'bonsai-4.png',
                'featured' => false,
            ],
            [
                'name' => 'Hawaiian Umbrella',
                'slug' => 'hawaiian-umbrella-01',
                'price' => 350000,
                'short_description' => 'Tropical umbrella top',
                'category' => 'Tropical',
                'species' => 'Schefflera arboricola',
                'care_level' => 'Easy',
                'light' => 'Low to bright indirect',
                'watering' => 'When top soil is dry',
                'pot_size' => '16 cm',
                'description' => 'A resilient tropical bonsai with distinctive umbrella-shaped leaf clusters. One of the most tolerant indoor species, it thrives in varied light conditions and forgives occasional neglect.',
                'image' => 'bonsai-1.png',
                'featured' => false,
            ],
            [
                'name' => 'Brazilian Rain Tree',
                'slug' => 'brazilian-rain-01',
                'price' => 680000,
                'short_description' => 'Leaves fold at night',
                'category' => 'Tropical',
                'species' => 'Pithecellobium tortum',
                'care_level' => 'Intermediate',
                'light' => 'Bright indirect light',
                'watering' => '2–3 times per week',
                'pot_size' => '20 cm',
                'description' => 'A fascinating tropical bonsai whose delicate compound leaves fold closed at night, creating a living clock. Its zigzag branching pattern and tiny thorns add sculptural interest year-round.',
                'image' => 'bonsai-5.png',
                'featured' => false,
            ],
            [
                'name' => 'Fukien Tea Tree',
                'slug' => 'fukien-tea-01',
                'price' => 490000,
                'short_description' => 'Dark glossy foliage',
                'category' => 'Indoor',
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
                'category' => 'Flowering',
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
                'category' => 'Outdoor',
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
                'category' => 'Indoor',
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
                'category' => 'Indoor',
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
                'category' => 'Flowering',
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

        foreach ($productsData as $data) {
            $data['seller_id'] = $seller->id;
            $imageName = $data['image'];
            unset($data['image']);

            $product = Product::create($data);

            // Seed Image via Spatie MediaLibrary
            $imagePath = base_path('images/'.$imageName);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }

        // ── 4. Seed Journal Entries ──
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
                'content' => "Pemangkasan adalah inti dari seni bonsai. Melalui pemangkasan, kita tidak hanya mengontrol ukuran pohon tetapi juga mengarahkan energinya untuk membentuk estetika yang harmonis. Pemangkasan bonsai dibagi menjadi dua kategori utama: pemangkasan pemeliharaan dan pemangkasan struktural.\n\nPemangkasan pemeliharaan dilakukan sepanjang musim tumbuh untuk mempertahankan bentuk yang sudah ada. Ini melibatkan pemendekan tunas baru yang tumbuh terlalu panjang dan membuang daun-daun yang terlalu besar. Teknik ini merangsang pertumbuhan cabang yang lebih halus dan dedaunan yang lebih padat.\n\nPemangkasan struktural, di sisi lain, biasanya dilakukan pada akhir musim gugur atau awal musim semi saat pohon dalam keadaan dorman. Pemangkasan ini melibatkan pemotongan cabang besar yang tidak diinginkan untuk mendesain ulang siluet dasar pohon. Selalu gunakan gunting khusus bonsai yang tajam untuk meminimalkan kerusakan pada jaringan kayu dan membantu luka sembuh lebih cepat.",
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
