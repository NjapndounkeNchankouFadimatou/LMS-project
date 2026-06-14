/**
 * File: assets/js/publish.js
 * Purpose: Dynamically add new quiz question blocks to the publish form.
 */

document.getElementById('add-question-btn').addEventListener('click', function () {

    const container = document.getElementById('questions-container');
    const firstBlock = container.querySelector('.question-block');

    // Clone the first question block
    const newBlock = firstBlock.cloneNode(true);

    // Clear input values in the cloned block
    newBlock.querySelectorAll('input').forEach(function (input) {
        input.value = '';
    });

    container.appendChild(newBlock);
});