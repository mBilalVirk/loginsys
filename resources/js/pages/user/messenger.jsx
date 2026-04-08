import React, { useState, useRef } from "react";
import { Toast } from "primereact/toast";

// ✅ Chatbot conversation hook — with DB history + dynamic settings
const useChatbot = () => {
    const [botMessages, setBotMessages] = useState([]);
    const [botLoading, setBotLoading] = useState(false);
    const [botReady, setBotReady] = useState(false); // ✅ loading gate
    const [assistantName, setAssistantName] = useState(" ");

    const token = JSON.parse(localStorage.getItem("user-info"))?.data?.token;

    // ✅ Load settings + history together on mount
    React.useEffect(() => {
        const init = async () => {
            try {
                // Fetch settings and history in parallel
                const [settingsRes, historyRes] = await Promise.all([
                    fetch("/api/ai-settings", {
                        headers: {
                            Accept: "application/json",
                            Authorization: `Bearer ${token}`,
                        },
                    }),
                    fetch("/api/chatbot/history", {
                        headers: {
                            Accept: "application/json",
                            Authorization: `Bearer ${token}`,
                        },
                    }),
                ]);

                const settings = await settingsRes.json();

                const history = await historyRes.json();

                const name = settings.data.assistant_name ?? "AI Assistant";

                const welcome =
                    settings.data.welcome_message ??
                    "Hi! I'm your AI assistant 🤖 How can I help you today?";

                setAssistantName(name);

                // Build messages: greeting first, then history
                setBotMessages([
                    { id: 0, role: "assistant", message: welcome },
                    ...(Array.isArray(history)
                        ? history.map((m) => ({
                              id: m.id,
                              role: m.role,
                              message: m.message,
                          }))
                        : []),
                ]);
            } catch (error) {
                console.error("Failed to initialize chatbot:", error);
                // Fallback defaults if API fails
                setBotMessages([
                    {
                        id: 0,
                        role: "assistant",
                        message:
                            "Hi! I'm your AI assistant 🤖 How can I help you today?",
                    },
                ]);
            } finally {
                setBotReady(true); // ✅ ready to render
            }
        };
        init();
    }, []);

    // ✅ Clear history from DB
    const clearBotHistory = async () => {
        try {
            await fetch("/api/chatbot/history", {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token}`,
                },
            });
            setBotMessages((prev) => [prev[0]]); // keep welcome message
        } catch (error) {
            console.error("Failed to clear history:", error);
        }
    };

    // ✅ Send message
    const sendBotMessage = async (text, currentMessages) => {
        const userMsg = { id: Date.now(), role: "user", message: text };
        setBotMessages((prev) => [...prev, userMsg]);
        setBotLoading(true);

        try {
            const history = [...currentMessages.slice(1), userMsg].map((m) => ({
                role: m.role,
                content: m.message,
            }));

            const response = await fetch("/api/chatbot", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${token}`,
                },
                body: JSON.stringify({ messages: history }),
            });

            const data = await response.json();
            const reply = data.reply ?? "Sorry, no response.";

            setBotMessages((prev) => [
                ...prev,
                { id: Date.now() + 1, role: "assistant", message: reply },
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

    return {
        botMessages,
        botLoading,
        botReady,
        assistantName,
        sendBotMessage,
        clearBotHistory,
    };
};

// ✅ Main Messenger Component
const Messenger = () => {
    const toast = useRef(null);
    const [open, setOpen] = useState(false);
    const [selectedFriend, setSelectedFriend] = useState(null);
    const [messages, setMessages] = useState([]);
    const [friends, setFriends] = useState([]);
    const [message, setMessage] = useState("");
    const [editingId, setEditingId] = useState(null);
    const messagesEndRef = useRef(null);

    // Chatbot state
    const [chatbotOpen, setChatbotOpen] = useState(false);
    const {
        botMessages,
        botLoading,
        botReady,
        assistantName,
        sendBotMessage,
        clearBotHistory,
    } = useChatbot();
    const botEndRef = useRef(null);

    const token = JSON.parse(localStorage.getItem("user-info"))?.data?.token;
    const authId = JSON.parse(localStorage.getItem("user-info"))?.data?.user
        ?.id;

    // ── Auto-scroll ──
    React.useEffect(() => {
        botEndRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [botMessages]);

    React.useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [messages]);

    // ── Chatbot handlers ──
    const handleSelectChatbot = () => {
        setChatbotOpen(true);
        setSelectedFriend(null);
    };

    const handleBackFromChatbot = () => {
        setChatbotOpen(false);
    };

    // ── Friend message handlers ──
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
                        Authorization: `Bearer ${token}`,
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
                    Authorization: `Bearer ${token}`,
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
    // this is api for front-end
    // const fetchFriends = async () => {
    //     try {
    //         const response = await fetch("/api/user/friends", {
    //             method: "GET",
    //             headers: {
    //                 Accept: "application/json",
    //                 Authorization: `Bearer ${token}`,
    //             },
    //         });
    //         const data = await response.json();
    //         if (response.ok) {
    //             const acceptedFriends = data.accepted_friends.map(
    //                 (friendship) =>
    //                     friendship.sender.id === authId
    //                         ? friendship.receiver
    //                         : friendship.sender,
    //             );
    //             setFriends(acceptedFriends);
    //         }
    //     } catch (error) {
    //         console.error("Error fetching friends:", error);
    //     }
    // };
    // this is for .blade.php component
    const fetchFriends = async () => {
        try {
            // Step 1: Get CSRF cookie from Laravel
            await fetch("http://127.0.0.1:8000/sanctum/csrf-cookie", {
                credentials: "include", // Important
            });

            // Step 2: Fetch friends API
            const response = await fetch("user/api/friends", {
                method: "GET",
                credentials: "include", // Sends session cookie
                headers: {
                    Accept: "application/json",
                },
            });

            const data = await response.json();
            console.log("Friends API response:", data);
            if (response.ok) {
                const acceptedFriends = data.friends.map((friendship) =>
                    friendship.sender.id === authId
                        ? friendship.sender
                        : friendship.receiver,
                );
                setFriends(acceptedFriends);
            } else {
                console.error("Failed to fetch friends:", data);
            }
        } catch (error) {
            console.error("Error fetching friends:", error);
        }
    };
    const fetchMessages = async (friendId) => {
        try {
            const response = await fetch(`user/chat/${friendId}`, {
                method: "GET",
                credentials: "include",
                headers: {
                    Accept: "application/json",
                    // Authorization: `Bearer ${token}`,
                },
            });
            const data = await response.json();
            console.log("Messages API response:", data);
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
                    Authorization: `Bearer ${token}`,
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

    // ── Effects ──
    React.useEffect(() => {
        fetchFriends();
    }, []);

    React.useEffect(() => {
        if (selectedFriend) fetchMessages(selectedFriend.id);
    }, [selectedFriend]);

    // ── Real-time Echo ──
    React.useEffect(() => {
        if (!authId || !selectedFriend) return;
        const myChannel = window.Echo.private(`chat.${authId}`).listen(
            ".message.sent",
            (e) => setMessages((prev) => [...prev, e.message]),
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
        return () => window.Echo.leave("chat." + authId);
    }, [authId, selectedFriend]);

    // ── Render ──
    return (
        <div className="fixed bottom-0 right-6 w-80 z-50">
            <Toast ref={toast} />

            {/* ── Messenger Header ── */}
            <div
                onClick={() => setOpen(!open)}
                className="flex items-center justify-between px-4 py-3 bg-blue-600 text-white rounded-t-2xl cursor-pointer select-none"
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

                            {/* AI Chatbot Entry */}
                            <div
                                onClick={handleSelectChatbot}
                                className="flex items-center gap-3 p-2 hover:bg-blue-50 rounded-lg cursor-pointer border border-blue-100 mb-2"
                            >
                                <div className="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-base shrink-0">
                                    🤖
                                </div>
                                <div>
                                    <p className="font-semibold text-sm text-blue-700">
                                        {assistantName}
                                    </p>
                                    <p className="text-xs text-gray-400">
                                        Ask me anything...
                                    </p>
                                </div>
                                <span className="ml-auto text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">
                                    AI
                                </span>
                            </div>

                            {/* Friends */}
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
                                            {assistantName}
                                        </p>
                                        <p className="text-xs text-gray-400">
                                            Always online
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <button
                                        onClick={clearBotHistory}
                                        className="text-xs text-red-400 hover:text-red-500 font-medium"
                                    >
                                        Clear
                                    </button>
                                    <button
                                        onClick={handleBackFromChatbot}
                                        className="text-sm text-blue-600 hover:underline"
                                    >
                                        Back
                                    </button>
                                </div>
                            </div>

                            {/* ✅ Loading gate — show spinner until ready */}
                            {!botReady ? (
                                <div className="flex-1 flex items-center justify-center">
                                    <div className="flex flex-col items-center gap-2 text-gray-400">
                                        <span className="flex gap-1">
                                            <span
                                                className="w-2 h-2 bg-blue-400 rounded-full animate-bounce"
                                                style={{
                                                    animationDelay: "0ms",
                                                }}
                                            ></span>
                                            <span
                                                className="w-2 h-2 bg-blue-400 rounded-full animate-bounce"
                                                style={{
                                                    animationDelay: "150ms",
                                                }}
                                            ></span>
                                            <span
                                                className="w-2 h-2 bg-blue-400 rounded-full animate-bounce"
                                                style={{
                                                    animationDelay: "300ms",
                                                }}
                                            ></span>
                                        </span>
                                        <p className="text-xs">Loading...</p>
                                    </div>
                                </div>
                            ) : (
                                /* Bot Messages */
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
                                                            animationDelay:
                                                                "0ms",
                                                        }}
                                                    ></span>
                                                    <span
                                                        className="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                                        style={{
                                                            animationDelay:
                                                                "150ms",
                                                        }}
                                                    ></span>
                                                    <span
                                                        className="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                                        style={{
                                                            animationDelay:
                                                                "300ms",
                                                        }}
                                                    ></span>
                                                </span>
                                            </div>
                                        </div>
                                    )}
                                    <div ref={botEndRef} />
                                </div>
                            )}

                            {/* Bot Input */}
                            <div className="p-2 border-t">
                                <form
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        if (
                                            !message.trim() ||
                                            botLoading ||
                                            !botReady
                                        )
                                            return;
                                        sendBotMessage(
                                            message.trim(),
                                            botMessages,
                                        );
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
                                        disabled={botLoading || !botReady}
                                    />
                                    <button
                                        type="submit"
                                        disabled={
                                            botLoading ||
                                            !message.trim() ||
                                            !botReady
                                        }
                                        className="bg-blue-600 text-white px-3 rounded text-sm disabled:opacity-50"
                                    >
                                        Send
                                    </button>
                                </form>
                            </div>
                        </>
                    )}

                    {/* ── Friend Chat View ── */}
                    {selectedFriend && (
                        <>
                            <div className="p-3 border-b flex justify-between items-center">
                                <span className="font-semibold">
                                    {selectedFriend.name}
                                </span>
                                <button
                                    onClick={() => {
                                        setSelectedFriend(null);
                                        setEditingId(null);
                                        setMessage("");
                                    }}
                                    className="text-sm text-blue-600"
                                >
                                    Back
                                </button>
                            </div>

                            <div className="flex-1 p-3 overflow-y-auto space-y-2">
                                {messages.map((msg) => (
                                    <div
                                        key={msg.id}
                                        className={`p-2 rounded-lg w-fit max-w-[80%] text-sm ${
                                            msg.sender_id === authId
                                                ? "bg-blue-600 text-white ml-auto"
                                                : "bg-gray-200 text-gray-800"
                                        }`}
                                    >
                                        {msg.message}
                                        {msg.sender_id === authId && (
                                            <>
                                                <i
                                                    className="pi pi-pencil ml-2 cursor-pointer opacity-70 hover:opacity-100"
                                                    onClick={() =>
                                                        editMessage(
                                                            msg.id,
                                                            msg.message,
                                                        )
                                                    }
                                                ></i>
                                                <i
                                                    className="pi pi-trash ml-2 cursor-pointer opacity-70 hover:opacity-100"
                                                    onClick={() =>
                                                        deleteMessage(msg.id)
                                                    }
                                                ></i>
                                            </>
                                        )}
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
                                        className="flex-1 border rounded px-2 py-1 text-sm"
                                        placeholder={
                                            editingId
                                                ? "Edit message..."
                                                : "Type message..."
                                        }
                                    />
                                    <button className="bg-blue-600 text-white px-3 rounded text-sm">
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
