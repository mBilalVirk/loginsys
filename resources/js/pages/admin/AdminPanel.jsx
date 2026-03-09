import React, { useState, useRef, useEffect } from "react";

import { Toast } from "primereact/toast";

import Dashboard from "./Dashboard";
import { Routes, Route, Link, useNavigate } from "react-router-dom";

import User from "./User";
import Post from "./Post";
const AdminPanel = () => {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);
    const toast = useRef(null);
    const navigate = useNavigate();
    const AdminInfo = JSON.parse(localStorage.getItem("admin-info"));
    const role = AdminInfo.data.user.role;
    const image = AdminInfo.data.user.photo;
    const name = AdminInfo.data.user.name;

    // Close dropdown when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                dropdownRef.current &&
                !dropdownRef.current.contains(event.target)
            ) {
                setDropdownOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);
    const logOut = async (e) => {
        console.log("logout click!");
        const user = JSON.parse(localStorage.getItem("admin-info"));
        const token = user.data.token;

        try {
            const logout = await fetch("/api/admin/logout", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    // "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            });
            const data = await logout.json();
            if (logout.ok) {
                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail: data.message || "Login successful",
                    life: 3000,
                });
                localStorage.removeItem("admin-info");
                navigate("/adminlogin");
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "error",
                    detail: data.message || "Something wrong!",
                    life: 3000,
                });
            }
        } catch (error) {
            toast.current.show({
                severity: "error",
                summary: "error",
                detail: "Something wrong!",
                life: 3000,
            });
            console.error(error);
        }
    };
    return (
        <div className="flex min-h-screen bg-gray-100">
            <Toast ref={toast} />
            {/* Sidebar */}
            <aside
                className={`bg-gray-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform 
                ${sidebarOpen ? "translate-x-0" : "-translate-x-full"} 
                md:relative md:translate-x-0 transition duration-200 ease-in-out`}
            >
                <h2 className="text-2xl font-bold text-center">Admin Panel</h2>

                <nav className="mt-10">
                    <Link
                        to="/admin/dashboard"
                        className="flex items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded"
                    >
                        <i className="pi pi-th-large"></i>
                        <span className="ml-2">Dashboard</span>
                    </Link>

                    <Link
                        to="/admin/users"
                        className="flex items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded"
                    >
                        <i className="pi pi-user pi-2x"></i>
                        <span className="ml-2">Users</span>
                    </Link>

                    <Link
                        to="/admin/posts"
                        className="flex items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded"
                    >
                        <i className="pi pi-pencil"></i>
                        <span className="ml-2">Post</span>
                    </Link>

                    <a
                        href="#"
                        className="block py-2 px-4 hover:bg-gray-700 rounded"
                    >
                        <i className="pi pi-comments"></i>
                        <span className="ml-2">Comments</span>
                    </a>

                    <a
                        href="#"
                        className="block py-2 px-4 hover:bg-gray-700 rounded"
                    >
                        <i className="pi pi-cog"></i>
                        <span className="ml-2">Settings</span>
                    </a>
                </nav>
            </aside>

            {/* Main Content */}
            <div className="flex-1 flex flex-col">
                {/* Top Navbar */}
                <header className="flex items-center justify-between bg-white shadow px-6 py-4 relative">
                    {/* Mobile Sidebar Toggle */}
                    <button
                        className="md:hidden text-gray-700"
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                    >
                        ☰
                    </button>

                    {/* Title */}
                    <h1 className="text-xl font-semibold">Dashboard</h1>

                    {/* Right Menu */}
                    <div className="flex items-center gap-4 relative">
                        {/* Notifications Icon */}
                        <button className="relative text-gray-600">
                            🔔
                            <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                3
                            </span>
                        </button>

                        {/* Profile Avatar */}
                        <div className="relative" ref={dropdownRef}>
                            <button
                                className="flex items-center gap-2 focus:outline-none"
                                onClick={() => setDropdownOpen(!dropdownOpen)}
                            >
                                <img
                                    src={`/${image}`}
                                    alt="profile"
                                    className="rounded-full w-8 h-8"
                                />
                                <span className="hidden md:block text-gray-700">
                                    Admin
                                </span>
                            </button>

                            {/* Dropdown Menu */}
                            {dropdownOpen && (
                                <div className="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                                    <span className="block px-4 py-2 text-gray-700">
                                        <i className="pi pi-user pi-2x"></i>{" "}
                                        {"    "}
                                        {name}
                                    </span>
                                    <a
                                        href="#"
                                        className="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    >
                                        Profile
                                    </a>
                                    <a
                                        href="#"
                                        className="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    >
                                        Settings
                                    </a>
                                    <button
                                        className="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                                        onClick={logOut}
                                    >
                                        Logout
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>
                </header>

                {/* Dashboard Content */}

                <main className="p-6 flex-1">
                    <Routes>
                        <Route path="/dashboard" element={<Dashboard />} />
                        <Route path="/users" element={<User />} />
                        <Route path="/posts" element={<Post />} />
                        {/* You can add more pages like /posts, /comments */}
                    </Routes>
                </main>
            </div>
        </div>
    );
};

export default AdminPanel;
