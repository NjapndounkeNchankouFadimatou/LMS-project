/**
 * File: assets/js/quiz.js
 * Purpose: Handle quiz submission via AJAX (fetch API).
 */

document.getElementById('submit-quiz-btn').addEventListener('click', function () {

    const form = document.getElementById('quiz-form');
    const formData = new FormData(form);

    // Build the answers object: { question_id: selected_answer }
    const answers = {};
    const radios = form.querySelectorAll('input[type="radio"]:checked');

    radios.forEach(function (radio) {
        const questionId = radio.name.split('_')[1];
        answers[questionId] = radio.value;
    });

    const courseId = formData.get('course_id');

    fetch('/LMS-project/ajax/submit_quiz.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'course_id=' + encodeURIComponent(courseId) + 
              '&answers=' + encodeURIComponent(JSON.stringify(answers))
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('quiz-result');
        const actionsDiv = document.getElementById('quiz-actions');

        if (data.success) {
            let html = '<p class="quiz-score">Your score: ' + data.score + '% (' + 
                       data.correct_count + '/' + data.total_questions + ' correct)</p>';

            if (data.certificate_earned) {
                html += '<p class="certificate-earned">Congratulations! You earned a certificate for completing this module.</p>';
            }

            resultDiv.innerHTML = html;
            actionsDiv.style.display = 'flex';
        } else {
            resultDiv.innerHTML = '<p class="status-failed">' + data.message + '</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

});