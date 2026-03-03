import React, { useState } from "react";

const Messenger = () => {
    const [open, setOpen] = useState(false);
    const [selectedFriend, setSelectedFriend] = useState(null);
    const [message, setMessage] = useState("");
    const [friends, setFriends] = useState([]);

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
    const fetchMessages = async (friendId) => {
        try {
            const response = await fetch(`/api/user/messages/${friendId}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
            });
            const data = await response.json();
            if (response.ok) {
                // setMessages(data);
            } else {
                console.error("Failed to fetch messages:", data.message);
            }
        } catch (error) {
            console.error("Error fetching messages:", error);
        }
    };
    React.useEffect(() => {
        fetchFriends();
    }, []);

    return (
        <div className="fixed bottom-0 right-6 w-80">
            {/* Header */}
            <div
                onClick={() => setOpen(!open)}
                className="flex items-center justify-between px-4 py-3 bg-blue-600 text-white rounded-t-2xl cursor-pointer"
            >
                <i className="pi pi-comment text-sm"></i>
                <span className="font-semibold">Messenger</span>
                <i
                    className={`pi ${open ? "pi-chevron-down" : "pi-chevron-up"}`}
                ></i>
            </div>

            {/* Popup Body */}
            {open && (
                <div className="bg-white shadow-xl rounded-t-2xl h-96 flex flex-col">
                    {/* If no friend selected → Show Friend List */}
                    {!selectedFriend && (
                        <div className="p-3 overflow-y-auto">
                            <h3 className="font-semibold mb-2">Friends</h3>

                            {friends.map((friend) => (
                                <div
                                    key={friend.id}
                                    onClick={() => setSelectedFriend(friend)}
                                    className="p-2 hover:bg-gray-100 rounded cursor-pointer"
                                >
                                    {friend.name}
                                </div>
                            ))}
                        </div>
                    )}

                    {/* If friend selected → Show Chat */}
                    {selectedFriend && (
                        <>
                            {/* Chat Header */}
                            <div className="p-3 border-b flex justify-between items-center">
                                <span className="font-semibold">
                                    {selectedFriend.name}
                                </span>
                                <button
                                    onClick={() => setSelectedFriend(null)}
                                    className="text-sm text-blue-600"
                                >
                                    Back
                                </button>
                            </div>

                            {/* Messages */}
                            <div className="flex-1 p-3 overflow-y-auto space-y-2">
                                {messages.map((msg, index) => (
                                    <div
                                        key={index}
                                        className={`p-2 rounded-lg w-fit ${
                                            msg.from === "me"
                                                ? "bg-blue-600 text-white ml-auto"
                                                : "bg-gray-200"
                                        }`}
                                    >
                                        {msg.text}
                                    </div>
                                ))}
                            </div>

                            {/* Input */}
                            <div className="p-2 border-t flex gap-2">
                                <input
                                    type="text"
                                    value={message}
                                    onChange={(e) => setMessage(e.target.value)}
                                    className="flex-1 border rounded px-2 py-1"
                                    placeholder="Type message..."
                                />
                                <button className="bg-blue-600 text-white px-3 rounded">
                                    Send
                                </button>
                            </div>
                        </>
                    )}
                </div>
            )}
        </div>
    );
};

export default Messenger;
