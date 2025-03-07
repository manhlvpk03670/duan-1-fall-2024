document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('selected-rating');

    stars.forEach(star => {
        // Xử lý hover
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });

        // Xử lý click
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            highlightStars(rating);
            // Thêm class permanent để giữ màu sao sau khi click
            resetPermanentStars();
            highlightPermanentStars(rating);
        });
    });

    // Reset hover effect khi rời khỏi vùng sao
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        if (ratingInput.value) {
            highlightStars(ratingInput.value);
        } else {
            resetStars();
        }
    });

    function highlightStars(rating) {
        stars.forEach(star => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function resetStars() {
        stars.forEach(star => {
            if (!star.classList.contains('permanent')) {
                star.classList.remove('active');
            }
        });
    }

    function resetPermanentStars() {
        stars.forEach(star => {
            star.classList.remove('permanent');
        });
    }

    function highlightPermanentStars(rating) {
        stars.forEach(star => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.add('permanent');
            }
        });
    }
});
