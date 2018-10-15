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
//var $ = require('jquery');

import axios from 'axios';


//Break Browser Navigation
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};


window.onload=function()
{



    //Delete
    function deleteButtonClicked(event)
    {
        const itemId = event.target.getAttribute('data-id');

        // send the HTTP REQ
        axios.delete('/todoList/deleteItem/' + itemId)
            .then(response => window.location.href = window.location.href);
    }
    let deleteButtons = document.querySelectorAll('.deleteButton');
    deleteButtons.forEach(button => button.addEventListener('click', deleteButtonClicked));
//End Delete

//Complete
    function completeButtonClicked(event)
    {
        const itemId = event.target.getAttribute('data-id');
        axios.post('/todoList/item/' + itemId + '/toggleIsDone')
            .then(response => window.location.href = window.location.href);
    }
    let isDoneButtons = document.querySelectorAll('.completeButton');
    isDoneButtons.forEach(button => button.addEventListener('click', completeButtonClicked));
//End Complete

//Publish
    function publishButtonClicked(event)
    {
        const studentId = event.target.getAttribute('student-id');
        const examId = event.target.getAttribute('exam-id');
        axios.post('/courses/exams/students/publish/' + examId + '/' + studentId)
            .then(response => location.reload());
    }
    let publishButtons = document.querySelectorAll('.publishButton');
    publishButtons.forEach(button => button.addEventListener('click', publishButtonClicked));
//End Publish


//PublishALl
    function publishAllButtonClicked(event)
    {
        let studentIds = [];
        const examId = event.target.getAttribute('exam-id');
        let inputs = document.getElementsByTagName("input");
        for (let i = 0; i < inputs.length; ++i) {
                studentIds.push(inputs[i].id);
        }
        axios.post('/courses/exams/students/publishAll/',{examId :examId, studentIds :studentIds})//.then(response => location.reload());
    }
    let publishAllButtons = document.querySelectorAll('.publishAllButton');
    publishAllButtons.forEach(button => button.addEventListener('click', publishAllButtonClicked));
//End PublishAll


//Submit
    function submitButtonClicked()
    {
        let question = [];
        let answer = [];
        let inputs = document.getElementsByTagName("input");
        for (let i = 0; i < inputs.length; ++i) {
            if (inputs[i].checked) {
                question.push(inputs[i].id);
                answer.push(inputs[i].value);

            }
        }
        let result =  {};
        for (let j = 0; j < question.length; j++) {
            result[question[j]] = answer[j];
        }

        let instance = document.getElementById("instanceId").value;

        axios.post('/courses/exams/complete',{ result: result, instance:instance }).then(response => window.location =  '/courses/exams/result/'+instance);

    }

    document.getElementById("submitButton").addEventListener("click", submitButtonClicked, false);
//End Submit
}

