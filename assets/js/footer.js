document.addEventListener("DOMContentLoaded", function () {
    // Dynamic Year Update
    const yearSpan = document.getElementById("footer-year");
    if (yearSpan) {
        yearSpan.textContent = new Date().getFullYear();
    }

    // Smooth Scroll for Footer Links
    document.querySelectorAll(".footer-nav a").forEach(link => {
        link.addEventListener("click", function (event) {
            const targetId = this.getAttribute("href").substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                event.preventDefault();
                window.scrollTo({
                    top: targetElement.offsetTop - 50,
                    behavior: "smooth"
                });
            }
        });
    });

    // Back to Top Button
    const backToTopBtn = document.createElement("button");
    backToTopBtn.innerText = "↑";
    backToTopBtn.id = "back-to-top";
    document.body.appendChild(backToTopBtn);

    backToTopBtn.style.position = "fixed";
    backToTopBtn.style.bottom = "20px";
    backToTopBtn.style.right = "20px";
    backToTopBtn.style.padding = "10px 15px";
    backToTopBtn.style.fontSize = "18px";
    backToTopBtn.style.border = "none";
    backToTopBtn.style.background = "#f8c146";
    backToTopBtn.style.color = "#222";
    backToTopBtn.style.borderRadius = "5px";
    backToTopBtn.style.cursor = "pointer";
    backToTopBtn.style.display = "none";

    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    });

    backToTopBtn.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});
