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


window.onload=function() {

    function submitButtonClicked() {

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

};

function deleteButtonClicked(event)
{
    console.log(event);
    const examId = event.target.getAttribute('examId');
    console.log(examId);

    // send the HTTP REQ
    axios.delete('/courses/exams/delete/' + examId)
        .then(response => location.reload());
}

/*
axios.post('/complete', { params: {
         'user' : 1, 'question1' : answer1,'question2' : answer2}
            })
            .then(response => window.location.href = window.location.href);

*/