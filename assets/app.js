import "./stimulus_bootstrap.js";
import './styles/app.css';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener('DOMContentLoaded', () => {
    const interventionForm = document.querySelector('#intervention-form');

    if (!interventionForm) return;

    const coursePeriodSelect = document.getElementById('new_intervention_course_period');
    const startInput = document.getElementById('new_intervention_start_date');
    const endInput = document.getElementById('new_intervention_end_date');

    if (!coursePeriodSelect || !startInput || !endInput) return;

    const startPicker = flatpickr(startInput, {
        enableTime: true,
        dateFormat: "Y-d-m H:i",
        time_24hr: true,
        minTime: "08:30",
        maxTime: "17:30",
        allowInput: true,
    });

    const endPicker = flatpickr(endInput, {
        enableTime: true,
        dateFormat: "Y-d-m H:i",
        time_24hr: true,
        minTime: "08:30",
        maxTime: "17:30",
        allowInput: true
    });

   coursePeriodSelect.addEventListener('change', () => {
    const selectedOption = coursePeriodSelect.selectedOptions[0];
    if (!selectedOption) return;

    endPicker.clear;
    startPicker.clear;

    const start = selectedOption.dataset.start;
    const end = selectedOption.dataset.end;

    if (start && end) {
        startPicker.set('minDate', start);
        startPicker.set('maxDate', end);
        endPicker.set('minDate', start);
        endPicker.set('maxDate', end);

        if (startPicker.selectedDates[0] < new Date(start)) {
            startPicker.setDate(start, true, "Y-m-d H:i");
        }
        if (startPicker.selectedDates[0] > new Date(end)) {
            startPicker.setDate(end, true, "Y-m-d H:i");
        }

        if (endPicker.selectedDates[0] < new Date(start)) {
            endPicker.setDate(start, true, "Y-m-d H:i");
        }
        if (endPicker.selectedDates[0] > new Date(end)) {
            endPicker.setDate(end, true, "Y-m-d H:i");
        }
    }
});

});



