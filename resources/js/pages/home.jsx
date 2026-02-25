import React from "react";
import { Link } from "react-router-dom";
import Messenger from "./user/messenger";
import Navebar from "./user/components/navbar";
const home = () => {
    return (
        <div class="mx-auto w-full max-w-screen-2xl px-4 sm:px-6 lg:px-8 xl:px-12">
            <div
                class="grid min-h-screen grid-cols-1 gap-4 
              lg:grid-cols-[240px_1fr_260px] 
              lg:grid-rows-[auto_1fr_auto]"
            >
                <Navebar />
                <aside class="bg-blue-400 p-6 lg:p-8 lg:row-start-2">
                    Left sidebar
                </aside>

                <main class="bg-black text-white p-6 lg:p-10 lg:row-start-2">
                    Main content
                </main>

                <aside class="bg-red-500 p-6 lg:p-8 lg:row-start-2">
                    Right sidebar
                </aside>

                <footer class="col-span-1 lg:col-span-3 bg-gray-700 py-8 text-center text-white">
                    Footer — © 2026 Your Company
                </footer>
            </div>
        </div>
    );
};

export default home;
