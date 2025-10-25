class QuizApp {
    constructor() {
        this.questions = [];
        this.currentQuestionIndex = 0;
        this.userAnswers = [];
        this.startTime = null;
        this.timerInterval = null;
        
        this.initializeElements();
        this.loadQuestions();
        this.attachEventListeners();
    }

    initializeElements() {
        this.startScreen = document.getElementById('start-screen');
        this.quizScreen = document.getElementById('quiz-screen');
        this.resultsScreen = document.getElementById('results-screen');
        
        this.startBtn = document.getElementById('start-btn');
        this.prevBtn = document.getElementById('prev-btn');
        this.nextBtn = document.getElementById('next-btn');
        this.submitBtn = document.getElementById('submit-btn');
        this.restartBtn = document.getElementById('restart-btn');
        this.quizForm = document.getElementById('quiz-form');
        
        this.questionContainer = document.getElementById('question-container');
        this.currentQuestionEl = document.getElementById('current-question');
        this.totalQuestionsEl = document.getElementById('total-questions');
        this.timeEl = document.getElementById('time');
    }

    async loadQuestions() {
        try {
            const response = await fetch('/api/quiz/questions');
            this.questions = await response.json();
            this.totalQuestionsEl.textContent = this.questions.length;
            this.userAnswers = new Array(this.questions.length).fill(null);
        } catch (error) {
            console.error('Error loading questions:', error);
        }
    }

    attachEventListeners() {
        this.startBtn.addEventListener('click', () => this.startQuiz());
        this.prevBtn.addEventListener('click', () => this.previousQuestion());
        this.nextBtn.addEventListener('click', () => this.nextQuestion());
        this.submitBtn.addEventListener('click', (e) => this.submitQuiz(e));
        this.restartBtn.addEventListener('click', () => this.restartQuiz());
        this.quizForm.addEventListener('change', (e) => this.handleAnswer(e));
    }

    startQuiz() {
        this.startScreen.classList.remove('active');
        this.quizScreen.classList.add('active');
        this.startTime = Date.now();
        this.startTimer();
        this.showQuestion(0);
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            const elapsedTime = Math.floor((Date.now() - this.startTime) / 1000);
            this.timeEl.textContent = elapsedTime;
        }, 1000);
    }

    showQuestion(index) {
        this.currentQuestionIndex = index;
        const question = this.questions[index];
        
        this.currentQuestionEl.textContent = index + 1;
        
        let optionsHtml = question.options.map((option, optionIndex) => `
            <label class="option ${this.userAnswers[index] === option ? 'selected' : ''}">
                <input type="radio" name="answer" value="${option}" 
                       ${this.userAnswers[index] === option ? 'checked' : ''}>
                ${option}
            </label>
        `).join('');

        this.questionContainer.innerHTML = `
            <div class="question">
                <h3>${question.question}</h3>
                <div class="options">${optionsHtml}</div>
            </div>
        `;

        this.updateNavigation();
    }

    updateNavigation() {
        this.prevBtn.style.display = this.currentQuestionIndex === 0 ? 'none' : 'block';
        
        if (this.currentQuestionIndex === this.questions.length - 1) {
            this.nextBtn.style.display = 'none';
            this.submitBtn.style.display = 'block';
        } else {
            this.nextBtn.style.display = 'block';
            this.submitBtn.style.display = 'none';
        }
    }

    handleAnswer(event) {
        if (event.target.name === 'answer') {
            this.userAnswers[this.currentQuestionIndex] = event.target.value;
            
            // Update visual selection
            document.querySelectorAll('.option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.option').classList.add('selected');
        }
    }

    previousQuestion() {
        if (this.currentQuestionIndex > 0) {
            this.showQuestion(this.currentQuestionIndex - 1);
        }
    }

    nextQuestion() {
        if (this.currentQuestionIndex < this.questions.length - 1) {
            this.showQuestion(this.currentQuestionIndex + 1);
        }
    }

    async submitQuiz(e) {
        e.preventDefault();
        
        // Check if all questions are answered
        const unanswered = this.userAnswers.filter(answer => answer === null).length;
        if (unanswered > 0) {
            if (!confirm(`You have ${unanswered} unanswered questions. Submit anyway?`)) {
                return;
            }
        }

        clearInterval(this.timerInterval);

        try {
            const response = await fetch('/api/quiz/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    answers: this.userAnswers
                })
            });

            const results = await response.json();
            this.showResults(results);
        } catch (error) {
            console.error('Error submitting quiz:', error);
        }
    }

    showResults(results) {
        this.quizScreen.classList.remove('active');
        this.resultsScreen.classList.add('active');

        const scoreDisplay = document.getElementById('score-display');
        const resultsDetails = document.getElementById('results-details');

        scoreDisplay.innerHTML = `
            <div class="score-circle">${results.score}/${results.total}</div>
            <h3>Your Score: ${results.percentage}%</h3>
            <p>Time taken: ${this.timeEl.textContent} seconds</p>
        `;

        let resultsHtml = '<h3>Detailed Results:</h3>';
        results.results.forEach((result, index) => {
            resultsHtml += `
                <div class="result-item ${result.isCorrect ? 'correct' : 'incorrect'}">
                    <strong>Question ${index + 1}:</strong> ${result.question}<br>
                    <strong>Your answer:</strong> ${result.userAnswer || 'Not answered'}<br>
                    <strong>Correct answer:</strong> ${result.correctAnswer}<br>
                    <strong>Status:</strong> ${result.isCorrect ? '✓ Correct' : '✗ Incorrect'}
                </div>
            `;
        });

        resultsDetails.innerHTML = resultsHtml;
    }

    restartQuiz() {
        this.userAnswers = new Array(this.questions.length).fill(null);
        this.currentQuestionIndex = 0;
        this.resultsScreen.classList.remove('active');
        this.startScreen.classList.add('active');
        clearInterval(this.timerInterval);
        this.timeEl.textContent = '0';
    }
}

// Initialize the quiz when the page loads
document.addEventListener('DOMContentLoaded', () => {
    new QuizApp();
});