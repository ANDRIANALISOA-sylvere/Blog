import { useDispatch, useSelector } from "react-redux";
import { useEffect } from "react";
import { AppDispatch } from "@/redux/store";
import { fetchPosts } from "./postSlice";
import SpinnerXlBasicHalf from "@/components/ui/spinner";
import Carditem from "@/components/ui/carditem";

interface RootState {
    posts: {
        posts: any[];
        status: "idle" | "pending" | "succeeded" | "failed";
        error: string | null;
    };
}

function Home() {
    const dispatch = useDispatch<AppDispatch>();
    const posts = useSelector((state: RootState) => state.posts.posts);
    const status = useSelector((state: RootState) => state.posts.status);
    const error = useSelector((state: RootState) => state.posts.error);

    useEffect(() => {
        if (status === "idle") {
            dispatch(fetchPosts());
        }
    }, [dispatch, status]);

    console.log(posts);

    return (
        <div>
            <div className="flex">
                <div className="flex-1 p-4"></div>
                <div className="flex-2 p-1">
                    {status === "pending" && (
                        <SpinnerXlBasicHalf></SpinnerXlBasicHalf>
                    )}
                    {status === "failed" && (
                        <p>Erreur de chargement des posts {error}.</p>
                    )}
                    {status === "succeeded" &&
                        posts.map((post, index) => {
                            return (
                                <>
                                    <Carditem
                                        post={post}
                                    ></Carditem>
                                    {index !== posts.length - 1 && (
                                        <hr className="my-4" />
                                    )}
                                </>
                            );
                        })}
                </div>
                <div className="flex-2 p-4">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Quod, laborum?
                </div>
            </div>
        </div>
    );
}

export default Home;
