import React from "react";
import { useState } from "react";
import { InputText } from "primereact/inputtext";
import { classNames } from "primereact/utils";
const Sidebar = () => {
    const [friends, setFriends] = useState([]);
    const [searchFriends, setSearchFriends] = useState("");

    const fetchFriends = async () => {
        try {
            const response = await fetch("/api/user/friends", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
            });
            const data = await response.json();
            if (response.ok) {
                setFriends(data);
            } else {
                console.error("Failed to fetch friends:", data.message);
            }
        } catch (error) {
            console.error("Error fetching friends:", error);
        }
    };
    React.useEffect(() => {
        fetchFriends();
    }, []);

    return (
        <div className="w-72 bg-white shadow-lg rounded-lg p-4 h-screen overflow-y-auto">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-semibold">Friends</h2>
                <i className="pi pi-users text-gray-500"></i>
            </div>

            {/* Search */}
            <div className="p-inputgroup w-full mb-4">
                <span className="p-inputgroup-addon">
                    <i className="pi pi-search text-gray-400"></i>
                </span>
                <InputText
                    placeholder="Search friends..."
                    className="w-full text-sm"
                />
            </div>

            {/* Friend List */}
            <div className="space-y-3">
                {friends.map((friend) => (
                    <div
                        key={friend.id}
                        className="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 cursor-pointer"
                    >
                        <div className="relative">
                            <img
                                src={"/" + friend.photo}
                                alt={friend.name}
                                className="w-10 h-10 rounded-full object-cover"
                            />

                            {/* Online Indicator */}
                            {friend.online && (
                                <span className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                            )}
                        </div>

                        <span className="text-sm font-medium">
                            {friend.name}
                        </span>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Sidebar;
