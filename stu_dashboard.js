function searchCourses() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    const courseContainers = document.querySelectorAll('.course-container');
    let resultsFound = false;

    courseContainers.forEach(container => {
        const title = container.getAttribute('data-title').toLowerCase();
        if (title.includes(searchQuery)) {
            container.style.display = '';
            resultsFound = true;
        } else {
            container.style.display = 'none';
        }
    });

    const noResultsMessage = document.getElementById('noResults');
    if (!resultsFound) {
        noResultsMessage.style.display = 'block';
    } else {
        noResultsMessage.style.display = 'none';
    }
}


function registerEvent(eventName) {
    alert(`Comming soon ${eventName}!`);
}

function showPopup() {
    document.getElementById('popupOverlay').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
}

function redirectToProfile() {
    window.location.hash = '#profile';
    showSection();
    closePopup();
}

document.addEventListener('DOMContentLoaded', (event) => {
    const userNameElement = document.getElementById('userName');
    userNameElement.textContent = 'Pranav';

    window.addEventListener('hashchange', showSection);
    showSection();

    // Simulate checking if the profile is incomplete
    const isProfileIncomplete = true; // This should be determined by your actual logic
    if (isProfileIncomplete) {
        setTimeout(showPopup, 10000); // Show popup after 10 seconds
    }
});
