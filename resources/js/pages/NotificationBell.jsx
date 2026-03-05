import React, { useEffect, useState } from "react";

const NotificationBell = () => {
    const [notifications, setNotifications] = useState([]);
    const [open, setOpen] = useState(false);

    const userId = JSON.parse(localStorage.getItem("user-info")).data.user.id;

    // Fetch unread notifications from API
    const fetchNotifications = async () => {
        const res = await fetch("/api/user/notifications", {
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
            },
        });
        const data = await res.json();
        setNotifications(data);
    };

    useEffect(() => {
        fetchNotifications();

        // Listen for real-time notifications
        const channel = window.Echo.private(
            `App.Models.User.${userId}`,
        ).notification((notification) => {
            setNotifications((prev) => [notification, ...prev]);
        });

        return () => {
            window.Echo.leave(`App.Models.User.${userId}`);
        };
    }, []);

    return (
        <div className="relative">
            {/* Bell Icon */}
            <div
                onClick={() => setOpen(!open)}
                className="cursor-pointer relative"
            >
                <i className="pi pi-bell text-xl"></i>
                {notifications.length > 0 && (
                    <span className="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs w-4 h-4 flex items-center justify-center">
                        {notifications.length}
                    </span>
                )}
            </div>

            {/* Dropdown */}
            {open && (
                <div className="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded">
                    {notifications.length === 0 && (
                        <div className="p-2 text-sm text-gray-500">
                            No new notifications
                        </div>
                    )}
                    {notifications.map((n) => {
                        const message =
                            n.data?.message || n.message || "No message"; // support both
                        return (
                            <div
                                key={n.id}
                                className="p-2 border-b hover:bg-gray-100 cursor-pointer"
                            >
                                {message}
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
};

export default NotificationBell;
