import { ReactNode } from "react";

export default function Modal({
    show,
    toggleModal,
    acceptText,
    cancelText,
    children,
    onAccept,
    hideButtons = false,
}: {
    show: boolean;
    toggleModal: () => void;
    acceptText?: string;
    cancelText?: string;
    children: ReactNode;
    onAccept?: () => void;
    hideButtons?: boolean;
}) {
    function cancelAction() {
        toggleModal();
    }

    function acceptAction() {
        onAccept && onAccept();
        toggleModal();
    }

    return (
        <>
            <div
                className={`relative z-10 text-gray-800 transition duration-300 ${
                    show
                        ? "opacity-100 pointer-events-auto"
                        : "opacity-0 pointer-events-none"
                }`}
                aria-labelledby="dialog-title"
                role="dialog"
                aria-modal="true"
            >
                <div
                    className="fixed inset-0 bg-gray-500/75 transition-opacity"
                    aria-hidden="true"
                ></div>

                <div className="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div className="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div>{children}</div>
                            </div>
                            {hideButtons ? (
                                <div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button
                                        type="button"
                                        className="cursor-pointer inline-flex w-full justify-center rounded-md bg-purple-500 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-purple-600 transition duration-300 sm:ml-3 sm:w-auto"
                                        onClick={acceptAction}
                                    >
                                        {acceptText ? acceptText : "Aceptar"}
                                    </button>
                                    <button
                                        type="button"
                                        className="cursor-pointer mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                        onClick={cancelAction}
                                    >
                                        {cancelText ? cancelText : "Cancelar"}
                                    </button>
                                </div>
                            ) : null}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
