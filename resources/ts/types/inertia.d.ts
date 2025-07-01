import { PageProps } from "@inertiajs/inertia";

export interface AuthUser {
    id: number;
    name: string;
    email: string;
}

export type InertiaSharedProps = PageProps & {
    appName: string;
    auth: {
        user: AuthUser | null;
    };
};
