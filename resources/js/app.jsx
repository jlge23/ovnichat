import "./bootstrap";
//import './echo';
import jQuery from "jquery";
window.$ = jQuery;

/* const channel = Echo.channel('Test-channel');
channel.listen('TestEvent', (e) => {
    console.log(e.message);
}); */
/* $(function(){
    window.Echo.channel('Test-channel').listen('TestEvent', (e) => {$("h2").html(e)});
}); */

import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.jsx", { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
