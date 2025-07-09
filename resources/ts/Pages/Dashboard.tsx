import LayoutAuth from "@/Layouts/LayoutAuth";
import { InertiaSharedProps } from "@/types/inertia";
import { Head, usePage } from "@inertiajs/react";

export default function Dashboard() {
    const { appName } = usePage<InertiaSharedProps>().props;

    return (
        <LayoutAuth>
            <Head>
                <title>{appName}</title>
            </Head>
            <div>Dashboard</div>
        </LayoutAuth>
    );
}
