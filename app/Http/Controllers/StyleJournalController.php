<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StyleJournalController extends Controller
{
    private function format_tanggal($date_string) {
        if (empty($date_string)) return '';
        $timestamp = strtotime($date_string);
        $bulan_indonesia = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return date('d', $timestamp) . ' ' . $bulan_indonesia[(int)date('m', $timestamp)] . ' ' . date('Y', $timestamp);
    }

    private function getArticlesDummyData()
    {
        return [
            [
                'id_journal' => 12, 
                'title' => 'Panduan Pakaian Minimalis Greenflag', 
                'descr' => 'Bangun lemari pakaian yang serbaguna dan efisien dengan konsep minimalis.', 
                'content' => 'Konten lengkap artikel 5. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-16',
                'image' => 'journal12.jpg'
            ],
            [
                'id_journal' => 11, 
                'title' => 'Effortless Styling Tips for Every Occasion', 
                'descr' => 'Denim adalah pakaian abadi. Pelajari cara memadukannya untuk tampilan sehari-hari.', 
                'content' => 'Konten lengkap artikel 4. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-15',
                'image' => 'journal11.jpeg' 
            ],
            [
                'id_journal' => 10, 
                'title' => 'Footwear Trends to Watch This Year', 
                'descr' => 'Step up your shoe game with the latest trends in footwear that combine fashion and comfort.', 
                'content' => 'Dunia fashion terus berputar, dan begitu pula dengan tren alas kaki. Setiap tahun, ada gaya baru yang muncul dan kembali populer, menawarkan kita kesempatan untuk menyegarkan penampilan dan mengekspresikan diri. Tahun ini, kita melihat perpaduan menarik antara kenyamanan dan gaya, seperti yang sedikit diisyaratkan oleh penampilan dalam foto di atas.
                                Jika Anda ingin "meningkatkan permainan sepatu Anda" dan tetap up-to-date dengan gaya terkini, simak tren alas kaki yang patut Anda perhatikan di tahun ini.

                                1. Dominasi Sneakers Bergaya "Dad Shoe" dan Retro
                                Meskipun tren ini sudah ada beberapa waktu, sneakers dengan siluet besar atau yang sering disebut "dad shoe" dan model retro dari tahun 80-an atau 90-an tetap menjadi favorit. Mereka menawarkan kenyamanan maksimal dan sentuhan edgy pada setiap penampilan. Padukan dengan celana longgar seperti yang terlihat pada foto, atau dengan celana jogger dan bahkan rok midi untuk gaya yang kontras.

                                2. Loafers dan Mules yang Menawan
                                Untuk sentuhan yang lebih rapi namun tetap santai, loafers dan mules kembali mendominasi. Tersedia dalam berbagai bahan seperti kulit klasik, suede, atau bahkan faux fur, alas kaki ini sempurna untuk gaya smart casual hingga tampilan kantor yang santai. Mereka memberikan kesan dewasa dan profesional tanpa mengorbankan kenyamanan.

                                3. Sandal Chunky dan Platform
                                Seiring dengan meningkatnya minat pada kenyamanan, sandal dengan sol tebal atau chunky menjadi pilihan utama, terutama saat cuaca hangat. Dari flip-flop platform hingga sandal strappy dengan sol yang substansial, mereka menawarkan tambahan tinggi dan statement pada penampilan kasual Anda.

                                4. Sepatu Bot dengan Sol Tebal (Combat Boots & Chelsea Boots)
                                Untuk tampilan yang lebih berani dan tangguh, sepatu bot dengan sol tebal, seperti combat boots atau Chelsea boots dengan chunky sole, tetap menjadi pilihan yang kuat. Mereka tidak hanya praktis di cuaca dingin, tetapi juga menambahkan kesan cool pada gaya streetwear atau bahkan dipadukan dengan gaun feminin untuk kontras yang menarik.

                                5. Flat Klasik dengan Sentuhan Modern
                                Ballet flats dan mary janes kembali populer, namun dengan sentuhan yang lebih modern. Pikirkan bahan yang tidak biasa, detail hiasan, atau warna-warna cerah yang membuat flat klasik ini terasa segar dan relevan. Mereka adalah pilihan sempurna untuk gaya yang anggun namun tetap nyaman untuk aktivitas sehari-hari.

                                6. Aksesoris Kaki yang Berani
                                Tren alas kaki tahun ini juga mencakup penggunaan aksesori kaki yang lebih berani. Dari kaos kaki dengan motif menarik yang sengaja diperlihatkan (seperti pada foto di mana kaos kaki putih kontras dengan sepatu gelap yang mungkin dikenakan) hingga anklets atau rantai sepatu, detail-detail ini dapat meningkatkan keseluruhan penampilan sepatu Anda.

                                Bagaimana Memadukan Tren Ini ke Dalam Gaya Anda?

                                Kunci untuk menguasai tren alas kaki adalah memadukannya dengan sisa lemari pakaian Anda. Seperti pria dalam foto yang memadukan sneakers dengan celana chino longgar dan t-shirt kasual, penting untuk menemukan keseimbangan antara kenyamanan dan gaya pribadi.

                                Prioritaskan Kenyamanan: Tren sepatu tahun ini sangat mendukung kenyamanan. Pilihlah sepatu yang tidak hanya modis tetapi juga nyaman dipakai sepanjang hari.
                                Jangan Takut Bereksperimen: Cobalah padu padan yang berbeda. Sepatu chunky bisa terlihat bagus dengan gaun flowy, dan loafers bisa dipadukan dengan celana jogger untuk tampilan high-low.
                                Investasi pada Kualitas: Sepatu adalah investasi penting. Memilih alas kaki berkualitas tinggi akan memastikan mereka tahan lama dan tetap nyaman.
                                Dengan begitu banyak pilihan yang menarik, tahun ini adalah waktu yang tepat untuk memperbarui koleksi alas kaki Anda. Nikmati prosesnya dan temukan sepatu yang tidak hanya modis tetapi juga benar-benar mencerminkan gaya Anda!', 
                'publication_date' => '2025-10-14',
                'image' => 'journal10.jpeg'
            ],
            [
                'id_journal' => 9, 
                'title' => 'Style Guides for Every Occasion', 
                'descr' => 'Bangun lemari pakaian yang serbaguna dan efisien dengan konsep minimalis.', 
                'content' => 'Konten lengkap artikel 2. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-13',
                'image' => 'journal9.jpeg'
            ],
            [
                'id_journal' => 8, 
                'title' => 'The Power of Perfect Color Harmony', 
                'descr' => 'Master the technique of layering your clothes to stay warm without sacrificing style.', 
                'content' => 'Master the technique of layering your clothes to stay warm without sacrificing style.', 
                'publication_date' => '2025-10-12',
                'image' => 'journal8.jpeg'
            ],
            [
                'id_journal' => 7, 
                'title' => 'The Art of Layering: Stay Warm and Stylish', 
                'descr' => 'Master the technique of layering your clothes to stay warm without sacrificing style.', 
                'content' => 'Ketika suhu mulai menurun atau saat Anda berada di tempat dengan perubahan cuaca yang tidak menentu, seni layering atau melapisi pakaian menjadi sangat penting. Namun, layering bukan hanya soal menjaga tubuh tetap hangat; ini juga tentang menciptakan penampilan yang dinamis, menarik, dan tentu saja, stylish.

                                Seperti yang terlihat pada foto di atas, teknik layering yang tepat bisa mengubah tampilan sederhana menjadi sesuatu yang lebih berdimensi dan berkarakter. Mari kita selami lebih dalam bagaimana menguasai seni ini tanpa mengorbankan gaya Anda.

                                Mengapa Layering Begitu Penting?

                                Adaptasi Cuaca: Ini adalah fungsi utamanya. Dengan melapisi pakaian, Anda bisa dengan mudah menyesuaikan diri dengan perubahan suhu. Jika panas, Anda bisa melepas lapisan terluar; jika dingin, Anda bisa menambahkannya kembali.
                                Kedalaman dan Dimensi: Layering menambahkan tekstur dan dimensi pada penampilan Anda. Kombinasi kain dan siluet yang berbeda menciptakan tampilan yang lebih kaya dan menarik secara visual.
                                Ekspresi Gaya: Ini adalah cara yang fantastis untuk menunjukkan kepribadian Anda. Anda bisa memadukan berbagai item fashion yang mungkin tidak Anda kenakan secara terpisah.
                                Memaksimalkan Lemari Pakaian: Dengan layering, Anda bisa menggunakan item fashion Anda dalam berbagai kombinasi, sehingga memperluas pilihan busana Anda tanpa perlu membeli banyak pakaian baru.
                                Komponen Kunci Layering yang Berhasil:

                                Lapisan Dasar (Base Layer):
                                Ini adalah pakaian yang paling dekat dengan kulit Anda. Pilih bahan yang nyaman dan menyerap keringat. Contohnya adalah kaos katun polos, thermal underwear, atau turtleneck tipis. Warna netral seperti putih, hitam, atau abu-abu seringkali menjadi pilihan terbaik karena mudah dipadukan.

                                Lapisan Tengah (Mid Layer):
                                Lapisan ini berfungsi sebagai isolasi dan penambah kehangatan. Ini bisa berupa kemeja, sweater, hoodie, atau rompi. Anda bisa bereksperimen dengan tekstur dan pola di lapisan ini untuk menambah minat visual. Dalam foto, t-shirt putih mungkin berfungsi sebagai lapisan dasar di bawah jaket.

                                Lapisan Terluar (Outer Layer):
                                Ini adalah "bintang" dari layering Anda dan seringkali menjadi statement piece. Jaket, mantel, blazer, atau cardigan tebal adalah pilihan yang umum. Pilihlah yang sesuai dengan gaya dan kebutuhan cuaca Anda. Pria dalam foto mengenakan jaket bomber tebal yang memberikan siluet modern dan rapi.

                                Tips Menguasai Seni Layering:

                                Mulai dari yang Paling Tipis ke Paling Tebal: Ini adalah aturan emas. Lapisan dasar harus yang paling tipis, diikuti oleh lapisan yang semakin tebal.
                                Perhatikan Proporsi: Pastikan setiap lapisan terlihat bagus saat dikenakan sendiri, dan juga saat dipadukan. Hindari membuat tubuh terlihat terlalu bervolume. Misalnya, jaket cropped seperti yang dikenakan dalam foto bisa sangat efektif untuk menciptakan proporsi yang baik.
                                Bermain dengan Tekstur dan Warna: Jangan takut memadukan berbagai tekstur (misalnya, katun dengan wol atau denim) dan warna. Meskipun warna netral adalah pilihan aman, sentuhan warna pop dari salah satu lapisan bisa sangat menarik.
                                Perhatikan Detail: Kerah kemeja yang sedikit terlihat di bawah sweater, atau lengan kaos yang menyembul dari bawah jaket, bisa menambah detail menarik pada penampilan Anda.
                                Gunakan Aksesori: Syal, topi, atau sarung tangan tidak hanya menambah kehangatan tetapi juga melengkapi tampilan layering Anda.
                                Contoh Gaya Layering ala Pria di Foto:

                                Pria dalam foto menunjukkan contoh layering yang efektif untuk tampilan modern dan maskulin:

                                Lapisan Dasar: Kemungkinan t-shirt polos berwarna terang (putih) yang memberikan kesan bersih.
                                Lapisan Terluar: Jaket bomber hitam tebal yang memberikan volume dan karakter pada bahu.
                                Celana: Celana berwarna terang (krem atau abu-abu muda) yang memberikan kontras, mungkin chino atau celana kasual lainnya.
                                Aksesori: Kalung dan gelang menambahkan sentuhan personal dan modern.
                                Menguasai seni layering adalah tentang menemukan keseimbangan antara fungsionalitas dan estetika. Dengan sedikit latihan dan eksperimen, Anda akan menemukan cara-cara baru yang kreatif untuk tetap hangat dan tampil stylish di setiap kesempatan. Selamat mencoba!', 
                'publication_date' => '2025-10-18',
                'image' => 'journal7.jpg' 
            ],
            [
                'id_journal' => 6, 
                'title' => 'Tren Warna Musim Dingin 2025', 
                'descr' => 'Dari hijau zamrud hingga merah marun, temukan palet warna yang akan mendominasi musim dingin tahun ini.', 
                'content' => 'Konten lengkap artikel 6. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-17',
                'image' => 'journal6.jpg'
            ],
            [
                'id_journal' => 5, 
                'title' => 'Accessorizing 101: How to Enhance Any Outfit', 
                'descr' => 'Bangun lemari pakaian yang serbaguna dan efisien dengan konsep minimalis. Lebih sedikit, lebih baik!', 
                'content' => 'Konten lengkap artikel 5. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-16',
                'image' => 'journal5.jpeg'
            ],
            [
                'id_journal' => 4, 
                'title' => 'Wardrobe Hacks for Any Event', 
                'descr' => 'From brunch to dinner, these wardrobe hacks ensure perfect outfits for any occasion..', 
                'content' => 'Konten lengkap artikel 4. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-15',
                'image' => 'journal4.jpeg' 
            ],
            [
                'id_journal' => 3, 
                'title' => 'Tren Warna Musim Dingin 2025', 
                'descr' => 'Dari hijau zamrud hingga merah marun, temukan palet warna yang akan mendominasi musim dingin tahun ini.', 
                'content' => 'Konten lengkap artikel 3. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-14',
                'image' => 'journal3.jpg'
            ],
            [
                'id_journal' => 2, 
                'title' => 'Essential Wardrobe Pieces for Year-Round Style', 
                'descr' => 'Bangun lemari pakaian yang serbaguna dan efisien dengan konsep minimalis. Lebih sedikit, lebih baik!', 
                'content' => 'Konten lengkap artikel 2. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-13',
                'image' => 'journal2.jpeg'
            ],
            [
                'id_journal' => 1, 
                'title' => 'Sustainable Fashion Choices You Can Make Today', 
                'descr' => 'Embrace eco-friendly fashion with these sustainable clothing tips and tricks.', 
                'content' => 'Konten lengkap artikel 1. Anda dapat mengisi konten ini lebih panjang untuk halaman detail.', 
                'publication_date' => '2025-10-12',
                'image' => 'journal1.jpeg'
            ]
        ];
    }

    public function index()
    {
        $articles_data = $this->getArticlesDummyData();

        $articles_data = collect($articles_data)->sortByDesc('id_journal')->values()->all();
        $articles_per_page = 6;
        $total_articles = count($articles_data);
        $total_pages = ceil($total_articles / $articles_per_page);
        return view('stylejournal.index', compact('articles_data', 'articles_per_page', 'total_pages'));
    }
    
    // Menampilkan detail artikel 
    public function show($id)
    {
        $articles_data = $this->getArticlesDummyData();
        $article = collect($articles_data)->firstWhere('id_journal', (int)$id);

        if (!$article) {
            // Jika artikel tidak ditemukan
            abort(404);
        }

        $article['formatted_date'] = $this->format_tanggal($article['publication_date']);

        return view('stylejournal.show', compact('article'));
    }
}