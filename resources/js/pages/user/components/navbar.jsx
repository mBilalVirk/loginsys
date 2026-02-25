import React, { useState } from "react";

const Navbar = () => {
    const [mobileOpen, setMobileOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);

    return (
        <nav className="relative bg-[#3F84CD] container">
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
                                <a className="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white">
                                    Dashboard
                                </a>
                                <a className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Team
                                </a>
                                <a className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Projects
                                </a>
                                <a className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Calendar
                                </a>
                            </div>
                        </div>
                    </div>

                    {/* Right Section */}
                    <div className="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:ml-6 sm:pr-0">
                        {/* Notification */}
                        <button className="rounded-full p-1 text-gray-400 hover:text-white">
                            🔔
                        </button>

                        {/* Profile */}
                        <div className="relative ml-3">
                            <img
                                onClick={() => setProfileOpen(!profileOpen)}
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e"
                                alt=""
                                className="h-8 w-8 rounded-full cursor-pointer"
                            />

                            {profileOpen && (
                                <div className="absolute right-0 mt-2 w-48 rounded-md bg-white py-1 shadow-lg">
                                    <a className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Your Profile
                                    </a>
                                    <a className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Settings
                                    </a>
                                    <a className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </a>
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
                        Dashboard
                    </a>
                    <a className="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                        Team
                    </a>
                    <a className="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                        Projects
                    </a>
                    <a className="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                        Calendar
                    </a>
                </div>
            )}
        </nav>
    );
};

export default Navbar;
