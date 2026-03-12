import React, { useState } from "react";

const Search = () => {
    const [searchData, setSearchData] = useState([]);
    const [searched, setSearched] = useState(false);
    const [open, setOpen] = useState(false);
    const [category, setCategory] = useState("Categories");
    const [input, setInput] = useState({
        search: "",
    });

    const selectCategory = (value) => {
        setCategory(value);
        setOpen(false);
    };

    const handleChange = (e) => {
        setInput({
            ...input,
            [e.target.name]: e.target.value,
        });
    };

    const handleSearch = async (e) => {
        e.preventDefault();
        const admin = JSON.parse(localStorage.getItem("admin-info"));
        const token = admin.data.token;
        const formData = {
            category: category,
            search: input.search,
        };

        console.log(formData);

        // Example API call
        // const res = await fetch(`/api/search?type=${category}&query=${input.search}`);
        try {
            const response = await fetch(
                `/api/admin/search?category=${category}&query=${input.search}`,
                {
                    method: "get",
                    headers: {
                        Accept: "application/json",
                        // "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            const data = await response.json();
            if (response.ok) {
                console.log(data.data);
                setSearchData(data.data);
                setSearched(true);
            } else {
                setSearchData([]);
                console.log("Not Fetch anything");
            }
        } catch (error) {
            console.log(error);
        }
    };

    return (
        <div className="bg-white rounded shadow p-6">
            <h2 className="text-lg font-semibold mb-4">Search:</h2>

            <div className="relative mt-2 w-full max-w-sm min-w-[400px] m-auto">
                <form onSubmit={handleSearch}>
                    {/* Dropdown */}
                    <div className="absolute top-1 left-1 flex items-center">
                        <button
                            type="button"
                            onClick={() => setOpen(!open)}
                            className="rounded border border-transparent py-1 px-1.5 flex items-center text-sm text-slate-600"
                        >
                            <span>{category}</span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                strokeWidth="1.5"
                                stroke="currentColor"
                                className="h-4 w-4 ml-1"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                                />
                            </svg>
                        </button>

                        <div className="h-6 border-l border-slate-200" />

                        {open && (
                            <div className="absolute left-0 mt-10 w-[150px] bg-white border border-slate-200 rounded-md shadow-lg z-10">
                                <ul>
                                    <li
                                        onClick={() => selectCategory("admins")}
                                        className="px-4 py-2 hover:bg-slate-50 cursor-pointer"
                                    >
                                        Admins
                                    </li>
                                    <li
                                        onClick={() => selectCategory("users")}
                                        className="px-4 py-2 hover:bg-slate-50 cursor-pointer"
                                    >
                                        Users
                                    </li>
                                    <li
                                        onClick={() => selectCategory("posts")}
                                        className="px-4 py-2 hover:bg-slate-50 cursor-pointer"
                                    >
                                        Posts
                                    </li>
                                </ul>
                            </div>
                        )}
                    </div>

                    {/* Search Input */}
                    <input
                        type="text"
                        name="search"
                        value={input.search}
                        onChange={handleChange}
                        className="w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded-md px-28 py-2 focus:outline-none"
                        placeholder="Search..."
                    />

                    {/* Submit Button */}
                    <button
                        className="absolute top-1 right-1 rounded bg-slate-800 py-1 px-2.5 text-sm text-white"
                        type="submit"
                    >
                        Search
                    </button>
                </form>
            </div>
            <div className="flex flex-wrap justify-start gap-4">
                {searchData.map((item) => (
                    <div key={item.id}>
                        {item.role === "user" || item.role === "admin" ? (
                            <div className="mx-auto right-0 mt-2 w-60 ">
                                <div className="bg-white rounded overflow-hidden shadow-lg">
                                    <div className="text-center p-6 bg-gray-700 border-b relative">
                                        <p className="absolute top-2 right-2 text-xs text-gray-100">
                                            {item.role.toUpperCase()}
                                        </p>
                                        <div className="relative w-16 h-16 mx-auto">
                                            {item.photo ? (
                                                <img
                                                    src={`/${item.photo}`}
                                                    alt={item.name}
                                                    className="h-16 w-16 rounded-full object-cover"
                                                />
                                            ) : (
                                                <svg
                                                    aria-hidden="true"
                                                    role="img"
                                                    className="h-16 w-16 text-gray-300 rounded-full"
                                                    viewBox="0 0 256 256"
                                                    fill="currentColor"
                                                >
                                                    <path d="M172 120a44 44 0 1 1-44-44a44 44 0 0 1 44 44Zm60 8A104 104 0 1 1 128 24a104.2 104.2 0 0 1 104 104Zm-16 0a88 88 0 1 0-153.8 58.4a81.3 81.3 0 0 1 24.5-23a59.7 59.7 0 0 0 82.6 0a81.3 81.3 0 0 1 24.5 23A87.6 87.6 0 0 0 216 128Z" />
                                                </svg>
                                            )}
                                        </div>
                                        <p className="pt-2 text-lg font-semibold text-gray-50">
                                            {item.name}
                                        </p>
                                        <p className="text-sm text-gray-100">
                                            {item.email}
                                        </p>

                                        <div className="mt-5">
                                            <a className="border rounded-full py-2 px-4 text-xs font-semibold text-gray-100">
                                                Manage Account
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className=" flex items-center justify-center">
                                <div className="bg-white p-8 rounded-lg shadow-md max-w-md">
                                    {/* User Info with Three-Dot Menu */}
                                    <div className="flex items-center justify-between mb-4">
                                        <div className="flex items-center space-x-2">
                                            <img
                                                src={`/${item.user.photo}`}
                                                alt="User Avatar"
                                                className="w-8 h-8 rounded-full"
                                            />
                                            <div>
                                                <p className="text-gray-800 font-semibold">
                                                    {item.user.name}
                                                </p>
                                                <p className="text-gray-500 text-sm">
                                                    {new Date(
                                                        item.created_at,
                                                    ).toLocaleDateString()}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="text-gray-500 cursor-pointer">
                                            {/* Three-dot menu icon */}
                                            <button className="hover:bg-gray-50 rounded-full p-1">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width={24}
                                                    height={24}
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    strokeWidth={2}
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                >
                                                    <circle
                                                        cx={12}
                                                        cy={7}
                                                        r={1}
                                                    />
                                                    <circle
                                                        cx={12}
                                                        cy={12}
                                                        r={1}
                                                    />
                                                    <circle
                                                        cx={12}
                                                        cy={17}
                                                        r={1}
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    {/* Message */}
                                    <div className="mb-4">
                                        <p className="text-gray-800">
                                            {item.content}{" "}
                                        </p>
                                    </div>
                                    {/* Image */}
                                    <div className="mb-4">
                                        <img
                                            src={`/${item.photo}`}
                                            alt="Post Image"
                                            className="w-full h-48 object-cover rounded-md"
                                        />
                                    </div>
                                    {/* Like and Comment Section */}
                                    <div className="flex items-center justify-between text-gray-500">
                                        <div className="flex items-center space-x-2">
                                            <button className="flex justify-center items-center gap-2 px-2 hover:bg-gray-50 rounded-full p-1">
                                                <svg
                                                    className="w-5 h-5 fill-current"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path d="M12 21.35l-1.45-1.32C6.11 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-4.11 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                                <span>42 Likes</span>
                                            </button>
                                        </div>
                                        <button className="flex justify-center items-center gap-2 px-2 hover:bg-gray-50 rounded-full p-1">
                                            <svg
                                                width="22px"
                                                height="22px"
                                                viewBox="0 0 24 24"
                                                className="w-5 h-5 fill-current"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <g
                                                    id="SVGRepo_bgCarrier"
                                                    strokeWidth={0}
                                                />
                                                <g
                                                    id="SVGRepo_tracerCarrier"
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                />
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        fillRule="evenodd"
                                                        clipRule="evenodd"
                                                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 13.5997 2.37562 15.1116 3.04346 16.4525C3.22094 16.8088 3.28001 17.2161 3.17712 17.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L6.39939 20.8229C6.78393 20.72 7.19121 20.7791 7.54753 20.9565C8.88837 21.6244 10.4003 22 12 22ZM8 13.25C7.58579 13.25 7.25 13.5858 7.25 14C7.25 14.4142 7.58579 14.75 8 14.75H13.5C13.9142 14.75 14.25 14.4142 14.25 14C14.25 13.5858 13.9142 13.25 13.5 13.25H8ZM7.25 10.5C7.25 10.0858 7.58579 9.75 8 9.75H16C16.4142 9.75 16.75 10.0858 16.75 10.5C16.75 10.9142 16.4142 11.25 16 11.25H8C7.58579 11.25 7.25 10.9142 7.25 10.5Z"
                                                    />
                                                </g>
                                            </svg>
                                            <span>{item.comments.length}</span>
                                        </button>
                                    </div>
                                    <hr className="mt-2 mb-2" />
                                    <p className="text-gray-800 font-semibold">
                                        Comment
                                    </p>
                                    <hr className="mt-2 mb-2" />
                                    <div className="mt-4">
                                        {/* Comment 1 */}

                                        {/* Comment 2 */}

                                        {/* Reply from John Doe with indentation */}

                                        {/* Add more comments and replies as needed */}
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                ))}

                {searched && searchData.length === 0 && (
                    <p className="bg-amber-200 mt-2 ml-2 max-w-100 p-2">
                        No results found:
                    </p>
                )}
            </div>
        </div>
    );
};

export default Search;
