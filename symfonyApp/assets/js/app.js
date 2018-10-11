/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('../images/todo.ico');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');

import axios from 'axios';


function deleteButtonClicked(event)
{
    
    const itemId = event.target.getAttribute('data-id');

    // send the HTTP REQ
    axios.delete('/todoList/deleteItem/' + itemId)
        .then(response => window.location.href = window.location.href);
}

function completeButtonClicked(event)
{
    const itemId = event.target.getAttribute('data-id');
    axios.post('/todoList/item/' + itemId + '/toggleIsDone')
        .then(response => window.location.href = window.location.href);
}

let deleteButtons = document.querySelectorAll('.deleteButton');
deleteButtons.forEach(button => button.addEventListener('click', deleteButtonClicked));

let isDoneButtons = document.querySelectorAll('.completeButton');
isDoneButtons.forEach(button => button.addEventListener('click', completeButtonClicked));

