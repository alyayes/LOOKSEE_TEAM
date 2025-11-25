{{-- resources/views/home/mood.blade.php --}}

<section class="mood-selection" id="mood">
    <div class="mood-slider-section">
        <div class="mood-slider-container">
            <h2>Let's Find an Outfit That Suits With Your Mood!</h2><br>

            {{-- GIF/AVATAR DISPLAY --}}
            <div id="mood-gif-display">
                <div id="very-happy" style="display:none;">
                    <img src="{{ asset('assets/gif/sangatSenang.gif') }}" width="150" height="150" alt="Very Happy">
                </div>
                <div id="happy" style="display:none;">
                    <img src="{{ asset('assets/gif/senang.gif') }}" width="150" height="150" alt="Happy">
                </div>
                <div id="netral">
                    <img src="{{ asset('assets/gif/biasa.gif') }}" width="150" height="150" alt="Netral">
                </div>
                <div id="sad" style="display:none;">
                    <img src="{{ asset('assets/gif/sedih.gif') }}" width="150" height="150" alt="Sad">
                </div>
                <div id="very-sad" style="display:none;">
                    <img src="{{ asset('assets/gif/sangatSedih.gif') }}" width="150" height="150" alt="Very Sad">
                </div>
            </div>

            {{-- SLIDER INPUT DAN LABELS --}}
            <div class="slider-wrapper">
                <div class="slider">
                    <input type="range" min="0" max="4" value="2" class="slider-input"
                        id="moodSlider">
                    <div class="mood-labels">
                        <div class="mood very-sad" data-mood="Very Sad">😢</div>
                        <div class="mood sad" data-mood="Sad">☹️</div>
                        <div class="mood netral" data-mood="Netral">😐</div>
                        <div class="mood happy" data-mood="Happy">😊</div>
                        <div class="mood very-happy" data-mood="Very Happy">😁</div>
                    </div>
                </div>
            </div>

            <button type="button" class="main-btn" id="submitVisualMood">Is It Your Mood?</button>
        </div>
    </div>
</section>

{{-- =========================================== --}}
{{-- C. JAVASCRIPT SLIDER --}}
{{-- =========================================== --}}
@section('footer_scripts')
    @parent

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moodSlider = document.getElementById('moodSlider');
            const submitBtn = document.getElementById('submitVisualMood');

            const moods = ['very-sad', 'sad', 'netral', 'happy', 'very-happy'];

            // Saat slider digeser, ubah GIF
            moodSlider.addEventListener('input', function() {
                const index = parseInt(moodSlider.value, 10);

                // Sembunyikan semua GIF dulu
                moods.forEach(m => {
                    document.getElementById(m).style.display = 'none';
                });

                // Tampilkan GIF sesuai slider
                const selectedMood = moods[index];
                document.getElementById(selectedMood).style.display = 'block';
            });

            // Saat tombol diklik, arahkan ke halaman produk berdasarkan mood
            submitBtn.addEventListener('click', function() {
                const index = parseInt(moodSlider.value, 10);
                const moodElement = document.querySelectorAll('.mood-labels .mood')[index];
                const moodText = moodElement ? moodElement.dataset.mood : 'Netral';
                const gender = ''; // Bisa diisi nanti jika ada pilihan gender

                const url = "{{ route('mood.products') }}" + "?mood=" + encodeURIComponent(moodText) +
                    "&gender=" + encodeURIComponent(gender);
                window.location.href = url;
            });
        });
    </script>
@endsection
