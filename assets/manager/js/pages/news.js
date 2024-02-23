import "../../css/pages/news.scss"

import React from "react";
import { createRoot } from "react-dom/client";
import { News } from "@managerPages/News/News";
import { NewsFormulaire } from "@managerPages/News/NewsForm";

let el = document.getElementById("news_list");
if(el){
	createRoot(el).render(<News {...el.dataset} />)
}

el = document.getElementById("news_update");
if(el){
	createRoot(el).render(<NewsFormulaire context="update" element={JSON.parse(el.dataset.obj)} />)
}

el = document.getElementById("news_create");
if(el){
	createRoot(el).render(<NewsFormulaire context="create" element={null} />)
}
