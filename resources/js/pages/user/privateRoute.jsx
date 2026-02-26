// PrivateRoute.jsx
import React from "react";
import { Navigate } from "react-router-dom";

const PrivateRoute = ({ children }) => {
    const user = localStorage.getItem("user-info");

    if (!user) {
        // Not logged in → redirect to login page
        return <Navigate to="/" replace />;
    }

    // Logged in → show the page
    return children;
};

export default PrivateRoute;
