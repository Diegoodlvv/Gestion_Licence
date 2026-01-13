import { Controller } from '@hotwired/stimulus';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.css';

export default class extends Controller {
    connect() {
        // Initializes TomSelect on the element
        this.control = new TomSelect(this.element, {
            plugins: {
                remove_button: {
                    title: 'Supprimer',
                }
            },
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
            placeholder: 'Sélectionnez des modules...',
            maxOptions: null,
        });
    }

    disconnect() {
        if (this.control) {
            this.control.destroy();
        }
    }
}
