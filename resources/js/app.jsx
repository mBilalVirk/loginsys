// import "./bootstrap";
// import Echo from "laravel-echo";
// import Pusher from "pusher-js";

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: "reverb",
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: window.location.hostname,
//     wsPort: 8080,
//     forceTLS: false,
//     encrypted: false,
//     disableStats: true,
// });
import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import "../css/app.css";
import Home from "./pages/home";
import Login from "./pages/login";
import Register from "./pages/register";

function App() {
    return (
        <BrowserRouter basename="/vite">
            <Routes>
                <Route path="/" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route path="/home" element={<Home />} />
            </Routes>
        </BrowserRouter>
    );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
