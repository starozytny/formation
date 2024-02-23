import "../../css/pages/settings.scss"

import React from "react";
import { createRoot } from "react-dom/client";
import { Settings } from "@managerPages/Settings/Settings";
import { TaxFormulaire } from "@managerPages/Settings/Taxs/TaxForm";

let el = document.getElementById("settings_update");
if(el){
	createRoot(el).render(<Settings {...el.dataset} />)
}

el = document.getElementById("taxs_update");
if(el){
	createRoot(el).render(<TaxFormulaire context="update" element={JSON.parse(el.dataset.obj)} />)
}

el = document.getElementById("taxs_create");
if(el){
	createRoot(el).render(<TaxFormulaire context="create" element={null} />)
}
