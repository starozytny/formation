import React from "react";
import { createRoot } from "react-dom/client";
import { Preregistration } from "@userPages/Formation/Preregistration";

let el = document.getElementById("formations_preregistration");
if(el){
	createRoot(el).render(<Preregistration />)
}
