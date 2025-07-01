import { router } from "@inertiajs/react";
import { FormEvent } from "react";

export default function Dashboard() {
    function handleSubmit(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();

        router.post("/logout");
    }

    return (
        <div>
            Dashboard
            <form onSubmit={handleSubmit}>
                <button type="submit">cerrar sesion</button>
            </form>
        </div>
    );
}
