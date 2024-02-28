import React from "react";
import { createRoot } from "react-dom/client";
import { Preregistration } from "@userPages/Formation/Preregistration";

let el = document.getElementById("formations_preregistration");
if(el){
	createRoot(el).render(<Preregistration formation={JSON.parse(el.dataset.formation)}
										   workers={JSON.parse(el.dataset.workers)}
										   registered={JSON.parse(el.dataset.participants)} />)
}
