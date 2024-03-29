import "../../css/pages/worker.scss"

import React from "react";
import { createRoot } from "react-dom/client";
import { WorkerFormulaire } from "@userPages/Worker/WorkerForm";
import { Workers } from "@userPages/Worker/Workers";

let el = document.getElementById("worker_create");
if(el){
	createRoot(el).render(<WorkerFormulaire context="create" element={null} />)
}

el = document.getElementById("worker_update");
if(el){
	createRoot(el).render(<WorkerFormulaire context="update" element={JSON.parse(el.dataset.obj)} />)
}

el = document.getElementById("worker_list");
if(el){
	createRoot(el).render(<Workers />)
}
