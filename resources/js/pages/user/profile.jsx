import React from "react";
import Navbar from "./components/navbar";
import Messenger from "./messenger";
import Sidebar from "./components/sidebar";
import WritePost from "./components/PostForm";
import Post from "./components/Post";

const Profile = () => {
    // Add safety check
    const userData = JSON.parse(localStorage.getItem("user-info"));
    const user = userData?.data?.user;
    const authUserName = userData.data.user.name;
    const authUserImg = userData.data.user.photo;
    if (!user) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <p className="text-lg">Please log in to view your profile</p>
            </div>
        );
    }

    const { name, photo } = user; // photo is usually a full URL or path like "/uploads/..."

    // You might want a separate cover photo in the future
    // For now we'll use the profile photo as fallback banner
    const bannerImage =
        photo ||
        "https://images.unsplash.com/photo-1557683316-973673baf926?w=1600"; // fallback

    return (
        <div className="min-h-screen bg-gray-50">
            <Navbar />
            <Messenger />

            <div className="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 pb-12">
                {/* Profile Banner */}
                <div className="relative h-48 sm:h-64 md:h-80 lg:h-96 overflow-hidden rounded-b-xl shadow-lg">
                    <img
                        src="https://images.unsplash.com/photo-1557683316-973673baf926?w=1600"
                        alt="Profile banner"
                        className="absolute inset-0 w-full h-full object-cover object-center"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent" />

                    {/* Profile picture + name overlay */}
                    <div className="absolute bottom-6 left-6 sm:left-10 flex items-end gap-4">
                        <div className="relative">
                            <img
                                src={`/${authUserImg}`}
                                alt={authUserName}
                                className="w-28 h-28 sm:w-32 sm:h-32 md:w-40 md:h-40 object-cover rounded-full border-4 border-white shadow-xl"
                            />
                        </div>

                        <div className="text-white pb-3">
                            <h1 className="text-2xl sm:text-3xl md:text-4xl font-bold drop-shadow-lg">
                                {authUserName}
                            </h1>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-[minmax(240px,280px)_1fr] lg:grid-cols-[300px_1fr]  mt-6 md:mt-8">
                    <div className="hidden md:block">
                        <Sidebar />
                    </div>

                    {/* Center column - posts */}
                    <div className="flex flex-col ">
                        <WritePost />
                        {/* In real app: map over user's posts */}
                        <Post />

                        {/* ... or show "No posts yet" when empty */}
                    </div>

                    {/* Optional right column for suggestions/friends/etc */}
                    {/* <div className="hidden lg:block"> ... </div> */}
                </div>
            </div>
        </div>
    );
};

export default Profile;
