import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import * as postApi from "../../api/postApi";

interface PostState {
    posts: any[];
    post: any | null;
    status: 'idle' | 'pending' | 'succeeded' | 'failed';
    error: string | null | undefined;
}
const initialState: PostState = {
    posts: [],
    post: null,
    status: 'idle',
    error: null
};

export const fetchPosts = createAsyncThunk("posts/fetchPosts", async () => {
    const response = await postApi.getPosts();
    return response.data;
});

export const fetchPostById = createAsyncThunk(
    "posts/fetchPostById",
    async (id: number) => {
        const response = await postApi.getPostById(id);
        return response.data;
    }
);

const postSlice = createSlice({
    name: "posts",
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchPosts.pending, (state) => {
                state.status = "pending";
            })
            .addCase(fetchPosts.fulfilled, (state, action) => {
                state.posts = action.payload;
                state.status = "succeeded";
            })
            .addCase(fetchPosts.rejected, (state, action) => {
                state.status = "failed";
                state.error = action.error.message;
            })
            .addCase(fetchPostById.pending, (state) => {
                state.status = "pending";
            })
            .addCase(fetchPostById.fulfilled, (state, action) => {
                state.post = action.payload;
                state.status = "succeeded";
            })
            .addCase(fetchPostById.rejected, (state, action) => {
                state.status = "failed";
                state.error = action.error.message;
            });
    },
});

export default postSlice.reducer;
