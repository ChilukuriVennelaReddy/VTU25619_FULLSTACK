document.addEventListener('DOMContentLoaded', () => {
    // Auth Tabs Logic
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (loginTab && registerTab) {
        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
        });
    }

    // Seat Selection Logic
    const seats = document.querySelectorAll('.seat:not(.booked)');
    const selectedSeatsInput = document.getElementById('selected-seats-input');
    const totalPriceEl = document.getElementById('total-price');
    const seatPriceEl = document.getElementById('base-price');
    const selectedSeatsList = document.getElementById('selected-seats-list');
    
    if (seats.length > 0 && seatPriceEl) {
        const ticketPrice = parseFloat(seatPriceEl.value);
        let selectedSeats = [];

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                const seatNo = seat.dataset.seat;
                
                if (seat.classList.contains('selected')) {
                    seat.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(s => s !== seatNo);
                } else {
                    seat.classList.add('selected');
                    selectedSeats.push(seatNo);
                }

                updateBookingSummary();
            });
        });

        function updateBookingSummary() {
            if (selectedSeatsInput) {
                selectedSeatsInput.value = selectedSeats.join(',');
            }
            if (selectedSeatsList) {
                selectedSeatsList.innerText = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
            }
            if (totalPriceEl) {
                const total = selectedSeats.length * ticketPrice;
                totalPriceEl.innerText = '$' + total.toFixed(2);
            }
            
            const submitBtn = document.getElementById('book-btn');
            if (submitBtn) {
                if (selectedSeats.length === 0) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                }
            }
        }
    }
});
