import React, { useState } from "react";
import { Toast } from "primereact/toast";
import { useRef } from "react";

const Messenger = () => {
    const toast = useRef(null);
    const [open, setOpen] = useState(false);
    const [selectedFriend, setSelectedFriend] = useState(null);
    const [messages, setMessages] = useState([]);
    const [friends, setFriends] = useState([]);
    const messagesEndRef = React.useRef(null);
    const [message, setMessage] = useState("");
    React.useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [messages]);
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
            const userInfo = JSON.parse(localStorage.getItem("user-info"));
            const authUserId = userInfo.data.user.id;

            if (response.ok) {
                const acceptedFriends = data.accepted_friends.map(
                    (friendship) => {
                        if (friendship.sender.id === authUserId) {
                            return friendship.receiver; // other person
                        } else {
                            return friendship.sender; // other person
                        }
                    },
                );
                setFriends(acceptedFriends);
            } else {
                console.error("Failed to fetch friends:", data.message);
            }
        } catch (error) {
            console.error("Error fetching friends:", error);
        }
    };
    const fetchMessages = async (friendId) => {
        try {
            const response = await fetch(`/api/user/chat/${friendId}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
            });
            const data = await response.json();
            if (response.ok) {
                setMessages(data.data);
            } else {
                console.error("Failed to fetch messages:", data.message);
            }
        } catch (error) {
            console.error("Error fetching messages:", error);
        }
    };

    const handleSendMessage = async (e) => {
        e.preventDefault(); // prevent page reload

        if (!message.trim()) return; // don't send empty messages

        try {
            const response = await fetch("/api/user/message/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
                body: JSON.stringify({
                    receiver_id: selectedFriend.id.toString(),
                    message: message,
                }),
            });

            const data = await response.json();
            if (response.ok) {
                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail: data.message,
                    life: 3000,
                });
                setMessages((prev) => [...prev, data.data]);
                setMessage(""); // clear input
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: "Something went wrong!",
                    life: 3000,
                });
                console.error("Error sending message:", data);
            }
        } catch (error) {
            console.error("Error sending message:", error);
        }
    };
    React.useEffect(() => {
        fetchFriends();
    }, []);
    React.useEffect(() => {
        if (selectedFriend) {
            fetchMessages(selectedFriend.id);
        }
    }, [selectedFriend]);
    window.Echo.connector.pusher.connection.bind("connected", () => {
        console.log("WebSocket connected ✅");
    });
    const userInfo = JSON.parse(localStorage.getItem("user-info"));
    const authId = userInfo?.data?.user?.id;
    React.useEffect(() => {
        if (!authId) return;

        console.log("Listening on: chat." + authId);

        const channel = window.Echo.private("chat." + authId).listen(
            ".message.sent",
            (e) => {
                console.log("New message received:", e);

                setMessages((prev) => [...prev, e.message]);
            },
        );

        return () => {
            window.Echo.leave("private-chat." + authId);
        };
    }, [authId]);
    return (
        <div className="fixed bottom-0 right-6 w-80">
            <Toast ref={toast} />
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
                                {messages.map((msg) => (
                                    <div
                                        key={msg.id}
                                        className={`p-2 rounded-lg w-fit ${
                                            msg.sender_id ===
                                            JSON.parse(
                                                localStorage.getItem(
                                                    "user-info",
                                                ),
                                            ).data.user.id
                                                ? "bg-blue-600 text-white ml-auto"
                                                : "bg-gray-200"
                                        }`}
                                        id="message"
                                    >
                                        {msg.message}
                                    </div>
                                ))}
                                <div ref={messagesEndRef} />
                            </div>

                            {/* Input */}
                            <div>
                                <form
                                    action="#"
                                    method="POST"
                                    onSubmit={handleSendMessage}
                                    className="p-2 border-t flex gap-2"
                                >
                                    <input
                                        type="text"
                                        value={message}
                                        onChange={(e) =>
                                            setMessage(e.target.value)
                                        }
                                        className="flex-1 border rounded px-2 py-1"
                                        placeholder="Type message..."
                                    />
                                    <button className="bg-blue-600 text-white px-3 rounded">
                                        Send
                                    </button>
                                </form>
                            </div>
                        </>
                    )}
                </div>
            )}
        </div>
    );
};

export default Messenger;
