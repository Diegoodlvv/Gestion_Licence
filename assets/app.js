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
import locale from '@fullcalendar/core/locales/fr';

let calendarEl = document.getElementById('calendar');

if(calendarEl){

    let calendar = new Calendar(calendarEl, {
    plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
    initialView: 'timeGridWeek',
    locale,
    firstDay:1,
    weekends:false,
    allDaySlot:false,
    slotMinTime:'08:00:00',
    slotMaxTime:'19:00:00',
    slotDuration:'01:00:00',
    height:'auto',
    weekNumbers: true,
    weekNumberFormat: {week: 'long'},
    dayHeaderFormat: { weekday: 'short', day: 'numeric', omitCommas: false },   
    
    headerToolbar: {
        left: 'prev title',
        center: 'timeGridDay, timeGridWeek, dayGridMonth',
        right: 'next'
    },

    buttonText: {
        timeGridDay: 'Jour',
        timeGridWeek: 'Semaine',
        dayGridMonth: 'Mois'
    },

    events: '/api/calendar',

        

        eventContent(arg) {
            const intervenants = arg.event.extendedProps.intervenants || [];
            let remotely = arg.event.extendedProps.remotely;
            const typeIntervention = arg.event.extendedProps.interventionType;


            if(remotely){
                remotely = "<img src='/icones/Frame.png' width='16' height='16'>"
            } else{
                remotely = ""
            }

            return {
            html: `
                <div class="pl-1">
                    <div class="flex gap-2">
                        ${arg.timeText}
                        ${remotely} 
                    </div>
                    <strong class="text-lg">${arg.event.title}</strong><br>
                    <strong class="text-base">${intervenants.map(i => i.nom).join(', ')}</strong>
                    <div class="pb-2 mt-3">
                        ${typeIntervention}
                    </div>
                </div>
            `
            };
        }
    });

    calendar.render();

}



