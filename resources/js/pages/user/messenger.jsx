import React, { useState } from "react";
import { Toast } from "primereact/toast";
import { useRef } from "react";

// ✅ Chatbot conversation hook
const useChatbot = () => {
    const [botMessages, setBotMessages] = useState([
        {
            id: 0,
            role: "assistant",
            message: "Hi! I'm your AI assistant 🤖 How can I help you today?",
        },
    ]);
    const [botLoading, setBotLoading] = useState(false);

    const sendBotMessage = async (text) => {
        const userMsg = { id: Date.now(), role: "user", message: text };
        setBotMessages((prev) => [...prev, userMsg]);
        setBotLoading(true);

        try {
            // Build messages array for API (exclude initial greeting)
            const history = [...botMessages.slice(1), userMsg].map((m) => ({
                role: m.role,
                content: m.message,
            }));

            const response = await fetch("/api/chatbot", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
                body: JSON.stringify({ messages: history }),
            });

            const data = await response.json();
            setBotMessages((prev) => [
                ...prev,
                { id: Date.now() + 1, role: "assistant", message: data.reply },
            ]);
        } catch {
            setBotMessages((prev) => [
                ...prev,
                {
                    id: Date.now() + 1,
                    role: "assistant",
                    message: "Sorry, something went wrong. Try again!",
                },
            ]);
        } finally {
            setBotLoading(false);
        }
    };

    return { botMessages, botLoading, sendBotMessage };
};

