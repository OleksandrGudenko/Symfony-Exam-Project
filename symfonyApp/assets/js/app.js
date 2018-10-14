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
        let r =  {};
        for (let j = 0; j < question.length; j++) {
            r[question[j]] = answer[j];
        }

        let instance = document.getElementById("instanceId").value;

console.log("InstanceBoi", instance);
console.log("Q&A",question, answer);
console.log("Result",r);

        axios.post('/complete',{ r })//.then(response => window.location =  '/complete');

    }


    document.getElementById("submitButton").addEventListener("click", submitButtonClicked, false);

};

/*
axios.post('/complete', { params: {
         'user' : 1, 'question1' : answer1,'question2' : answer2}
            })
            .then(response => window.location.href = window.location.href);

*/