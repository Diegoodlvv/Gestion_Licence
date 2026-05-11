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
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButton = document.getElementById("deleteButton");
            const deletePopUp = document.getElementById("deletePopUp");
            const closePopUp = document.getElementById("closePopUp");

            // Open
            deleteButton.addEventListener("click", function () {
                deletePopUp.classList.remove("hidden");
                deletePopUp.classList.add("flex");
            });

            // Close
            function hidePopUp() {
                deletePopUp.classList.add("hidden");
                deletePopUp.classList.remove("flex");
            }

            closePopUp.addEventListener("click", hidePopUp);

            // Close clique côté
            deletePopUp.addEventListener("click", function (e) {
                if (e.target === deletePopUp) {
                    hidePopUp();
                }
            });
        });
    }
}
