import "./stimulus_bootstrap.js";
import './styles/app.css';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

let calendarEl = document.getElementById('calendar');
let calendar = new Calendar(calendarEl, {
  plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
  initialView: 'timeGridWeek',
  locale:'fr',
  firstDay:1,
  
  allDaySlot:false,
  slotMinTime:'08:00:00',
  slotMaxTime:'19:00:00',
  slotDuration:'01:00:00',
  height:'auto',
    headerToolbar: {
    left: 'prev',
    center: 'timeGridDay, timeGridWeek, dayGridMonth',
    right: 'next'
  },
  buttonText: {
    timeGridDay: 'Jour',
    timeGridWeek: 'Semaine',
    dayGridMonth: 'Mois'
  }
});
calendar.render();


