import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['type'];

    connect() {
        this.instructorsSelectElement = document.querySelector('[data-controller="tom-select"]');

        this.toggle();
        this.typeTarget.addEventListener('change', () => this.toggle());
    }

    toggle() {
        const tom = this.instructorsSelectElement?.tomselect;
      
        if(tom){
            const selectedText = this.typeTarget.options[this.typeTarget.selectedIndex]?.text;

            if (selectedText === 'Autonomie') {   
                tom.clear();
                tom.disable();  
            } else {
                tom.enable();   
            }
        }

    
    }
}
