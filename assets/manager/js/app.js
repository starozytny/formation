import '../css/app.scss';

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React from "react";
import { createRoot } from "react-dom/client";
import { Notifications } from "@commonComponents/Elements/Notifications";

Routing.setRoutingData(routes);

let notifs = document.getElementById("notifs_list");
if(notifs){
    createRoot(notifs).render(<Notifications />)
}

menu();

function menu() {
    let btn = document.querySelector('#nav-mobile');
    if(btn){
        let body = document.querySelector("body");
        btn.addEventListener('click', function () {
            let content = document.querySelector('#nav-content');
            let svgs = document.querySelectorAll('#nav-mobile > svg');

            svgs.forEach(svg => {
                if(svg.classList.contains('hidden')){
                    svg.classList.remove('hidden');
                    svg.classList.add('flex');
                }else{
                    svg.classList.remove('flex');
                    svg.classList.add('hidden');
                }
            })

            if(content.classList.contains('hidden')){
                content.classList.remove('hidden');
                content.classList.add('flex');
                content.style.overflow = "auto";
                body.style.overflow = "hidden";
            }else{
                content.classList.remove('flex');
                content.classList.add('hidden');
                content.style.overflow = "hidden";
                body.style.overflow = "auto";
            }
        })
    }
}