const Messenger = () => {
    const toast = useRef(null);
    const [open, setOpen] = useState(false);
    const [selectedFriend, setSelectedFriend] = useState(null);
    const [messages, setMessages] = useState([]);
    const [friends, setFriends] = useState([]);
    const messagesEndRef = React.useRef(null);
    const [message, setMessage] = useState("");
    const [editingId, setEditingId] = useState(null);

    // ✅ Chatbot state
    const [chatbotOpen, setChatbotOpen] = useState(false);
    const { botMessages, botLoading, sendBotMessage } = useChatbot();
    const botEndRef = React.useRef(null);

    // Auto-scroll bot messages
    React.useEffect(() => {
        botEndRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [botMessages]);

    // ✅ Handle chatbot friend selection
    const handleSelectChatbot = () => {
        setChatbotOpen(true);
        setSelectedFriend(null);
    };

    const handleBackFromChatbot = () => {
        setChatbotOpen(false);
    };

    // ---- Your existing functions (unchanged) ----
    const editMessage = (id, text) => {
        setMessage(text);
        setEditingId(id);
    };

    const updateMessage = async () => {
        try {
            const response = await fetch(
                `/api/user/message/update/${editingId}`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                    },
                    body: JSON.stringify({ message }),
                },
            );
            const data = await response.json();
            if (response.ok) {
                setMessages((prev) =>
                    prev.map((msg) =>
                        msg.id === editingId ? { ...msg, message } : msg,
                    ),
                );
                setEditingId(null);
                setMessage("");
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: data.message || "Failed to update!",
                    life: 5000,
                });
            }
        } catch (error) {
            console.log(error);
        }
    };

    const deleteMessage = async (id) => {
        try {
            const response = await fetch(`/api/user/message/delete/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
            });
            const data = await response.json();
            if (response.ok) {
                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail: data.message,
                    life: 3000,
                });
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: data.message || "Can't delete!",
                    life: 3000,
                });
            }
            setMessages((prev) => prev.filter((msg) => msg.id !== id));
        } catch (error) {
            console.log(error);
        }
    };

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
            const authUserId = JSON.parse(localStorage.getItem("user-info"))
                .data.user.id;
            if (response.ok) {
                const acceptedFriends = data.accepted_friends.map(
                    (friendship) =>
                        friendship.sender.id === authUserId
                            ? friendship.receiver
                            : friendship.sender,
                );
                setFriends(acceptedFriends);
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
            if (response.ok) setMessages(data.data);
        } catch (error) {
            console.error("Error fetching messages:", error);
        }
    };

    const handleSendMessage = async (e) => {
        e.preventDefault();
        if (!message.trim()) return;
        if (editingId) {
            updateMessage();
            return;
        }
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
                    message,
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
                setMessage("");
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: "Something went wrong!",
                    life: 3000,
                });
            }
        } catch (error) {
            console.error("Error sending message:", error);
        }
    };

    React.useEffect(() => {
        fetchFriends();
    }, []);
    React.useEffect(() => {
        if (selectedFriend) fetchMessages(selectedFriend.id);
    }, [selectedFriend]);

    const userInfo = JSON.parse(localStorage.getItem("user-info"));
    const authId = userInfo?.data?.user?.id;

    React.useEffect(() => {
        if (!authId || !selectedFriend) return;
        const myChannel = window.Echo.private(`chat.${authId}`).listen(
            ".message.sent",
            (e) => {
                setMessages((prev) => [...prev, e.message]);
            },
        );
        myChannel.listen(".message.updated", (e) => {
            setMessages((prev) =>
                prev.map((m) =>
                    m.id === e.message.id
                        ? { ...m, message: e.message.message }
                        : m,
                ),
            );
        });
        myChannel.listen(".message.deleted", (e) => {
            setMessages((prev) => prev.filter((m) => m.id !== e.messageId));
        });
        return () => {
            window.Echo.leave("chat." + authId);
        };
    }, [authId, selectedFriend]);

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

            {open && (
                <div className="bg-white shadow-xl h-96 flex flex-col">
                    {/* ── Friend List View ── */}
                    {!selectedFriend && !chatbotOpen && (
                        <div className="p-3 overflow-y-auto flex flex-col h-full">
                            <h3 className="font-semibold mb-2 text-gray-700">
                                Messages
                            </h3>

                            {/* ✅ AI Chatbot Entry */}
                            <div
                                onClick={handleSelectChatbot}
                                className="flex items-center gap-3 p-2 hover:bg-blue-50 rounded-lg cursor-pointer border border-blue-100 mb-2"
                            >
                                <div className="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-base shrink-0">
                                    🤖
                                </div>
                                <div>
                                    <p className="font-semibold text-sm text-blue-700">
                                        AI Assistant
                                    </p>
                                    <p className="text-xs text-gray-400">
                                        Ask me anything...
                                    </p>
                                </div>
                                <span className="ml-auto text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">
                                    AI
                                </span>
                            </div>

                            <div className="border-t pt-2">
                                {friends.map((friend) => (
                                    <div
                                        key={friend.id}
                                        onClick={() =>
                                            setSelectedFriend(friend)
                                        }
                                        className="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg cursor-pointer"
                                    >
                                        <div className="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center bg-gray-300 text-gray-600 font-semibold text-sm shrink-0">
                                            {friend.photo ? (
                                                <img
                                                    src={`/${friend.photo}`}
                                                    alt={friend.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                friend.name
                                                    ?.charAt(0)
                                                    .toUpperCase()
                                            )}
                                        </div>
                                        <span className="text-sm font-medium text-gray-700">
                                            {friend.name}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── AI Chatbot View ── */}
                    {chatbotOpen && (
                        <>
                            {/* Bot Header */}
                            <div className="p-3 border-b flex items-center justify-between bg-blue-50">
                                <div className="flex items-center gap-2">
                                    <span className="text-xl">🤖</span>
                                    <div>
                                        <p className="font-semibold text-sm text-blue-700">
                                            AI Assistant
                                        </p>
                                        <p className="text-xs text-gray-400">
                                            Always online
                                        </p>
                                    </div>
                                </div>
                                <button
                                    onClick={handleBackFromChatbot}
                                    className="text-sm text-blue-600 hover:underline"
                                >
                                    Back
                                </button>
                            </div>

                            {/* Bot Messages */}
                            <div className="flex-1 p-3 overflow-y-auto space-y-2">
                                {botMessages.map((msg) => (
                                    <div
                                        key={msg.id}
                                        className={`flex ${msg.role === "user" ? "justify-end" : "justify-start"}`}
                                    >
                                        <div
                                            className={`px-3 py-2 rounded-2xl text-sm max-w-[80%] leading-relaxed ${
                                                msg.role === "user"
                                                    ? "bg-blue-600 text-white rounded-br-sm"
                                                    : "bg-gray-100 text-gray-800 rounded-bl-sm"
                                            }`}
                                        >
                                            {msg.message}
                                        </div>
                                    </div>
                                ))}

                                {/* Typing indicator */}
                                {botLoading && (
                                    <div className="flex justify-start">
                                        <div className="bg-gray-100 px-4 py-2 rounded-2xl rounded-bl-sm">
                                            <span className="flex gap-1">
                                                <span
                                                    className="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                                    style={{
                                                        animationDelay: "0ms",
                                                    }}
                                                ></span>
                                                <span
                                                    className="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                                    style={{
                                                        animationDelay: "150ms",
                                                    }}
                                                ></span>
                                                <span
                                                    className="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                                    style={{
                                                        animationDelay: "300ms",
                                                    }}
                                                ></span>
                                            </span>
                                        </div>
                                    </div>
                                )}
                                <div ref={botEndRef} />
                            </div>

                            {/* Bot Input */}
                            <div className="p-2 border-t">
                                <form
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        if (!message.trim() || botLoading)
                                            return;
                                        sendBotMessage(message.trim());
                                        setMessage("");
                                    }}
                                    className="flex gap-2"
                                >
                                    <input
                                        type="text"
                                        value={message}
                                        onChange={(e) =>
                                            setMessage(e.target.value)
                                        }
                                        className="flex-1 border rounded px-2 py-1 text-sm"
                                        placeholder="Ask AI anything..."
                                        disabled={botLoading}
                                    />
                                    <button
                                        type="submit"
                                        disabled={botLoading || !message.trim()}
                                        className="bg-blue-600 text-white px-3 rounded text-sm disabled:opacity-50"
                                    >
                                        Send
                                    </button>
                                </form>
                            </div>
                        </>
                    )}

                    {/* ── Friend Chat View (unchanged) ── */}
                    {selectedFriend && (
                        <>
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
                                    >
                                        {msg.message}
                                        <i
                                            className="pi pi-pencil ml-2"
                                            style={{ cursor: "pointer" }}
                                            onClick={() =>
                                                editMessage(msg.id, msg.message)
                                            }
                                        ></i>
                                        <i
                                            className="pi pi-trash ml-2"
                                            style={{ cursor: "pointer" }}
                                            onClick={() =>
                                                deleteMessage(msg.id)
                                            }
                                        ></i>
                                    </div>
                                ))}
                                <div ref={messagesEndRef} />
                            </div>

                            <div>
                                <form
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
                                        {editingId ? "Update" : "Send"}
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
