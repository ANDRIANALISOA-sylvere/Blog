import { useEffect } from "react";
import { useSelector, useDispatch } from "react-redux";
import { fetchPostById } from "./postSlice";
import { useParams } from "react-router-dom";
import { AppDispatch,RootState  } from "@/redux/store";
import SpinnerXlBasicHalf from "@/components/ui/spinner";

interface Post {
    title: string;
}


function Post() {
    const dispatch = useDispatch<AppDispatch>();
    const post = useSelector((state: RootState) => state.posts.post);
    const status = useSelector((state: RootState) => state.posts.status);
    const error = useSelector((state: RootState) => state.posts.error);
    const { id } = useParams<{ id: string }>();
    const numericId = Number(id);

    useEffect(() => {
        if (status === "idle" || post === null) {
            dispatch(fetchPostById(numericId));
        }
    }, [numericId, dispatch , status]);

    console.log(post);

    return (
        <div>
            {status === "pending" && <SpinnerXlBasicHalf></SpinnerXlBasicHalf>}
            {status === "failed" && (
                <p>Erreur de chargement des posts {error}.</p>
            )}
            {post && <p>{post.slug}</p>}
        </div>
    );
}

export default Post;
