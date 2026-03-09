import React, { useEffect, useState, useRef } from "react";
import { Button } from "primereact/button";
import { Dialog } from "primereact/dialog";
import { Toast } from "primereact/toast";
import { Paginator } from "primereact/paginator";

const PostsTable = () => {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedPost, setSelectedPost] = useState(null);
    const [modalOpen, setModalOpen] = useState(false);
    const [first, setFirst] = useState(0); // starting index for pagination
    const [rows, setRows] = useState(5); // posts per page
    const toast = useRef(null);

    useEffect(() => {
        const fetchPosts = async () => {
            setLoading(true);
            const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
            const token = adminInfo.data.token;

            try {
                const response = await fetch("/api/admin/post", {
                    headers: { Authorization: `Bearer ${token}` },
                });
                const data = await response.json();
                if (response.ok) {
                    const allPosts = data.data.flatMap((user) =>
                        user.posts.map((post) => ({
                            ...post,
                            userName: user.name,
                            userEmail: user.email,
                            userPhoto: user.photo,
                        })),
                    );
                    setPosts(allPosts);
                } else {
                    console.error("Failed to fetch posts:", data.message);
                }
            } catch (error) {
                console.error("Error fetching posts:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchPosts();
    }, []);

    const openModal = (post) => {
        setSelectedPost(post);
        setModalOpen(true);
    };

    const closeModal = () => {
        setSelectedPost(null);
        setModalOpen(false);
    };

    const handleDelete = async (id) => {
        const adminInfo = JSON.parse(localStorage.getItem("admin-info"));
        const token = adminInfo.data.token;

        try {
            const response = await fetch(`/api/admin/delete-post/${id}`, {
                method: "DELETE",
                headers: { Authorization: `Bearer ${token}` },
            });
            const data = await response.json();
            if (response.ok) {
                setPosts(posts.filter((post) => post.id !== id));
                toast.current.show({
                    severity: "success",
                    summary: "Deleted",
                    detail: data.message || "Post deleted successfully",
                    life: 3000,
                });
            }
        } catch (error) {
            console.error("Error deleting post:", error);
            toast.current.show({
                severity: "error",
                summary: "Error",
                detail: "Something went wrong!",
                life: 3000,
            });
        }
    };

    // Pagination slice
    const paginatedPosts = posts.slice(first, first + rows);

    return (
        <div className="bg-white rounded shadow p-6">
            <Toast ref={toast} />
            <h2 className="text-lg font-semibold mb-4">All Posts</h2>

            {loading ? (
                <i
                    className="pi pi-spin pi-spinner"
                    style={{ fontSize: "2rem" }}
                ></i>
            ) : (
                <>
                    <div className="overflow-x-auto">
                        <table className="min-w-full text-left">
                            <thead className="border-b">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Post ID</th>
                                    <th>Content</th>
                                    <th>Comments</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {paginatedPosts.map((post) => (
                                    <tr key={post.id} className="border-b">
                                        <td>{post.userName}</td>
                                        <td>{post.userEmail}</td>
                                        <td>{post.id}</td>
                                        <td>
                                            {post.content.length > 30
                                                ? post.content.slice(0, 30) +
                                                  "..."
                                                : post.content}
                                        </td>
                                        <td>{post.comments.length}</td>
                                        <td>
                                            {new Date(
                                                post.created_at,
                                            ).toLocaleDateString()}
                                        </td>
                                        <td>
                                            <Button
                                                icon="pi pi-eye"
                                                className="p-button-rounded p-button-text p-button-info"
                                                onClick={() => openModal(post)}
                                            />
                                            <Button
                                                icon="pi pi-trash"
                                                className="p-button-rounded p-button-text p-button-danger ml-2"
                                                onClick={() =>
                                                    handleDelete(post.id)
                                                }
                                            />
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Paginator */}
                    <Paginator
                        first={first}
                        rows={rows}
                        totalRecords={posts.length}
                        rowsPerPageOptions={[5, 10, 20]}
                        onPageChange={(e) => {
                            setFirst(e.first);
                            setRows(e.rows);
                        }}
                        className="mt-4"
                    />
                </>
            )}

            {/* Post Details Modal */}
            {selectedPost && (
                <Dialog
                    header="Post Details"
                    visible={modalOpen}
                    style={{ width: "500px" }}
                    onHide={closeModal}
                >
                    <div className="flex flex-col gap-4">
                        <h3>{selectedPost.userName}</h3>
                        <p>{selectedPost.content}</p>
                        <img
                            src={`/${selectedPost.userPhoto}`}
                            alt={selectedPost.userName}
                            className="rounded object-cover w-32 h-32"
                        />
                        <p>Comments: {selectedPost.comments.length}</p>
                    </div>
                </Dialog>
            )}
        </div>
    );
};

export default PostsTable;
