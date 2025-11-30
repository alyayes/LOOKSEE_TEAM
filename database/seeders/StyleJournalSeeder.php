<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StyleJournal;
use Carbon\Carbon;

class StyleJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journals = [
            [
                'title' => 'Tren Warna Musim Semi 2026: Palet Pastel yang Segar',
                'descr' => 'Menganalisis warna-warna pastel yang akan mendominasi fashion di musim semi tahun depan.',
                'content' => 'Musim semi 2026 akan dipenuhi dengan warna-warna lembut seperti mint green, lilac, dan baby blue. Padukan warna ini dengan aksen netral untuk tampilan yang ringan dan chic. Kain yang mengalir dan potongan longgar menjadi kunci.',
                'publication_date' => Carbon::parse('2026-03-01'), 
                'image' => 'spring_pastel_01.jpg',
            ],
            [
                'title' => 'Panduan Lengkap Memilih Sepatu untuk Setiap Acara',
                'descr' => 'Dari sneakers hingga stiletto, ketahui sepatu yang paling tepat untuk situasi formal, kasual, dan semi-formal.',
                'content' => 'Untuk acara formal, *stiletto* klasik atau *pump* tertutup adalah pilihan terbaik. Untuk kasual, tak ada yang mengalahkan kenyamanan *sneakers* putih bersih. Sementara itu, untuk *smart-casual*, *loafer* atau *mule* bisa menjadi penyeimbang sempurna. Selalu utamakan kenyamanan tanpa mengorbankan gaya.',
                'publication_date' => Carbon::parse('2026-02-15'), 
                'image' => 'shoe_guide_02.png',
            ],
            [
                'title' => 'Kebangkitan Fashion Era 90-an: Crop Top dan Baggy Jeans',
                'descr' => 'Mengapa gaya Y2K dan era 90-an kembali populer dan cara menata ulang gaya ini agar terlihat modern.',
                'content' => 'Gaya 90-an kembali dengan *twist* modern. *Baggy jeans* dipadukan dengan *crop top* yang lebih terstruktur. Tambahkan aksesori minimalis seperti kalung rantai tebal dan kacamata hitam kecil untuk sentuhan kontemporer. Kuncinya adalah tidak meniru gaya lama sepenuhnya, melainkan mengambil inspirasi kuncinya.',
                'publication_date' => Carbon::parse('2026-02-01'), 
                'image' => '90s_revival_03.webp',
            ],
            [
                'title' => '5 Jenis Jaket Pria yang Wajib Ada di Lemari',
                'descr' => 'Mulai dari *bomber* hingga *trench coat*, investasikan pada jaket serbaguna ini untuk tampilan yang berkelas.',
                'content' => 'Jaket *bomber* adalah pilihan kasual yang tak pernah salah. *Trench coat* menawarkan tampilan yang elegan dan profesional, sempurna untuk cuaca berangin. Sementara jaket kulit (leather jacket) memberikan kesan *edgy* yang tak lekang oleh waktu. Pastikan potongannya pas di bahu.',
                'publication_date' => Carbon::parse('2026-01-20'), 
                'image' => 'mens_jacket_04.jpg',
            ],
            [
                'title' => 'Aksesoris Emas atau Perak: Mana yang Cocok untuk Anda?',
                'descr' => 'Pelajari cara menentukan warna perhiasan yang paling sesuai dengan warna kulit (tone) Anda.',
                'content' => 'Aturan sederhananya: Jika pembuluh darah di pergelangan tangan Anda tampak kehijauan, Anda memiliki *warm tone* (cocok dengan emas). Jika tampak kebiruan, Anda memiliki *cool tone* (cocok dengan perak atau *white gold*). Jika campuran, Anda bisa cocok dengan keduanya (*neutral tone*).',
                'publication_date' => Carbon::parse('2026-01-10'), 
                'image' => 'jewelry_guide_05.jpg',
            ],
            [
                'title' => 'Menguasai Gaya Monokrom: Elegan dalam Satu Warna',
                'descr' => 'Cara memadukan satu warna dari kepala hingga kaki tanpa terlihat membosankan. Kuncinya adalah tekstur!',
                'content' => 'Gaya monokrom adalah teknik yang mudah namun memberi dampak besar. Pilih satu warna dasar (misalnya, beige atau navy). Gunakan berbagai *shade* dan *tint* dari warna tersebut. Yang terpenting, padukan berbagai tekstur kain (misalnya, rajutan tebal dengan sutra tipis) untuk menciptakan kedalaman.',
                'publication_date' => Carbon::parse('2026-01-05'), 
                'image' => 'monochrome_style_06.png',
            ],
            [
                'title' => 'Pakaian Ramah Lingkungan: Tren Fashion Berkelanjutan',
                'descr' => 'Mengenal konsep *sustainable fashion* dan merek-merek yang berkomitmen pada praktik etis.',
                'content' => 'Fashion berkelanjutan berfokus pada penggunaan bahan daur ulang, produksi yang etis, dan pengurangan limbah. Cari label yang menunjukkan bahan organik, *Tencel*, atau *Recycled Polyester*. Pikirkan kualitas jangka panjang daripada tren sesaat (fast fashion).',
                'publication_date' => Carbon::parse('2025-12-25'), 
                'image' => 'sustainable_fashion_07.jpg',
            ],
            [
                'title' => 'Rahasia Styling Baju Putih Polos',
                'descr' => 'Dari kaus hingga kemeja, mengubah pakaian putih polos menjadi *statement piece* yang serbaguna.',
                'content' => 'Kaus putih adalah kanvas kosong terbaik. Gunakan sebagai *layer* di bawah blazer atau kenakan dengan celana berpotongan lebar (wide-leg trousers). Kemeja putih bisa dinaikkan levelnya dengan membiarkan beberapa kancing terbuka dan menambahkan kalung berani. Setrika rapi adalah keharusan.',
                'publication_date' => Carbon::parse('2025-12-18'), 
                'image' => 'white_shirt_hacks_08.webp',
            ],
            [
                'title' => 'Padu Padan Motif Garis (Stripes) Tanpa Tabrakan',
                'descr' => 'Tips sederhana untuk menggabungkan motif garis horisontal dan vertikal dalam satu tampilan yang harmonis.',
                'content' => 'Menggabungkan motif garis membutuhkan keberanian dan keseimbangan. Pilih satu motif garis yang dominan (misalnya, garis tebal) dan pasangkan dengan motif garis sekunder yang lebih halus. Pastikan kedua motif memiliki setidaknya satu warna yang sama untuk menyatukan tampilan.',
                'publication_date' => Carbon::parse('2025-12-10'), 
                'image' => 'stripe_mixing_09.jpg',
            ],
            [
                'title' => 'Investasi Tas Tangan Klasik yang Tak Akan Pernah Usang',
                'descr' => 'Mengapa tas klasik dari merek ternama merupakan investasi cerdas di dunia fashion.',
                'content' => 'Tas tangan klasik seperti *Chanel 2.55* atau *Hermès Birkin* tidak hanya fungsional tetapi juga menjaga nilainya. Fokus pada warna netral seperti hitam, coklat, atau *tan*. Kualitas kulit yang prima akan memastikan tas Anda dapat diwariskan ke generasi berikutnya.',
                'publication_date' => Carbon::parse('2025-12-05'), 
                'image' => 'classic_bags_10.png',
            ],
            [
                'title' => 'Gaya Liburan Tropis: Pakaian Wajib ke Pantai',
                'descr' => 'Daftar pakaian yang harus Anda bawa untuk liburan ke pantai, dari dress linen hingga topi fedora.',
                'content' => 'Kunci gaya liburan tropis adalah kain yang ringan dan bernapas. *Linen dress* atau kemeja katun ringan adalah pilihan utama. Jangan lupakan *swimwear* berkualitas tinggi dan topi lebar untuk perlindungan dari matahari. Warna-warna cerah atau motif floral sangat disarankan.',
                'publication_date' => Carbon::parse('2025-11-28'), 
                'image' => 'tropical_style_11.jpg',
            ],
            [
                'title' => 'Mengenal Cutting Celana Wanita: Dari Skinnie hingga Wide-Leg',
                'descr' => 'Memahami berbagai potongan celana dan cara memilih yang paling menonjolkan bentuk tubuh Anda.',
                'content' => 'Celana *wide-leg* sangat populer karena memberikan siluet panjang dan elegan. *Straight-leg* adalah pilihan paling serbaguna. Hindari *cutting* yang terlalu ketat atau terlalu longgar jika Anda ingin tampilan yang lebih profesional. Pilih celana dengan *high-waist* untuk ilusi kaki yang lebih jenjang.',
                'publication_date' => Carbon::parse('2025-11-20'), 
                'image' => 'trouser_cuts_12.webp',
            ],
            [
                'title' => 'Paduan Jaket Denim dan Gaun: Tampilan Edgy & Feminin',
                'descr' => 'Membuat kombinasi sempurna antara jaket denim kasual dan gaun feminin untuk gaya sehari-hari yang unik.',
                'content' => 'Jaket denim adalah penyeimbang sempurna untuk gaun yang terlalu manis atau formal. Untuk gaun mini, pilih jaket denim berpotongan *cropped*. Untuk gaun *maxi* yang panjang, jaket denim *oversized* akan memberikan kontras yang stylish. Gulung sedikit lengan jaket untuk kesan santai.',
                'publication_date' => Carbon::parse('2025-11-15'), 
                'image' => 'denim_dress_13.jpg',
            ],
            [
                'title' => 'Perawatan Kain Sutra (Silk): Tips Mencuci dan Menyimpan',
                'descr' => 'Jaga keindahan dan kilau sutra Anda dengan panduan perawatan yang tepat. Hindari mesin cuci!',
                'content' => 'Sutra harus dicuci dengan tangan menggunakan deterjen lembut atau sampo bayi, dan air dingin. Jangan pernah memerasnya. Setelah dicuci, gantung di tempat yang teduh. Untuk menyetrika, gunakan pengaturan suhu paling rendah atau gunakan setrika uap. Simpan di tempat yang tidak lembap.',
                'publication_date' => Carbon::parse('2025-11-08'), 
                'image' => 'silk_care_14.png',
            ],
            [
                'title' => 'Sepatu Loafer: Gaya Klasik yang Kembali Populer',
                'descr' => 'Cara menata sepatu *loafer* (termasuk *penny* dan *horsebit*) untuk tampilan kantor dan kasual.',
                'content' => '*Loafer* adalah simbol *smart-casual*. Untuk kantor, padukan dengan celana *slack* berpotongan lurus dan blazer. Untuk tampilan akhir pekan, kenakan *loafer* tanpa kaus kaki dengan celana *cropped* atau jeans yang digulung. Pilih *loafer* kulit glossy untuk tampilan premium.',
                'publication_date' => Carbon::parse('2025-11-01'), 
                'image' => 'loafer_style_15.jpg',
            ],
        ];
        
        // Masukkan data ke database
        foreach ($journals as $journal) {
            StyleJournal::create($journal);
        }
    }
}
