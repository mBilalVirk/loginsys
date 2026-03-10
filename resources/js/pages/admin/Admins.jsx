import React, { useEffect, useRef, useState } from "react";
import { Dialog } from "primereact/dialog";
import { Button } from "primereact/button";
import { ConfirmPopup, confirmPopup } from "primereact/confirmpopup";

import { Toast } from "primereact/toast";
export const Admins = () => {
    const [admin, setAdmin] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedAdmin, setSelectedAdmin] = useState(null); // admin to show in modal
    const [modalOpen, setModalOpen] = useState(false);
    const toast = useRef(null);

    const [input, setInput] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        photo: null,
    });

    const handleChange = (e) => {
        if (e.target.type === "file") {
            setInput({
                ...input,
                photo: e.target.files[0],
            });
        } else {
            setInput({
                ...input,
                [e.target.name]: e.target.value,
            });
        }
    };
    const handleSubmit = async (e) => {
        e.preventDefault();
        const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
        const token = adminInfo.data.token;
        const formData = new FormData();
        formData.append("name", input.name);
        formData.append("email", input.email);
        formData.append("password", input.password);
        formData.append("password_confirmation", input.password_confirmation);
        formData.append("photo", input.photo);
        try {
            const response = await fetch("/api/admin/create", {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
                body: formData,
            });
            const data = await response.json();
            if (response.ok) {
                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail: data.message || "Admin created successfully",
                    life: 3000,
                });
                // Optionally, you can add the new admin to the local state to update the UI immediately
                setAdmin((prevAdmins) => [...prevAdmins, data.data]);
                setInput({
                    name: "",
                    email: "",
                    password: "",
                    photo: null,
                });
                setModalOpen(false);
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: data.message || "Failed to create admin",
                    life: 3000,
                });
            }
        } catch (error) {
            console.error("Error creating admin:", error);
            toast.current.show({
                severity: "error",
                summary: "Error",
                detail: "Something went wrong!",
                life: 3000,
            });
        }
    };

    const [viewAdminOpen, setViewAdminOpen] = useState(false);
    useEffect(() => {
        const fetchAdmins = async () => {
            setLoading(true);
            const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
            const token = adminInfo.data.token;
            try {
                const response = await fetch("/api/admin/fetchadmin", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                if (response.ok) {
                    console.log("Admin data:", data);
                    setAdmin(data.data);
                } else {
                    console.error("Failed to fetch admins:", data.message);
                }
            } catch (error) {
                console.error("Error fetching admins:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchAdmins();
    }, []);
    const openModal = (admin) => {
        setSelectedAdmin(admin);
        setModalOpen(true);
    };

    const closeModal = () => {
        setSelectedAdmin(null);
        setModalOpen(false);
    };
    const [createModalOpen, setCreateModalOpen] = useState(false);
    const openCreateModal = () => setCreateModalOpen(true);
    const closeCreateModal = () => setCreateModalOpen(false);
    // admin model
    // Open create admin modal

    // Open view admin modal
    const openViewModal = (admin) => {
        setSelectedAdmin(admin);
        setViewAdminOpen(true);
    };

    const closeViewModal = () => {
        setSelectedAdmin(null);
        setViewAdminOpen(false);
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
                // Remove the admin from local state to update UI immediately
                setAdmin((prevUsers) => prevUsers.filter((u) => u.id !== id));

                toast.current.show({
                    severity: "success",
                    summary: "Deleted",
                    detail: data.message || "admin deleted successfully",
                    life: 3000,
                });
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: data.message || "Failed to delete admin",
                    life: 3000,
                });
            }
        } catch (error) {
            console.error("Error deleting admin:", error);
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
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-semibold">Admins</h2>

                <Button
                    icon="pi pi-user-plus"
                    className="p-button-success"
                    onClick={openCreateModal}
                />

                <Dialog
                    header="Create New Admin"
                    visible={createModalOpen}
                    style={{ width: "400px" }}
                    onHide={closeCreateModal}
                >
                    <form action="" onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <label htmlFor="name" className="block mb-1">
                                Name
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                className="w-full border rounded px-3 py-2"
                                placeholder="Enter name"
                                onChange={handleChange}
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="email" className="block mb-1">
                                Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                className="w-full border rounded px-3 py-2"
                                placeholder="Enter email"
                                onChange={handleChange}
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="password" className="block mb-1">
                                Password
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                className="w-full border rounded px-3 py-2"
                                placeholder="Enter password"
                                onChange={handleChange}
                            />
                        </div>
                        <div className="mb-3">
                            <label
                                htmlFor="password-confirmation"
                                className="block mb-1"
                            >
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                id="password-confirmation"
                                name="password_confirmation"
                                className="w-full border rounded px-3 py-2"
                                placeholder="Confirm password"
                                onChange={handleChange}
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="photo" className="block mb-1">
                                Image
                            </label>
                            <input
                                type="file"
                                id="photo"
                                name="photo"
                                className="w-full border rounded px-3 py-2"
                                placeholder="Enter photo URL"
                                onChange={handleChange}
                            />
                        </div>
                        <Button
                            label="Create"
                            className="p-button-success"
                            type="submit"
                        />
                    </form>
                </Dialog>
            </div>
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
                            {admin.map((admin) => (
                                <tr className="border-b" key={admin.id}>
                                    <td>{admin.id}</td>
                                    <td>{admin.name}</td>
                                    <td>{admin.email}</td>
                                    <td>
                                        <img
                                            src={`/${admin.photo}`}
                                            width={50}
                                            height={50}
                                            className="rounded-full object-cover w-12 h-12"
                                            alt={admin.name}
                                        />
                                    </td>
                                    <td>
                                        <Button
                                            label=""
                                            icon="pi pi-eye"
                                            className="p-button-text p-button-info text-blue-600"
                                            onClick={() => openModal(admin)}
                                        />

                                        {"      "}
                                        <Button
                                            icon="pi pi-trash"
                                            className="p-button-text p-button-danger"
                                            onClick={(e) =>
                                                confirmPopup({
                                                    target: e.currentTarget,
                                                    message:
                                                        "Are you sure you want to delete this admin?",
                                                    icon: "pi pi-exclamation-triangle",
                                                    accept: () =>
                                                        handleDelete(admin.id),
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
            {/* admin Modal */}
            {selectedAdmin && (
                <Dialog
                    header="Admin Details"
                    visible={modalOpen}
                    style={{ width: "400px" }}
                    onHide={closeModal}
                >
                    <div className="flex flex-col items-center gap-4">
                        <img
                            src={`/${selectedAdmin.photo}`}
                            alt={selectedAdmin.name}
                            className="rounded-full object-cover w-24 h-24"
                        />
                        <h3 className="text-lg font-semibold">
                            {selectedAdmin.name}
                        </h3>
                        <p>Email: {selectedAdmin.email}</p>

                        {/* Add more details if needed */}
                    </div>
                </Dialog>
            )}
        </div>
    );
};
