import "./stimulus_bootstrap.js";
import './styles/app.css';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import locale from '@fullcalendar/core/locales/fr';
import interaction from "@fullcalendar/interaction";

let calendar = null;

function initCalendar() {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        return;
    }

    calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interaction],
        initialView: 'timeGridWeek',
        locale,
        firstDay: 1,
        weekends: false,
        allDaySlot: false,
        slotMinTime: '08:00:00',
        slotMaxTime: '18:00:00',
        slotDuration: '01:00:00',
        height: 'auto',
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

        selectable: true,
        selectMirror: true,
        selectOverlap: false,
        
        selectAllow: function(selectInfo) {
            let duration = (selectInfo.end - selectInfo.start) / (1000 * 60);
            return duration <= 240;
        },

        select: function(info) {
            let startDate = info.startStr;
            let endDate = info.endStr;

            if (startDate && endDate) {
                window.location.href = `/intervention/new?start=${startDate}&end=${endDate}`;
            }
        },

        events: '/api/calendar',

        eventContent(arg) {
            const intervenants = arg.event.extendedProps.intervenants || [];
            let remotely = arg.event.extendedProps.remotely;
            const typeIntervention = arg.event.extendedProps.interventionType;

            if (remotely) {
                remotely = "<img src='/icones/Frame.png' width='16' height='16'>";
            } else {
                remotely = "";
            }

            return {
                html: `
                    <div class="pl-1 cursor-pointer">
                        <div class="flex gap-2">
                            ${arg.timeText}
                            ${remotely} 
                        </div>
                        <strong>${arg.event.title}</strong><br>
                        <strong>${intervenants.map(i => i.nom).join(', ')}</strong>
                        <div>
                            ${typeIntervention}
                        </div>
                    </div>
                `
            };
        },

        eventClick(arg) {
            let id = arg.event.id;
            let url = `/intervention/${id}/edit`;

            if (url) {
                window.location.href = url;
            }
        }
    });

    calendar.render();
}


document.addEventListener('DOMContentLoaded', initCalendar);
document.addEventListener('turbo:load', initCalendar);
document.addEventListener('turbo:render', initCalendar);