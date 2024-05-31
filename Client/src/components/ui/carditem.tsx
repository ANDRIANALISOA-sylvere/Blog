import { Badge } from "@/components/ui/badge";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { FC } from "react";
import { NavLink } from "react-router-dom";

interface Post {
    id: number;
    title: string;
    content: string;
    featured_image: string;
    published_at: string;
    user: {
        name: string;
    };
    categories: Category[];
}

interface Category {
    id: number;
    name: string;
}

const carditem: FC<{ post: Post }> = ({ post }) => {
    return (
        <NavLink to={`/posts/${post.id}`} key={post.id}>
            <Card
                className="mt-2 shadow-none flex border-0 relative"
                style={{ cursor: "pointer" }}
            >
                <div className="p-4">
                    <CardHeader>
                        <CardDescription>
                            {post.user.name} {post.published_at}
                        </CardDescription>
                        <CardTitle>
                            <h1 className="text-2xl">{post.title}</h1>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p>
                            {post.content.slice(0, 200)}
                            {post.content.length > 200 ? "..." : ""}
                        </p>
                    </CardContent>
                    <CardFooter>
                        {post.categories.map((category: Category) => {
                            return (
                                <Badge key={category.id} className="m-1">
                                    {category.name}
                                </Badge>
                            );
                        })}
                    </CardFooter>
                </div>
                <div className="absolute inset-y-0 right-0 flex items-center p-4">
                    <img
                        src={post.featured_image}
                        alt={post.title}
                        className="w-60 h-48 object-cover"
                    />
                </div>
            </Card>
        </NavLink>
    );
};

export default carditem;
