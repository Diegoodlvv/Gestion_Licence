import { Controller } from "@hotwired/stimulus";
import TomSelect from "tom-select";
import "../styles/tom-select.custom.css";

export default class extends Controller {
    connect() {
        this.control = new TomSelect(this.element, {
            plugins: {
                remove_button: {
                    title: "Supprimer",
                    className: "ml-1 text-gray-500 hover:text-gray-700 px-2",
                },
            },
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Sélectionnez...",
            maxOptions: null,
        });
    }

    disconnect() {
        if (this.control) this.control.destroy();
    }
}
