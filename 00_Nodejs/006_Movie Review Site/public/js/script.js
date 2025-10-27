// Common utility functions
class MovieReviewApp {
    static async fetchReviews() {
        try {
            const response = await fetch('/api/reviews');
            return await response.json();
        } catch (error) {
            console.error('Error fetching reviews:', error);
            return [];
        }
    }

    static async deleteReview(reviewId) {
        if (confirm('Are you sure you want to delete this review?')) {
            try {
                const response = await fetch(`/api/reviews/${reviewId}`, {
                    method: 'DELETE'
                });
                
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error deleting review');
                }
            } catch (error) {
                console.error('Error deleting review:', error);
                alert('Error deleting review');
            }
        }
    }

    static formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    }

    static generateStars(rating) {
        return '★'.repeat(rating) + '☆'.repeat(5 - rating);
    }
}

// Page-specific functionality
if (document.getElementById('reviewForm')) {
    // Add review page
    document.getElementById('reviewForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const reviewData = {
            movieTitle: formData.get('movieTitle'),
            reviewerName: formData.get('reviewerName'),
            rating: formData.get('rating'),
            reviewText: formData.get('reviewText')
        };

        try {
            const response = await fetch('/api/reviews', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reviewData)
            });

            const result = await response.json();
            
            if (result.success) {
                alert('Review added successfully!');
                window.location.href = '/reviews';
            } else {
                alert('Error adding review: ' + result.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error adding review');
        }
    });
}

if (document.getElementById('reviewsList')) {
    // Reviews list page
    document.addEventListener('DOMContentLoaded', async () => {
        const reviews = await MovieReviewApp.fetchReviews();
        const reviewsList = document.getElementById('reviewsList');
        
        if (reviews.length === 0) {
            reviewsList.innerHTML = '<p>No reviews yet. Be the first to add one!</p>';
            return;
        }

        reviewsList.innerHTML = reviews.map(review => `
            <div class="review-card">
                <div class="review-header">
                    <h3 class="movie-title">${review.movieTitle}</h3>
                    <div class="rating" title="${MovieReviewApp.generateStars(review.rating)}">
                        ${review.rating}/5
                    </div>
                </div>
                <p class="reviewer">By: ${review.reviewerName}</p>
                <p class="review-text">${review.reviewText}</p>
                <div class="review-footer">
                    <span class="review-date">${MovieReviewApp.formatDate(review.date)}</span>
                    <div class="review-actions">
                        <button class="btn btn-danger" onclick="MovieReviewApp.deleteReview('${review.id}')">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    });
}