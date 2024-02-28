import "../../css/pages/order.scss"

import React from "react";
import { createRoot } from "react-dom/client";
import { Orders } from "@userPages/Order/Orders";

let el = document.getElementById("orders_list");
if(el){
	createRoot(el).render(<Orders />)
}
