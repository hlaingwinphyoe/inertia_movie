import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        colors: {
            "primary-100": "#CBFDFB",
            "primary-200": "#98F7FB",
            "primary-300": "#64E3F3",
            "primary-400": "#3DC9E7",
            "primary-500": "#04A3D8",
            "primary-600": "#027FB9",
            "primary-700": "#025F9B",
            "primary-800": "#01437D",
            "primary-900": "#003067",
        },
        screens: {
            sm: "640px",
            // => @media (min-width: 640px) { ... }

            md: "768px",
            // => @media (min-width: 768px) { ... }

            lg: "1024px",
            // => @media (min-width: 1024px) { ... }

            xl: "1280px",
            // => @media (min-width: 1280px) { ... }

            "2xl": "1536px",
            // => @media (min-width: 1536px) { ... }

            minsm: { min: "641px" },
            minmd: { min: "769px" },
            minlg: { min: "1025px" },
            minxl: { min: "1281px" },
            min2xl: { min: "1537px" },
        },
        container: {
            padding: "1rem",
        },
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, require("flowbite/plugin")],
};
