import { Controller } from "@hotwired/stimulus";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { French } from "flatpickr/dist/l10n/fr.js";

export default class extends Controller {
    // definition des valeur configurables depuis le HTML
    static values = {
        minDate: String,
        maxDate: String,
        minTime: String,
        maxTime: String,
        disableMonths: Array,
    };

    connect() {
        // configuration de base de Flatpickr
        const config = {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minuteIncrement: 30, // 0 et 30 minutes
            locale: French,
            allowInput: true,
        };

        // ajoute une date minimale si elle est définie dans le HTML
        if (this.hasMinDateValue) {
            config.minDate = this.minDateValue;
        }

        if (this.hasMaxDateValue) {
            config.maxDate = this.maxDateValue;
        }

        if (this.hasMinTimeValue) {
            config.minTime = this.minTimeValue;
        }

        if (this.hasMaxTimeValue) {
            config.maxTime = this.maxTimeValue;
        }

        // desactivation de certains mois selectionnes
        if (this.hasDisableMonthsValue && this.disableMonthsValue.length > 0) {
            config.disable = [
                (date) => {
                    // Les mois JS sont 0-11, mais les notres sont 1-12. donc date.getMonth() + 1
                    // check et verifie si le mois est dans disableMonthsValue, et donc ne l'affiche aps
                    return this.disableMonthsValue.includes(
                        date.getMonth() + 1,
                    );
                },
            ];
        }

        // initialisation fe Flatpiskr
        flatpickr(this.element, config);
    }
}

// contraintes
// 'hours' => [8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
// 'minutes' => [0, 30],
// 'months' => [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12],
// 'years' => [$year, $year - 1, $year + 1],
