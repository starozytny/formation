import React from "react";
import { createRoot } from "react-dom/client";
import { ProfilFormulaire } from "@userPages/Profil/ProfilForm";

let el = document.getElementById("profil_update");
if(el){
	createRoot(el).render(<ProfilFormulaire element={JSON.parse(el.dataset.obj)} />)
}
