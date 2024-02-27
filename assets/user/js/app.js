import '../css/app.scss';

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import toastr from "toastr";

Routing.setRoutingData(routes);

const userBtn = document.getElementById('user-menu-button');
const userMenu = document.getElementById('user-menu-content');
if(userBtn){
    userBtn.addEventListener('mouseover', (e) => {
        userMenu.style.zIndex = "10";
        userMenu.style.opacity = "1";
    })
    userBtn.addEventListener('mouseleave', (e) => {
        userMenu.style.zIndex = "-10";
        userMenu.style.opacity = "0";
    })
}

let flashes = document.getElementById("flashes");
if(flashes){
    let data = JSON.parse(flashes.dataset.flashes);
    Object.entries(data).forEach(([key, value]) => {
        switch (key){
            case 'error':
                toastr.error(value, 'Erreur'); break;
            default:
                toastr.info(value); break;
        }
    })
}
