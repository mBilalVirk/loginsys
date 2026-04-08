import React from "react";
import ReactDOM from "react-dom/client";
import Messenger from "../js/pages/user/messenger";

const el = document.getElementById("messenger-root");
// console.log("ROOT:", el);
if (el) {
    ReactDOM.createRoot(el).render(<Messenger />);
}
