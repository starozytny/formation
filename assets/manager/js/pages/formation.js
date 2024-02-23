import "../../css/pages/formation.scss"

import React from "react";
import { createRoot } from "react-dom/client";
import { Formations } from "@managerPages/Formations/Formations";
import { FormationFormulaire } from "@managerPages/Formations/FormationForm";

let el = document.getElementById("formations_list");
if(el){
	createRoot(el).render(<Formations />)
}

el = document.getElementById("formations_update");
if(el){
	createRoot(el).render(<FormationFormulaire context="update" element={JSON.parse(el.dataset.obj)} />)
}

el = document.getElementById("formations_create");
if(el){
	createRoot(el).render(<FormationFormulaire context="create" element={null} />)
}
