const express = require('express');
const router = express.Router();
const questions = require('../data/questions.json');

// Get all questions
router.get('/questions', (req, res) => {
    res.json(questions);
});

// Submit quiz and calculate score
router.post('/submit', (req, res) => {
    const userAnswers = req.body.answers;
    let score = 0;
    const results = [];

    questions.forEach((question, index) => {
        const isCorrect = userAnswers[index] === question.correctAnswer;
        if (isCorrect) {
            score++;
        }
        
        results.push({
            question: question.question,
            userAnswer: userAnswers[index],
            correctAnswer: question.correctAnswer,
            isCorrect: isCorrect
        });
    });

    res.json({
        score: score,
        total: questions.length,
        percentage: Math.round((score / questions.length) * 100),
        results: results
    });
});

module.exports = router;