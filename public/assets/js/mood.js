const moodSlider = document.getElementById('moodSlider');
const moods = document.querySelectorAll('.mood');
const gifs = {
    'Very Sad': document.getElementById('very-sad'),
    'Sad': document.getElementById('sad'),
    'Netral': document.getElementById('netral'),
    'Happy': document.getElementById('happy'),
    'Very Happy': document.getElementById('very-happy')
};

let selectedMood = moods[2]; // Default mood: Neutral

function showGif(mood) {
    Object.values(gifs).forEach(gif => {
        gif.style.display = 'none';
    });
    gifs[mood.dataset.mood].style.display = 'block';
}

moodSlider.addEventListener('input', () => {
    const moodIndex = moodSlider.value;
    if (selectedMood) {
        selectedMood.classList.remove('selected');
    }
    selectedMood = moods[moodIndex];
    selectedMood.classList.add('selected');
    showGif(selectedMood);
});

document.getElementById('submitMood').addEventListener('click', () => {
    if (selectedMood) {
        const moodValue = selectedMood.dataset.mood;

        // Redirect to the selectedMood page
        localStorage.setItem('selectedMood', moodValue);  // Save the selected mood in localStorage
        window.location.href = 'selectedMood.php';
    } else {
        alert('Please select a mood before submitting.');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    Object.values(gifs).forEach(gif => {
        gif.style.display = 'none';
    });
    gifs['Netral'].style.display = 'block';
});
