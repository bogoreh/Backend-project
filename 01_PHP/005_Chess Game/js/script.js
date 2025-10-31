document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for chess squares (for future enhancement)
    const squares = document.querySelectorAll('.square');
    squares.forEach(square => {
        square.addEventListener('click', function() {
            const piece = this.querySelector('.piece');
            if (piece && piece.textContent.trim() !== '') {
                console.log('Piece clicked:', piece.textContent);
                // Future: Implement piece selection and movement via clicking
            }
        });
    });

    // Form validation
    const moveForm = document.querySelector('.move-form');
    if (moveForm) {
        moveForm.addEventListener('submit', function(e) {
            const fromInput = this.querySelector('input[name="from"]');
            const toInput = this.querySelector('input[name="to"]');
            
            if (!isValidChessPosition(fromInput.value) || !isValidChessPosition(toInput.value)) {
                e.preventDefault();
                alert('Please enter valid chess positions (e.g., e2, e4)');
            }
        });
    }

    function isValidChessPosition(pos) {
        return /^[a-h][1-8]$/i.test(pos);
    }
});