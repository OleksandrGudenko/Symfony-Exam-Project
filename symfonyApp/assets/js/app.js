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

window.onload=function(){

//Publish
    function addQuestionButtonClicked(event)
    {
        const questionId = event.target.getAttribute('question-id');
        const examId = event.target.getAttribute('exam-id');
        axios.post('/courses/exams/edit/' + examId + '/' + questionId)
            .then(response => location.reload());
    }
    let addQuestionButtons = document.querySelectorAll('.addQuestionButton');
    addQuestionButtons.forEach(button => button.addEventListener('click', addQuestionButtonClicked));
//End Publish

//Add Question

    function publishButtonClicked(event)
    {
        const studentId = event.target.getAttribute('student-id');
        const examId = event.target.getAttribute('exam-id');
        axios.post('/courses/exams/students/publish/' + examId + '/' + studentId)
            .then(response => location.reload());
    }
    let publishButtons = document.querySelectorAll('.publishButton');
    publishButtons.forEach(button => button.addEventListener('click', publishButtonClicked));

//End Add Question

//PublishAll
    function publishAllButtonClicked(event)
    {
        let studentIds = [];
        const examId = event.target.getAttribute('exam-id');
        let inputs = document.getElementsByTagName("input");
        for (let i = 0; i < inputs.length; ++i) {
                studentIds.push(inputs[i].id);
        }
        axios.post('/courses/exams/students/publishAll/',{examId :examId, studentIds :studentIds}).then(response => location.reload());
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

        axios.post('/complete',{ result: result, instance:instance }).then(response => window.location =  '/courses/exams/result/'+instance);

    }

    document.getElementById("submitButton").addEventListener("click", submitButtonClicked, false);
//End Submit

}

