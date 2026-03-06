import React, { useEffect, useState } from "react";
import "primeicons/primeicons.css";

import { InputText } from "primereact/inputtext";

const Post = () => {
    const [posts, setPosts] = useState([]);
    const [user, setUser] = useState(null);
    const [comment, setComment] = useState({
        comment: "",
        post_id: "",
        parent_id: "",
    });
    const handleChange = (e) => {
        setComment({
            ...comment,
            [e.target.name]: e.target.value,
        });
    };
    const giveComment = async (e) => {
        e.preventDefault();
        console.log("comment");
    };

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
        // Listen for new posts
        const handleNewPost = () => {
            fetchPosts(); // fetch again whenever new post is created
        };

        window.addEventListener("post-created", handleNewPost);

        return () => {
            window.removeEventListener("post-created", handleNewPost);
        };
    }, []);

    return (
        <div className="w-full  rounded-lg m-auto ml-10 mt-0">
            {posts.map((post) => (
                <div
                    key={post.id}
                    className="bg-white shadow-md rounded-xl p-4 mt-2"
                >
                    {/* USER INFO */}
                    <div className="flex items-center gap-3 mb-3">
                        <img
                            src={`http://127.0.0.1:8000/${user?.photo}`}
                            alt="user"
                            className="w-10 h-10 rounded-full object-cover"
                        />
                        <div>
                            <h4 className="font-semibold text-sm">
                                {user?.name}
                            </h4>
                            <p className="text-xs text-gray-500">
                                {new Date(post.created_at).toLocaleString()}
                            </p>
                        </div>
                    </div>

                    {/* POST CONTENT */}
                    <p className="mb-3 text-sm">{post.content}</p>

                    {/* POST IMAGE */}
                    {post.photo && (
                        <img
                            src={`http://127.0.0.1:8000/${post.photo}`}
                            alt="post"
                            className="w-[50%] rounded-lg mb-3"
                        />
                    )}

                    {/* LIKE / COMMENT BAR */}
                    <div className="flex justify-between text-gray-500 text-sm border-t pt-2">
                        <span>
                            <i className="pi pi-thumbs-up mr-1"></i> Like
                        </span>
                        <span>
                            <i className="pi pi-comments mr-1"></i>
                            {post.comments.length} Comments
                        </span>
                    </div>

                    {/* COMMENTS SECTION */}
                    <div className="mt-3 space-y-2">
                        {post.comments.map((comment) => (
                            <div
                                key={comment.id}
                                className="bg-gray-100 p-2 rounded-lg text-sm"
                            >
                                {comment.comment}
                            </div>
                        ))}
                    </div>
                    <div className="w-full mt-2">
                        <h5>Give Comments</h5>
                        <div>
                            <form
                                action=""
                                method=""
                                className="flex items-center gap-2 mt-2"
                                onSubmit={giveComment}
                            >
                                <InputText
                                    type="text"
                                    id="comment"
                                    name="comment"
                                    placeholder="Write comment"
                                    className="w-full"
                                    onChange={handleChange}
                                />
                                <input
                                    type="text"
                                    name="post_id"
                                    id="post_id"
                                    value={post.id}
                                    hidden
                                    onChange={handleChange}
                                />
                                <input
                                    type="text"
                                    name="parent_id"
                                    id="parent_id"
                                    value={comment.id}
                                    hidden
                                    onChange={handleChange}
                                />
                                <button type="submit">
                                    <i className="pi pi-send text-gray-500 cursor-pointer"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default Post;
