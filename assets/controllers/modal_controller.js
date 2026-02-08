import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["button", "popup", "close"];

  connect() {
    this.buttonTarget.addEventListener("click", () => {
      this.popupTarget.classList.remove("hidden");
      this.popupTarget.classList.add("flex");
    });

    this.closeTarget.addEventListener("click", () => {
      this.hide();
    });

    this.popupTarget.addEventListener("click", (e) => {
      if (e.target === this.popupTarget) this.hide();
    });
  }

  hide() {
    this.popupTarget.classList.add("hidden");
    this.popupTarget.classList.remove("flex");
  }
}

