import React, { useEffect, useRef, useState } from "react";
import { Dialog } from "primereact/dialog";
import { Button } from "primereact/button";
import { ConfirmPopup, confirmPopup } from "primereact/confirmpopup";

import { Toast } from "primereact/toast";

const User = () => {
    const [user, setUser] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedUser, setSelectedUser] = useState(null); // User to show in modal
    const [modalOpen, setModalOpen] = useState(false);
    const toast = useRef(null);
    useEffect(() => {
        const fetchUsers = async () => {
            setLoading(true);
            const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
            const token = adminInfo.data.token;
            try {
                const response = await fetch("/api/admin/fetch", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    setUser(data.user);
                } else {
                    console.error("Failed to fetch users:", data.message);
                }
            } catch (error) {
                console.error("Error fetching users:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchUsers();
    }, []);
    const openModal = (user) => {
        setSelectedUser(user);
        setModalOpen(true);
    };

    const closeModal = () => {
        setSelectedUser(null);
        setModalOpen(false);
    };
    const handleDelete = async (id) => {
        const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
        const token = adminInfo.data.token;

        try {
            const response = await fetch(`/api/admin/delete/${id}`, {
                method: "DELETE",
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });

            const data = await response.json();

            if (response.ok) {
                // Remove the user from local state to update UI immediately
                setUser((prevUsers) => prevUsers.filter((u) => u.id !== id));

                toast.current.show({
                    severity: "success",
                    summary: "Deleted",
                    detail: data.message || "User deleted successfully",
                    life: 3000,
                });
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: data.message || "Failed to delete user",
                    life: 3000,
                });
            }
        } catch (error) {
            console.error("Error deleting user:", error);
            toast.current.show({
                severity: "error",
                summary: "Error",
                detail: "Something went wrong!",
                life: 3000,
            });
        }
    };
    return (
        <div className="bg-white rounded shadow p-6">
            <Toast ref={toast} />
            <ConfirmPopup />
            <h2 className="text-lg font-semibold mb-4">Recent Users</h2>

            <div className="overflow-x-auto">
                {loading ? (
                    <i
                        className="pi pi-spin pi-spinner"
                        style={{ fontSize: "2rem" }}
                    ></i>
                ) : (
                    <table className="min-w-full text-left">
                        <thead className="border-b">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {user.map((user) => (
                                <tr className="border-b" key={user.id}>
                                    <td>{user.id}</td>
                                    <td>{user.name}</td>
                                    <td>{user.email}</td>
                                    <td>
                                        <img
                                            src={`/${user.photo}`}
                                            width={50}
                                            height={50}
                                            className="rounded-full object-cover w-12 h-12"
                                            alt={user.name}
                                        />
                                    </td>
                                    <td>
                                        <Button
                                            label=""
                                            icon="pi pi-eye"
                                            className="p-button-text p-button-info text-blue-600"
                                            onClick={() => openModal(user)}
                                        />

                                        {"      "}
                                        <Button
                                            icon="pi pi-trash"
                                            className="p-button-text p-button-danger"
                                            onClick={(e) =>
                                                confirmPopup({
                                                    target: e.currentTarget,
                                                    message:
                                                        "Are you sure you want to delete this user?",
                                                    icon: "pi pi-exclamation-triangle",
                                                    accept: () =>
                                                        handleDelete(user.id),
                                                    reject: () => {},
                                                })
                                            }
                                        />
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                )}
            </div>
            {/* User Modal */}
            {selectedUser && (
                <Dialog
                    header="User Details"
                    visible={modalOpen}
                    style={{ width: "400px" }}
                    onHide={closeModal}
                >
                    <div className="flex flex-col items-center gap-4">
                        <img
                            src={`/${selectedUser.photo}`}
                            alt={selectedUser.name}
                            className="rounded-full object-cover w-24 h-24"
                        />
                        <h3 className="text-lg font-semibold">
                            {selectedUser.name}
                        </h3>
                        <p>Email: {selectedUser.email}</p>

                        {/* Add more details if needed */}
                    </div>
                </Dialog>
            )}
        </div>
    );
};

export default User;
