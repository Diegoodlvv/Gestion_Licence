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
    connect() {
        setTimeout(function () {
            const flashes = document.querySelectorAll(".flash-message");
            flashes.forEach(function (flash) {
                flash.classList.add("opacity-0", "-translate-x-full");
                flash.addEventListener("transitionend", function () {
                    flash.remove();
                });
            });
        }, 2000);
    }
}
