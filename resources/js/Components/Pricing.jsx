import { useState, useEffect, useRef } from "react";

const plans = [
    {
        name: "Starter",
        price: "Gratis",
        period: "Selamanya",
        description: "Untuk memulai perjalanan finansial Anda",
        features: [
            "Tracking pengeluaran dasar",
            "Budgeting sederhana (3 kategori)",
            "Laporan bulanan",
            "1 user",
            "Data export CSV",
        ],
        notIncluded: [
            "Integrasi bank",
            "AI prediksi cash flow",
            "Invoice otomatis",
            "Laporan pajak",
        ],
        cta: "Mulai Gratis",
        popular: false,
    },
    {
        name: "Professional",
        price: "Rp 99.000",
        period: "/bulan",
        description: "Untuk UMKM serius yang ingin bertumbuh",
        features: [
            "Tracking pengeluaran unlimited",
            "Budgeting kategori tak terbatas",
            "Laporan real-time & historis",
            "Hingga 5 users",
            "Integrasi bank (BCA, Mandiri, BRI)",
            "AI prediksi cash flow 3 bulan",
            "Invoice otomatis profesional",
            "Laporan pajak sesuai regulasi RI",
            "Priority support via WA",
        ],
        notIncluded: [],
        cta: "Upgrade ke Pro",
        popular: true,
    },
    {
        name: "Enterprise",
        price: "Custom",
        period: "Hubungi kami",
        description: "Untuk bisnis besar dengan kebutuhan khusus",
        features: [
            "Semua fitur Pro",
            "Unlimited users",
            "Integrasi API custom",
            "Dedicated account manager",
            "SLA 99.9% uptime",
            "Custom reporting",
            "On-premise deployment option",
            "Training & onboarding tim",
        ],
        notIncluded: [],
        cta: "Hubungi Sales",
        popular: false,
    },
];

function PriceCard({ plan, index }) {
    const [visible, setVisible] = useState(false);
    const ref = useRef(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setTimeout(() => setVisible(true), index * 200);
                    observer.disconnect();
                }
            },
            { threshold: 0.2 },
        );
        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect();
    }, [index]);

    const scrollToForm = () => {
        const el = document.getElementById("form");
        if (el) el.scrollIntoView({ behavior: "smooth" });
    };

    return (
        <div
            ref={ref}
            className={`relative flex flex-col bg-dark-200/60 backdrop-blur-sm border rounded-2xl p-6 sm:p-8 transition-all duration-500 ${
                plan.popular
                    ? "border-gold-400/30 glow-gold-strong scale-100 lg:scale-105"
                    : "border-gold-400/10 hover:border-gold-400/20"
            } ${visible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}
            style={{
                transition: "opacity 0.6s ease-out, transform 0.6s ease-out",
            }}
        >
            {/* Popular badge */}
            {plan.popular && (
                <div className="absolute -top-4 left-1/2 -translate-x-1/2">
                    <div className="bg-gold-btn text-dark font-body font-bold text-xs px-5 py-1.5 rounded-full shadow-lg shadow-gold-400/20">
                        PALING POPULER
                    </div>
                </div>
            )}

            {/* Plan Name */}
            <div className="mb-6">
                <h3
                    className={`font-heading text-xl font-bold mb-1 ${plan.popular ? "text-gold-gradient" : "text-white"}`}
                >
                    {plan.name}
                </h3>
                <p className="text-gray-500 font-body text-sm">
                    {plan.description}
                </p>
            </div>

            {/* Price */}
            <div className="mb-6 pb-6 border-b border-gold-400/10">
                <div className="flex items-baseline gap-1">
                    <span
                        className={`font-heading text-4xl font-bold ${plan.popular ? "text-gold-gradient" : "text-white"}`}
                    >
                        {plan.price}
                    </span>
                    <span className="text-gray-500 font-body text-sm">
                        {plan.period}
                    </span>
                </div>
            </div>

            {/* Features */}
            <div className="flex-1 mb-8">
                <ul className="space-y-3">
                    {plan.features.map((feature, i) => (
                        <li key={i} className="flex items-start gap-3">
                            <svg
                                className="w-5 h-5 text-gold-400 mt-0.5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth={2}
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span className="text-gray-300 font-body text-sm">
                                {feature}
                            </span>
                        </li>
                    ))}
                    {plan.notIncluded.map((feature, i) => (
                        <li
                            key={`no-${i}`}
                            className="flex items-start gap-3 opacity-40"
                        >
                            <svg
                                className="w-5 h-5 text-gray-600 mt-0.5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth={2}
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                            <span className="text-gray-500 font-body text-sm line-through">
                                {feature}
                            </span>
                        </li>
                    ))}
                </ul>
            </div>

            {/* CTA */}
            <button
                onClick={scrollToForm}
                className={`w-full font-body font-bold text-sm px-6 py-3.5 rounded-xl transition-all duration-300 hover:scale-[1.02] ${
                    plan.popular
                        ? "bg-gold-btn text-dark hover:shadow-lg hover:shadow-gold-400/20"
                        : "border border-gold-400/30 text-gold-400 hover:bg-gold-400/5 hover:border-gold-400/50"
                }`}
            >
                {plan.cta}
            </button>
        </div>
    );
}

export default function Pricing() {
    return (
        <section id="harga" className="relative py-20 md:py-28">
            <div className="absolute top-0 left-0 right-0 gold-line" />
            <div className="absolute bottom-1/3 left-0 w-[400px] h-[400px] bg-gold-400/3 rounded-full blur-[120px]" />

            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Header */}
                <div className="text-center max-w-3xl mx-auto mb-16">
                    <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gold-400/20 bg-gold-400/5 mb-6">
                        <span className="text-gold-400 text-xs font-body tracking-wider uppercase">
                            Harga Transparan
                        </span>
                    </div>
                    <h2 className="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                        <span className="text-white">Investasi </span>
                        <span className="text-gold-gradient">Terjangkau</span>
                        <span className="text-white"> untuk Keuangan </span>
                        <span className="text-gold-shine">Stabil</span>
                    </h2>
                    <p className="text-gray-400 font-body text-base sm:text-lg leading-relaxed">
                        Mulai gratis, upgrade kapan saja. Tanpa biaya
                        tersembunyi, tanpa kontrak jangka panjang.
                    </p>
                </div>

                {/* Cards */}
                <div className="grid md:grid-cols-3 gap-6 lg:gap-8 items-start">
                    {plans.map((plan, index) => (
                        <PriceCard key={index} plan={plan} index={index} />
                    ))}
                </div>
            </div>
        </section>
    );
}
