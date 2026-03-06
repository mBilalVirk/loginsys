import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import { Toast } from "primereact/toast";
import { useRef } from "react";
import NotificationBell from "../../NotificationBell";
import Logo from "../../../../../public/logo.png";
const Navbar = () => {
    const [mobileOpen, setMobileOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);
    const userInfo = JSON.parse(localStorage.getItem("user-info"));
    const authUserName = userInfo.data.user.name;
    const authUserImg = userInfo.data.user.photo;
    const navigate = useNavigate();
    useEffect(() => {}, []);

    const toast = useRef(null);

    const logOut = async (e) => {
        console.log("logout click!");
        const user = JSON.parse(localStorage.getItem("user-info"));
        const token = user.data.token;

        try {
            const logout = await fetch("/api/user/logout", {
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
                localStorage.removeItem("user-info");
                navigate("/");
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
        <nav className="relative bg-blue-500 container m-auto mb-1 rounded-b-md">
            <Toast ref={toast} />
            <div className="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div className="relative flex h-16 items-center justify-between">
                    {/* Mobile menu button */}
                    <div className="absolute inset-y-0 left-0 flex items-center sm:hidden">
                        <button
                            type="button"
                            onClick={() => setMobileOpen(!mobileOpen)}
                            className="inline-flex items-center justify-center rounded-md p-2  hover:bg-white/5 hover:text-white"
                        >
                            ☰
                        </button>
                    </div>

                    {/* Logo + Desktop Menu */}
                    <div className="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                        <div className="flex shrink-0 items-center">
                            <img
                                src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                                alt="Logo"
                                className="h-8 w-auto"
                            />
                        </div>

                        <div className="hidden sm:ml-6 sm:block">
                            <div className="flex space-x-4">
                                <Link
                                    to="/home"
                                    className="rounded-md  px-3 py-2 text-sm font-medium text-white"
                                >
                                    Home
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Right Section */}
                    <div className="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:ml-6 sm:pr-0">
                        {/* Notification */}
                        <button className="rounded-full p-1 text-gray-400 hover:text-white">
                            🔔
                        </button>
                        <span>|</span>
                        {/* <NotificationBell /> */}
                        {/* Profile */}
                        <div className="relative ml-3">
                            <img
                                onClick={() => setProfileOpen(!profileOpen)}
                                src={`/${authUserImg}`}
                                alt=""
                                className="h-8 w-8 rounded-full cursor-pointer"
                            />

                            {profileOpen && (
                                <div className="absolute right-0 mt-2 w-48 rounded-md bg-white py-1 shadow-lg z-50">
                                    <Link
                                        to="/profile"
                                        className=" block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        <i className="pi pi-user"></i> :{" "}
                                        <span>{`${authUserName}`}</span>
                                    </Link>
                                    <a className=" block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i className="pi pi-cog"></i> : Settings
                                    </a>
                                    <button
                                        className=" block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        onClick={logOut}
                                    >
                                        <i className="pi pi-sign-out"></i>: Sign
                                        out
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Mobile Menu */}
            {mobileOpen && (
                <div className="sm:hidden bg-gray-700 px-2 pt-2 pb-3 space-y-1">
                    <a className="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">
                        Home
                    </a>
                    <a className="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                        Friends
                    </a>
                    <a className="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                        Messages
                    </a>
                </div>
            )}
        </nav>
    );
};

export default Navbar;
