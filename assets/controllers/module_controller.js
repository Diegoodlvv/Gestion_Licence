import { Controller } from "@hotwired/stimulus";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ["children", "chevron"]; // correspond aux references HTML dans le twig
    // associe directemnet children = childrenTarget

    connect() {}

    toggle() {
        const isHidden = this.childrenTarget.classList.contains("hidden");

        if (isHidden) {
            // affiche les enfants
            this.childrenTarget.classList.remove("hidden");
            // tourne le chevron
            this.chevronTarget.style.transform = "rotate(90deg)";
        } else {
            // cache le children
            this.childrenTarget.classList.add("hidden");
            // remet le chevron
            this.chevronTarget.style.transform = "rotate(0deg)";
        }
    }
}
