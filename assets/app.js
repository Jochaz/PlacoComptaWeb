/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import './styles/app.scss';
//import 'bootswatch/dist/Minty/bootstrap.min.css'; // Added this :boom:
import './styles/bootstrap.min.css'; // Added this :boom:

import { Tooltip, Toast, Popover } from 'bootstrap';


$(document).ready(function() {
    // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        format: 'dd-mm-yyyy'
    });
});
