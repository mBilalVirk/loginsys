import React, { useState } from "react";
import { Link } from "react-router-dom";
import { Toast } from "primereact/toast";
import { useRef } from "react";

const Register = () => {
    const [input, setInput] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        photo: null,
    });

    const toast = useRef(null);
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

        const formData = new FormData();
        formData.append("name", input.name);
        formData.append("email", input.email);
        formData.append("password", input.password);
        formData.append("password_confirmation", input.password_confirmation);
        formData.append("photo", input.photo);

        try {
            const response = await fetch("/api/user/register", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                },
                body: formData,
            });

            const data = await response.json();

            if (response.ok) {
                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail: data.message,
                    life: 3000,
                });
                setInput({
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                    photo: null,
                });
                e.target.reset();
            } else if (response.status === 422) {
                Object.values(data.errors).forEach((err) => {
                    toast.current.show({
                        severity: "error",
                        summary: "Validation Error",
                        detail: err[0],
                        life: 4000,
                    });
                });
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: "Something went wrong!",
                    life: 3000,
                });
            }
        } catch (error) {
            toast.current.show({
                severity: "error",
                summary: "Server Error",
                detail: "Unable to connect to server",
                life: 3000,
            });
        }
    };

    return (
        <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <Toast ref={toast} />
            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 className="mt-10 text-center text-2xl font-bold text-black">
                    Register your account
                </h2>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form
                    className="space-y-6"
                    onSubmit={handleSubmit}
                    encType="multipart/form-data"
                >
                    <input
                        name="name"
                        type="text"
                        placeholder="Name"
                        onChange={handleChange}
                        className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-blue-800 outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        required
                    />

                    <input
                        name="email"
                        type="email"
                        placeholder="Email"
                        onChange={handleChange}
                        className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-blue-800 outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        required
                    />

                    <input
                        name="password"
                        type="password"
                        placeholder="Password"
                        onChange={handleChange}
                        className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-blue-800 outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        required
                    />

                    <input
                        name="password_confirmation"
                        type="password"
                        placeholder="Confirm Password"
                        onChange={handleChange}
                        className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-blue-800 outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        required
                    />

                    <input
                        name="photo"
                        type="file"
                        onChange={handleChange}
                        className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-blue-800 outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                    />

                    <button
                        type="submit"
                        className="w-full bg-indigo-500 text-white p-2 rounded"
                    >
                        Register
                    </button>
                </form>

                <p className="mt-4 text-center">
                    Already have account?{" "}
                    <Link
                        to="/"
                        className="font-semibold text-indigo-400 hover:text-indigo-300"
                    >
                        Login
                    </Link>
                </p>
            </div>
        </div>
    );
};

export default Register;
