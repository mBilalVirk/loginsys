import React, { useState, useRef } from "react";
import { Link } from "react-router-dom";
import { Toast } from "primereact/toast";
import { useNavigate } from "react-router-dom";
const ForgetPassword = () => {
    const toast = useRef(null);
    const navigate = useNavigate();

    const [input, setInput] = useState({
        email: "",
    });

    const handleChange = (e) => {
        setInput({
            email: e.target.value,
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await fetch(
                "http://127.0.0.1:8000/api/forgot-password",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        email: input.email,
                    }),
                },
            );

            let data;
            try {
                data = await response.json();
            } catch {
                data = {};
            }

            if (response.ok) {
                localStorage.setItem("user-info", JSON.stringify(data));

                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail:
                        data.message ||
                        "Password reset instructions sent to your email",
                    life: 3000,
                });
            } else if (response.status === 401) {
                toast.current.show({
                    severity: "error",
                    summary: "Password Reset Failed",
                    detail: data.message || "Invalid email address",
                    life: 3000,
                });
            } else {
                toast.current.show({
                    severity: "error",
                    summary: "Error",
                    detail: data.message || "Something went wrong",
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
            console.error("Password reset error:", error);
        }
    };
    return (
        <>
            <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                <Toast ref={toast} />
                <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                    <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">
                        Reset your password
                    </h2>
                </div>

                <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form
                        action="#"
                        method="POST"
                        className="space-y-6"
                        onSubmit={handleSubmit}
                    >
                        <div>
                            <label
                                htmlFor="email"
                                className="block text-sm/6 font-medium text-black"
                            >
                                Email address
                            </label>
                            <div className="mt-2">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    required
                                    autoComplete="email"
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-blue-800 outline-1 -outline-offset-1 outline-blue-400 placeholder-blue-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                    onChange={handleChange}
                                />
                            </div>
                        </div>

                        <div>
                            <button
                                type="submit"
                                className="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                            >
                                Send Reset Instructions
                            </button>
                        </div>
                    </form>

                    <p className="mt-10 text-center text-sm/6 text-gray-400">
                        <Link
                            to="/register"
                            className="font-semibold text-indigo-400 hover:text-indigo-300"
                        >
                            Register Now
                        </Link>
                        <span className="mx-2">|</span>
                        <Link
                            to="/"
                            className="font-semibold text-indigo-400 hover:text-indigo-300"
                        >
                            Sign In
                        </Link>
                    </p>
                </div>
            </div>
        </>
    );
};

export default ForgetPassword;
