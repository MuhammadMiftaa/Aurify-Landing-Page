export default function Footer() {
    const scrollTo = (id) => {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: "smooth" });
    };

    return (
        <footer className="relative border-t border-gold-400/10">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                    {/* Brand */}
                    <div className="sm:col-span-2 lg:col-span-1">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="w-10 h-10 rounded-lg bg-gold-btn flex items-center justify-center shadow-lg shadow-gold-400/20">
                                <span className="font-heading text-dark font-bold text-xl leading-none">
                                    A
                                </span>
                            </div>
                            <span className="text-gold-gradient font-heading text-2xl font-bold tracking-wide">
                                Aurify
                            </span>
                        </div>
                        <p className="text-gray-500 font-body text-sm leading-relaxed mb-4">
                            Jadikan keuangan Anda lebih berharga dan stabil.
                            Manajemen finansial premium untuk UMKM & keluarga
                            Indonesia.
                        </p>
                        <p className="text-gray-600 font-body text-xs">
                            © {new Date().getFullYear()} Aurify. All rights
                            reserved.
                        </p>
                    </div>

                    {/* Navigation */}
                    <div>
                        <h4 className="text-gold-400 font-body font-semibold text-sm uppercase tracking-wider mb-4">
                            Navigasi
                        </h4>
                        <ul className="space-y-3">
                            {[
                                { label: "Home", target: "hero" },
                                { label: "Fitur", target: "fitur" },
                                { label: "Harga", target: "harga" },
                                { label: "Testimoni", target: "testimoni" },
                                { label: "FAQ", target: "cta" },
                            ].map((link) => (
                                <li key={link.target}>
                                    <button
                                        onClick={() => scrollTo(link.target)}
                                        className="text-gray-500 font-body text-sm hover:text-gold-400 transition-colors duration-300"
                                    >
                                        {link.label}
                                    </button>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Fitur */}
                    <div>
                        <h4 className="text-gold-400 font-body font-semibold text-sm uppercase tracking-wider mb-4">
                            Fitur
                        </h4>
                        <ul className="space-y-3">
                            {[
                                "Budgeting Emas",
                                "Tracking Pengeluaran",
                                "Invoice Otomatis",
                                "Laporan Pajak",
                                "AI Cash Flow",
                            ].map((item) => (
                                <li key={item}>
                                    <span className="text-gray-500 font-body text-sm">
                                        {item}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Contact */}
                    <div>
                        <h4 className="text-gold-400 font-body font-semibold text-sm uppercase tracking-wider mb-4">
                            Hubungi Kami
                        </h4>
                        <ul className="space-y-3">
                            <li className="flex items-center gap-2">
                                <svg
                                    className="w-4 h-4 text-gold-400/60"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth={1.5}
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                    />
                                </svg>
                                <span className="text-gray-500 font-body text-sm">
                                    hello@aurify.id
                                </span>
                            </li>
                            <li className="flex items-center gap-2">
                                <svg
                                    className="w-4 h-4 text-gold-400/60"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth={1.5}
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                                    />
                                </svg>
                                <span className="text-gray-500 font-body text-sm">
                                    +62 812-3456-7890
                                </span>
                            </li>
                            <li className="flex items-start gap-2">
                                <svg
                                    className="w-4 h-4 text-gold-400/60 mt-0.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth={1.5}
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                                    />
                                </svg>
                                <span className="text-gray-500 font-body text-sm">
                                    Surabaya, Jawa Timur, Indonesia
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                {/* Bottom */}
                <div className="gold-line mt-10 mb-6" />
                <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p className="text-gray-600 font-body text-xs">
                        Didesain dengan ✨ untuk UMKM Indonesia
                    </p>
                    <div className="flex items-center gap-1">
                        <span className="text-gray-600 font-body text-xs">
                            Developed by
                        </span>
                        <span className="text-gold-400/60 font-body text-xs font-semibold">
                            Muhammad Mifta
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    );
}
