import React from "react";

const messenger = () => {
    return (
        <main className="bg-gray-100 lg:row-start-2 flex h-full">
            {/* Chat List */}
            <div className="w-80 bg-white border-r flex flex-col">
                <div className="p-4 font-bold text-lg border-b">Chats</div>
                <div className="flex-1 overflow-y-auto">
                    <div className="p-4 hover:bg-gray-100 cursor-pointer">
                        <p className="font-semibold">Ali</p>
                        <p className="text-sm text-gray-500">
                            Last message preview...
                        </p>
                    </div>
                    <div className="p-4 hover:bg-gray-100 cursor-pointer">
                        <p className="font-semibold">Ahmed</p>
                        <p className="text-sm text-gray-500">
                            Another message preview...
                        </p>
                    </div>
                </div>
            </div>
            {/* Conversation Area */}
            <div className="flex-1 flex flex-col">
                {/* Chat Header */}
                <div className="p-4 bg-white border-b font-semibold">Ali</div>
                {/* Messages */}
                <div className="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
                    {/* Receiver message */}
                    <div className="flex">
                        <div className="bg-white p-3 rounded-2xl shadow max-w-xs">
                            Hello 👋
                        </div>
                    </div>
                    {/* Sender message */}
                    <div className="flex justify-end">
                        <div className="bg-blue-500 text-white p-3 rounded-2xl shadow max-w-xs">
                            Hi bro!
                        </div>
                    </div>
                </div>
                {/* Message Input */}
                <div className="p-4 bg-white border-t flex gap-2">
                    <input
                        type="text"
                        placeholder="Type a message..."
                        className="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                    <button className="bg-blue-500 text-white px-5 py-2 rounded-full">
                        Send
                    </button>
                </div>
            </div>
        </main>
    );
};

export default messenger;
