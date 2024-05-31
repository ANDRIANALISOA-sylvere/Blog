import "./App.css";
import { Routes, Route } from "react-router-dom";
import NavBar from "./components/ui/NavBar";
import Home from "./features/posts/Home";
import Post from "./features/posts/Post";
import NotFound from "./components/ui/NotFound";

function App() {
    return (
        <div>
            <NavBar></NavBar>
            <Routes>
                <Route path="/" element={<Home></Home>}></Route>
                <Route path="/posts/:id" element={<Post></Post>}></Route>
                <Route path="*" element={<NotFound></NotFound>}></Route>
            </Routes>
        </div>
    );
}

export default App;
