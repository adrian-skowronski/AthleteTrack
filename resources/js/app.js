import "./bootstrap";
import 'bootstrap';


import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('input[required], select[required], textarea[required]')
    .forEach(field => {
        const label = field.closest('.form-group')?.querySelector('label');
        if (label) {
            label.innerHTML += ' <span style="color:red">*</span>';
        }
    });
