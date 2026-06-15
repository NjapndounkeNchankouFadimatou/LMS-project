/**
 * File: assets/js/publish.js
 * Purpose: Dynamically add new quiz question blocks to the publish form.
 */

let questionIndex = 1; // first block is index 0

document.getElementById('add-question-btn').addEventListener('click', function () {

    const container = document.getElementById('questions-container');
    const firstBlock = container.querySelector('.question-block');

    // Clone the first question block
    const newBlock = firstBlock.cloneNode(true);

    // Clear text input values
    newBlock.querySelectorAll('input[type="text"]').forEach(function (input) {
        input.value = '';
    });

    // Update radio button names and uncheck them
    newBlock.querySelectorAll('input[type="radio"]').forEach(function (radio) {
        radio.name = 'correct_' + questionIndex;
        radio.checked = false;
    });

    container.appendChild(newBlock);
    questionIndex++;
});