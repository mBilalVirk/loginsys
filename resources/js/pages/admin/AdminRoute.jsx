import { Navigate } from "react-router-dom";

const AdminRoute = ({ children }) => {
    const admin = localStorage.getItem("admin-info");

    return admin ? children : <Navigate to="/adminlogin" />;
};

export default AdminRoute;
