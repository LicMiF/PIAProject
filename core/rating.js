    const userRatingElement = document.getElementById('userRating');
    const averageRatingElement = document.getElementById('averageRating');

    const maxRating = 5;

    // Event listeners for user rating
    userRatingElement.addEventListener('mouseover', (event) => {
      const starRating = event.target.getAttribute('data-rating');
      if (starRating) {
        const hoveredRating = parseInt(starRating, 10);
        updateHoveredStars(hoveredRating);
      }
    });

    userRatingElement.addEventListener('mouseout', () => {
      updateHoveredStars(userRating);
    });

    userRatingElement.addEventListener('click', (event) => {
    const starRating = event.target.getAttribute('data-rating');
    if (starRating) {
      userRating = parseInt(starRating, 10);
      updateRating();

      // Call backend to update database with new rating
      fetch('updateRating.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ratedId: ratedId, criticId:criticId ,rating: userRating }), // Send user ID and rating to server
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            console.log('Rating updated successfully.');
            // Retrieve average rating from the server
            fetch(`getAverageRating.php`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ratedId: ratedId}), // Send user ID and rating to server
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  // Update average rating display
                  averageRatingElement.innerHTML = `<div class="average-rating-float"> ${parseFloat(data.averageRating).toFixed(1)} </div>${generateStarRating(parseFloat(data.averageRating))}`;
                } else {
                  console.error('Failed to retrieve average rating.');
                }
              })
              .catch(error => console.error('Error:', error));
          } else {
            console.error('Failed to update rating.');
          }
        })
        .catch(error => console.error('Error:', error));
    }
  });

    function updateHoveredStars(hoveredRating) {
      for (let i = 1; i <= maxRating; i++) {
        const starElement = document.querySelector(`#userRating [data-rating="${i}"]`);
        if (starElement) {
          starElement.classList.toggle('active', i <= hoveredRating);
        }
      }
    }

    // Update rating and display
    function updateRating() {
      // Update user rating display
      userRatingElement.setAttribute('data-rating', userRating);
      updateHoveredStars(userRating);
    }

    // Calculate average rating
    function calculateAverage(ratings) {
      const totalRating = ratings.reduce((sum, rating) => sum + rating, 0);
      return totalRating / ratings.length || 0; // Avoid division by zero
    }

    // Generate star rating HTML based on the rating
    function generateStarRating(rating) {
      const fullStars = '&#9733;'.repeat(Math.floor(rating));
      const halfStar = (rating % 1 !== 0) ? '&#9733;' : '';
      const emptyStars = '&#9734;'.repeat(Math.floor(maxRating - rating));
      return `${fullStars}${halfStar}${emptyStars}`;
    }

    // Initial rating update
    updateRating();