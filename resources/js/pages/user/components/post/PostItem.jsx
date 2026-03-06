import React, { useState } from "react";
import PostComments from "./PostComments";
import CommentForm from "./CommentForm";
import "primeicons/primeicons.css";

const PostItem = ({ post, user }) => {
    // State for comments
    const [comments, setComments] = useState(post.comments);

    // Edit comment
    const editComment = (id, text) => {
        // Prefill CommentForm for editing
        setEditingId(id);
        setCommentText(text);
    };

    // Delete comment
    const deleteComment = async (id) => {
        try {
            const response = await fetch(`/api/user/comment/delete/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${
                        JSON.parse(localStorage.getItem("user-info")).data.token
                    }`,
                },
            });
            const data = await response.json();

            if (response.ok) {
                // Remove comment from state
                setComments((prev) => prev.filter((c) => c.id !== id));

                console.log(data.message);
            } else {
                console.error("Failed to delete comment:", data.message);
            }
        } catch (error) {
            console.error("Error deleting comment:", error);
        }
    };

    // Add new comment
    const addComment = (newComment) => {
        setComments((prev) => [...prev, newComment]);
    };

    // Update comment after edit
    const updateComment = (updatedComment) => {
        setComments((prev) =>
            prev.map((c) => (c.id === updatedComment.id ? updatedComment : c)),
        );
    };

    // State for CommentForm editing
    const [editingId, setEditingId] = useState(null);
    const [commentText, setCommentText] = useState("");

    return (
        <div className="bg-white shadow-md rounded-xl p-4 mt-2">
            {/* USER INFO */}
            <div className="flex items-center gap-3 mb-3">
                <img
                    src={`http://127.0.0.1:8000/${user?.photo}`}
                    alt="user"
                    className="w-10 h-10 rounded-full object-cover"
                />
                <div>
                    <h4 className="font-semibold text-sm">{user?.name}</h4>
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
                    {comments.length} Comments
                </span>
            </div>

            {/* COMMENTS SECTION */}
            <PostComments
                comments={post.comments}
                onEdit={editComment}
                onDelete={deleteComment}
            />

            {/* COMMENT FORM */}
            <CommentForm
                postId={post.id}
                onAdd={addComment}
                onUpdate={updateComment}
                editingId={editingId}
                commentText={commentText}
                setEditingId={setEditingId}
                setCommentText={setCommentText}
            />
        </div>
    );
};

export default PostItem;
