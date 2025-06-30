import LayoutGuest from "@/Layouts/LayoutGuest";
import { Head, usePage } from "@inertiajs/react";

export default function Login() {
    const { props } = usePage();

    return (
        <LayoutGuest>
            <Head>
                <title>{props.appName} - Iniciar Sesión</title>
            </Head>
        </LayoutGuest>
    );
}
