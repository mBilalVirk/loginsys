import React, { useState } from "react";
import { InputText } from "primereact/inputtext";

const CommentForm = ({ postId }) => {
    const [comment, setComment] = useState("");

    const handleChange = (e) => {
        setComment(e.target.value);
    };

    const giveComment = async (e) => {
        e.preventDefault();
        if (!comment.trim()) return;

        try {
            const response = await fetch("/api/user/comment", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${
                        JSON.parse(localStorage.getItem("user-info")).data.token
                    }`,
                },
                body: JSON.stringify({ comment, post_id: postId.toString() }),
            });
            const result = await response.json();

            if (response.ok) {
                setComment("");
                window.dispatchEvent(new Event("post-created")); // refresh post comments
            } else {
                console.error("Failed:", result.message);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    };

    return (
        <form
            onSubmit={giveComment}
            className="flex items-center gap-2 mt-2 w-full"
        >
            <InputText
                type="text"
                name="comment"
                placeholder="Write comment"
                className="w-full"
                value={comment}
                onChange={handleChange}
            />
            <button type="submit">
                <i className="pi pi-send text-gray-500 cursor-pointer"></i>
            </button>
        </form>
    );
};

export default CommentForm;
