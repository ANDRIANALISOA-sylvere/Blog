import { configureStore } from "@reduxjs/toolkit";
import PostReducer from '../features/posts/postSlice'

export const store = configureStore({
    reducer : {
        posts : PostReducer
    }
})

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
export default store;

