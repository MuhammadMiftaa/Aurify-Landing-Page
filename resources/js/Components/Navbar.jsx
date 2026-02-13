import { useState, useEffect } from "react";

export default function Navbar() {
    const [scrolled, setScrolled] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 50);
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    const scrollTo = (id) => {
        setMobileOpen(false);
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: "smooth" });
        }
    };

    const navLinks = [
        { label: "Home", target: "hero" },
        { label: "Fitur", target: "fitur" },
        { label: "Harga", target: "harga" },
        { label: "Login", target: "cta" },
    ];

    return (
        <nav
            className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
                scrolled
                    ? "bg-dark/90 backdrop-blur-xl border-b border-gold-400/10 shadow-lg shadow-black/20"
                    : "bg-transparent"
            }`}
        >
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between h-16 md:h-20">
                    {/* Logo */}
                    <button
                        onClick={() => scrollTo("hero")}
                        className="flex items-center gap-3 group"
                    >
                        {/* Logo "A" icon */}
                        <div className="w-10 h-10 md:w-11 md:h-11 rounded-lg bg-gold-btn flex items-center justify-center shadow-lg shadow-gold-400/20 group-hover:shadow-gold-400/40 transition-shadow duration-300">
                            <span className="font-heading text-dark font-bold text-xl md:text-2xl leading-none">
                                A
                            </span>
                        </div>
                        <div className="flex flex-col">
                            <span className="text-gold-gradient font-heading text-xl md:text-2xl font-bold tracking-wide">
                                Aurify
                            </span>
                            <span className="text-[9px] md:text-[10px] text-gold-400/60 tracking-[0.15em] uppercase font-body hidden sm:block">
                                Financial Management
                            </span>
                        </div>
                    </button>

                    {/* Desktop Nav */}
                    <div className="hidden md:flex items-center gap-8">
                        {navLinks.map((link) => (
                            <button
                                key={link.target}
                                onClick={() => scrollTo(link.target)}
                                className="text-sm font-body text-gray-400 hover:text-gold-400 transition-colors duration-300 relative group"
                            >
                                {link.label}
                                <span className="absolute -bottom-1 left-0 w-0 h-px bg-gold-400 group-hover:w-full transition-all duration-300" />
                            </button>
                        ))}
                        <button
                            onClick={() => scrollTo("form")}
                            className="bg-gold-btn text-dark font-body font-semibold text-sm px-6 py-2.5 rounded-lg hover:shadow-lg hover:shadow-gold-400/20 transition-all duration-300 hover:scale-105"
                        >
                            Daftar Gratis
                        </button>
                    </div>

                    {/* Mobile Hamburger */}
                    <button
                        className="md:hidden flex flex-col gap-1.5 p-2"
                        onClick={() => setMobileOpen(!mobileOpen)}
                        aria-label="Toggle Menu"
                    >
                        <span
                            className={`w-6 h-0.5 bg-gold-400 transition-all duration-300 ${
                                mobileOpen ? "rotate-45 translate-y-2" : ""
                            }`}
                        />
                        <span
                            className={`w-6 h-0.5 bg-gold-400 transition-all duration-300 ${
                                mobileOpen ? "opacity-0" : ""
                            }`}
                        />
                        <span
                            className={`w-6 h-0.5 bg-gold-400 transition-all duration-300 ${
                                mobileOpen ? "-rotate-45 -translate-y-2" : ""
                            }`}
                        />
                    </button>
                </div>
            </div>

            {/* Mobile Menu */}
            <div
                className={`md:hidden overflow-hidden transition-all duration-500 ${
                    mobileOpen ? "max-h-96 opacity-100" : "max-h-0 opacity-0"
                }`}
            >
                <div className="bg-dark-100/95 backdrop-blur-xl border-t border-gold-400/10 px-4 py-6 space-y-4">
                    {navLinks.map((link) => (
                        <button
                            key={link.target}
                            onClick={() => scrollTo(link.target)}
                            className="block w-full text-left text-gray-400 hover:text-gold-400 font-body text-base py-2 transition-colors duration-300"
                        >
                            {link.label}
                        </button>
                    ))}
                    <button
                        onClick={() => scrollTo("form")}
                        className="w-full bg-gold-btn text-dark font-body font-semibold text-sm px-6 py-3 rounded-lg mt-2"
                    >
                        Daftar Gratis
                    </button>
                </div>
            </div>
        </nav>
    );
}
