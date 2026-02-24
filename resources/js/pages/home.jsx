import React from "react";
import { Link } from "react-router-dom";
const home = () => {
    return (
        <div>
            <h1>Home Page</h1>
            <Link to="/login">Go to Login</Link> |
            <Link to="/register">Go to Register</Link>
        </div>
    );
};

export default home;
