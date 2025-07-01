import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, usePage } from "@inertiajs/react";

export default function Dashboard() {
    const { appName } = usePage().props;

    return (
        <LayoutAuth>
            <Head>
                <title>{appName}</title>
            </Head>
            <div>Dashboard</div>
        </LayoutAuth>
    );
}
