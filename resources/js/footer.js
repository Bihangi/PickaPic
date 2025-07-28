// footer.js

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".animate-fade-in-left").forEach(el => {
        el.classList.add("opacity-0", "translate-x-[-20px]");
        setTimeout(() => {
            el.classList.remove("opacity-0", "translate-x-[-20px]");
            el.classList.add("transition", "duration-1000", "ease-out", "opacity-100", "translate-x-0");
        }, 200);
    });

    document.querySelectorAll(".animate-fade-in-right").forEach(el => {
        el.classList.add("opacity-0", "translate-x-[20px]");
        setTimeout(() => {
            el.classList.remove("opacity-0", "translate-x-[20px]");
            el.classList.add("transition", "duration-1000", "ease-out", "opacity-100", "translate-x-0");
        }, 300);
    });
});
