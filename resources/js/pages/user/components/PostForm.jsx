import React, { useState, useRef } from "react";
import { InputTextarea } from "primereact/inputtextarea";
import { Button } from "primereact/button";
import { FileUpload } from "primereact/fileupload";
import "primeicons/primeicons.css";
import { Toast } from "primereact/toast";

const PostForm = () => {
    const [content, setContent] = useState("");
    const [photo, setPhoto] = useState(null);
    const [loading, setLoading] = useState(false);
    const toast = useRef(null);
    const handleSubmit = async (e) => {
        e.preventDefault();
        console.log("Submit");
        if (!content.trim()) {
            alert("Post cannot be empty");
            return;
        }

        try {
            setLoading(true);

            const formData = new FormData();

            formData.append("content", content);
            if (photo) {
                formData.append("photo", photo);
            }

            const response = await fetch("/api/user/post", {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${JSON.parse(localStorage.getItem("user-info")).data.token}`,
                },
                body: formData,
            });

            const result = await response.json();

            if (response.ok) {
                toast.current.show({
                    severity: "success",
                    summary: "Success",
                    detail: result.message || "Post Has been Created!",
                    life: 3000,
                });
                setContent("");
                setPhoto(null);
                // After post is successfully created
                window.dispatchEvent(new Event("post-created"));
            } else {
                console.error(result.message);
            }
        } catch (error) {
            console.error("Error creating post:", error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="w-full bg-white shadow-lg rounded-lg p-4 m-auto ml-10 mt-0">
            <Toast ref={toast} />
            <h3 className="text-lg font-semibold mb-3">Create Post</h3>

            <form
                onSubmit={handleSubmit}
                className="space-y-3"
                encType="multipart/form-data"
            >
                {/* Post Text */}
                <InputTextarea
                    value={content}
                    name="content"
                    onChange={(e) => setContent(e.target.value)}
                    rows={3}
                    className="w-full"
                    placeholder="What's on your mind?"
                />

                {/* Image Upload */}
                <div className="grid grid-cols-1 content-between gap-5">
                    <FileUpload
                        mode="basic"
                        name="photo"
                        accept="image/*"
                        maxFileSize={1000000}
                        customUpload
                        auto={false}
                        chooseLabel="Upload Image"
                        onSelect={(e) => setPhoto(e.files[0])}
                    />

                    {/* Submit Button */}
                    <Button
                        label={loading ? "Posting..." : "Post"}
                        icon="pi pi-send"
                        type="submit"
                        disabled={loading}
                        className="w-full"
                    />
                </div>
            </form>
        </div>
    );
};

export default PostForm;
