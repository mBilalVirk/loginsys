import React, { useEffect, useState } from "react";

const Dashboard = () => {
    const [user, setUser] = useState("");
    const [userLogin, setUserLogin] = useState("");
    const [post, setPost] = useState("");
    const [admin, setAdmin] = useState("");
    const [comment, setComment] = useState("");

    useEffect(() => {
        const fetchStats = async () => {
            const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
            const token = adminInfo.data.token;
            try {
                const response = await fetch("/api/admin/stats", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    setUser(data.data.user_count);
                    setPost(data.data.post_count);
                    setAdmin(data.data.admin_count);
                    setComment(data.data.comment_count);
                } else {
                    console.error("Failed to fetch stats:", data.message);
                }
            } catch (error) {
                console.error("Error fetching stats:", error);
            }
        };

        fetchStats();
    }, []);
    useEffect(() => {
        const fetchUser = async () => {
            const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
            const token = adminInfo.data.token;

            try {
                const response = await fetch("/api/user", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    setUserLogin(data);
                    console.log("User data:", data);
                } else {
                    console.error("Failed to fetch user:", data.message);
                }
            } catch (error) {
                console.error("Error fetching user:", error);
            }
        };

        fetchUser();
    }, []);

    return (
        <main className="p-6 space-y-6">
            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="bg-white p-6 rounded shadow">
                    <h3 className="text-gray-500">Users</h3>
                    <p className="text-2xl font-bold">{user}</p>
                </div>

                <div className="bg-white p-6 rounded shadow">
                    <h3 className="text-gray-500">Posts</h3>
                    <p className="text-2xl font-bold">{post}</p>
                </div>

                <div className="bg-white p-6 rounded shadow">
                    <h3 className="text-gray-500">Comments</h3>
                    <p className="text-2xl font-bold">{comment}</p>
                </div>

                <div className="bg-white p-6 rounded shadow">
                    <h3 className="text-gray-500">Admins</h3>
                    <p className="text-2xl font-bold">{admin}</p>
                </div>
            </div>

            {/* Users Table */}

            <div className="bg-white rounded shadow p-6">
                <h2 className="text-lg font-semibold mb-4">Recent Users</h2>

                <div className="overflow-x-auto">
                    <table className="min-w-full text-left">
                        <thead className="border-b">
                            <tr>
                                <th className="py-2">Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        {/* <tbody>
                            <tr className="border-b">
                                <td className="py-2">Bilal Hassan</td>
                                <td>bilal@email.com</td>
                                <td className="text-green-600">Active</td>
                                <td>
                                    <button className="text-blue-600">
                                        View
                                    </button>
                                </td>
                            </tr>

                            <tr className="border-b">
                                <td className="py-2">Ali Khan</td>
                                <td>ali@email.com</td>
                                <td className="text-red-600">Blocked</td>
                                <td>
                                    <button className="text-blue-600">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody> */}
                    </table>
                </div>
            </div>
        </main>
    );
};

export default Dashboard;
