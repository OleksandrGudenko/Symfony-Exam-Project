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

        let question1 = document.getElementsByName('1'),
            answer1 = '', i = 0;

        for( ; i < question1.length; i++ )
        {
            if( question1[i].checked ) {
                answer1 = question1[i].value;
                break;
            }
        }

        let question2 = document.getElementsByName('2'),
            answer2 = '', j = 0;

        for( ; j < question2.length; j++ )
        {
            if( question2[j].checked ) {
                answer2 = question2[j].value;
                break;
            }

        }

        let question3 = document.getElementsByName('3'),
            answer3 = '', k = 0;

        for( ; k < question3.length; k++ )
        {
            if( question3[k].checked ) {
                answer3 = question2[k].value;
                break;
            }

        }


        axios({
            method: 'post',
            url: '/complete',
            data: {
                q1: answer1,
                q2: answer2,
                q3: answer3
            }
        }).then(response => window.location.href = '/complete');

    }


    document.getElementById("submitButton").addEventListener("click", submitButtonClicked, false);

};

/*
axios.post('/complete', { params: {
         'user' : 1, 'question1' : answer1,'question2' : answer2}
            })
            .then(response => window.location.href = window.location.href);

*/