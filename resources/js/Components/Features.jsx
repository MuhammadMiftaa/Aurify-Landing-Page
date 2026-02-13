import { useState, useEffect, useRef } from "react";

const features = [
    {
        icon: (
            <svg
                className="w-7 h-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={1.5}
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                />
            </svg>
        ),
        title: "Budgeting Emas",
        description:
            "Rencanakan pengeluaran keluarga & UMKM tanpa kebocoran cash flow. Alokasikan budget berdasarkan kategori, dan pantau realisasinya secara real-time.",
        highlight: "Kurangi kebocoran hingga 40%",
    },
    {
        icon: (
            <svg
                className="w-7 h-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={1.5}
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
                />
            </svg>
        ),
        title: "Tracking Pengeluaran",
        description:
            "Pantau setiap rupiah yang keluar secara real-time. Hindari hutang tak terduga dan lihat pola pengeluaran Anda dalam visualisasi yang mudah dipahami.",
        highlight: "Monitoring 24/7 real-time",
    },
    {
        icon: (
            <svg
                className="w-7 h-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={1.5}
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                />
            </svg>
        ),
        title: "Invoice & Laporan Otomatis",
        description:
            "Buat invoice profesional dalam sekejap. Laporan pajak otomatis sesuai regulasi Indonesia — tidak perlu akuntan mahal.",
        highlight: "Sesuai regulasi pajak RI",
    },
    {
        icon: (
            <svg
                className="w-7 h-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                strokeWidth={1.5}
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"
                />
            </svg>
        ),
        title: "AI Prediksi Cash Flow",
        description:
            "Teknologi AI yang memprediksi cash flow Anda 3 bulan ke depan. Ambil keputusan finansial lebih cerdas dengan data-driven insights.",
        highlight: "Akurasi prediksi 94%",
    },
];

function FeatureCard({ feature, index }) {
    const [visible, setVisible] = useState(false);
    const ref = useRef(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setTimeout(() => setVisible(true), index * 150);
                    observer.disconnect();
                }
            },
            { threshold: 0.2 },
        );
        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect();
    }, [index]);

    return (
        <div
            ref={ref}
            className={`group relative bg-dark-200/60 backdrop-blur-sm border border-gold-400/10 rounded-2xl p-6 sm:p-8 hover:border-gold-400/30 transition-all duration-500 hover:glow-gold ${
                visible
                    ? "opacity-100 translate-y-0"
                    : "opacity-0 translate-y-8"
            }`}
            style={{
                transition:
                    "opacity 0.6s ease-out, transform 0.6s ease-out, border-color 0.5s, box-shadow 0.5s",
            }}
        >
            {/* Gold top line on hover */}
            <div className="absolute top-0 left-6 right-6 h-px bg-gradient-to-r from-transparent via-gold-400/0 group-hover:via-gold-400/50 to-transparent transition-all duration-500" />

            {/* Icon */}
            <div className="w-14 h-14 rounded-xl bg-gold-400/10 border border-gold-400/10 flex items-center justify-center text-gold-400 mb-5 group-hover:bg-gold-400/15 group-hover:border-gold-400/20 transition-all duration-300">
                {feature.icon}
            </div>

            <h3 className="font-heading text-xl sm:text-2xl font-bold text-white mb-3 group-hover:text-gold-gradient transition-colors duration-300">
                {feature.title}
            </h3>

            <p className="text-gray-400 font-body text-sm leading-relaxed mb-4">
                {feature.description}
            </p>

            {/* Highlight badge */}
            <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gold-400/5 border border-gold-400/10">
                <span className="w-1.5 h-1.5 rounded-full bg-gold-500" />
                <span className="text-gold-400 text-xs font-body font-semibold">
                    {feature.highlight}
                </span>
            </div>
        </div>
    );
}

export default function Features() {
    return (
        <section id="fitur" className="relative py-20 md:py-28">
            {/* Background */}
            <div className="absolute top-0 left-0 right-0 gold-line" />
            <div className="absolute top-1/3 right-0 w-[400px] h-[400px] bg-gold-400/3 rounded-full blur-[120px]" />

            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Header */}
                <div className="text-center max-w-3xl mx-auto mb-16">
                    <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gold-400/20 bg-gold-400/5 mb-6">
                        <span className="text-gold-400 text-xs font-body tracking-wider uppercase">
                            Fitur Premium
                        </span>
                    </div>
                    <h2 className="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                        <span className="text-white">Tools </span>
                        <span className="text-gold-gradient">Premium</span>
                        <span className="text-white"> untuk Keuangan </span>
                        <span className="text-gold-shine">Stabil</span>
                    </h2>
                    <p className="text-gray-400 font-body text-base sm:text-lg leading-relaxed">
                        Semua yang Anda butuhkan untuk mengubah keuangan bisnis
                        & keluarga menjadi emas — dari budgeting hingga laporan
                        pajak otomatis.
                    </p>
                </div>

                {/* Cards */}
                <div className="grid sm:grid-cols-2 gap-6">
                    {features.map((feature, index) => (
                        <FeatureCard
                            key={index}
                            feature={feature}
                            index={index}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}
