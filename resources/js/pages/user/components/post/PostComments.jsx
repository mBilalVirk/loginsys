import React from "react";

const PostComments = ({ comments, onEdit, onDelete }) => {
    return (
        <div className="mt-3 space-y-2">
            {comments.map((comment) => (
                <div
                    key={comment.id}
                    className="bg-gray-100 p-2 rounded-lg text-sm flex justify-between items-center"
                >
                    <span>{comment.comment}</span>

                    <div className="flex gap-2 ml-2">
                        {/* Edit Button */}
                        <button
                            onClick={() => onEdit(comment.id, comment.comment)}
                            className="text-blue-600 hover:text-blue-800"
                        >
                            <i className="pi pi-pencil"></i>
                        </button>

                        {/* Delete Button */}
                        <button
                            onClick={() => onDelete(comment.id)}
                            className="text-red-600 hover:text-red-800"
                        >
                            <i className="pi pi-trash"></i>
                        </button>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default PostComments;
