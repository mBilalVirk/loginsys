import React, { useEffect, useState } from "react";
import PostItem from "./PostItem";

const PostList = () => {
    const [posts, setPosts] = useState([]);
    const [user, setUser] = useState(null);

    const fetchPosts = async () => {
        try {
            const response = await fetch("/api/user/fetch", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${
                        JSON.parse(localStorage.getItem("user-info")).data.token
                    }`,
                },
            });
            const result = await response.json();
            if (response.ok) {
                setPosts(result.data.post);
                setUser(result.data.user);
            } else {
                console.error("Failed:", result.message);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    };

    useEffect(() => {
        fetchPosts();

        const handleNewPost = () => fetchPosts();
        window.addEventListener("post-created", handleNewPost);

        return () => {
            window.removeEventListener("post-created", handleNewPost);
        };
    }, []);

    return (
        <div className="w-full rounded-lg m-auto ml-10 mt-0">
            {posts.map((post) => (
                <PostItem key={post.id} post={post} user={user} />
            ))}
        </div>
    );
};

export default PostList;
