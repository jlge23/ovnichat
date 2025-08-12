import Swal from "sweetalert2";

export const toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toastEl) => {
        toastEl.onmouseenter = Swal.stopTimer;
        toastEl.onmouseleave = Swal.resumeTimer;
    },
});

export const showToast = (
    icon: "success" | "error" | "warning" | "info",
    title: string
) => {
    toast.fire({ icon, title });
};
