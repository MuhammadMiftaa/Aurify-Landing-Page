import { useState, useEffect, useRef } from "react";

const testimonials = [
    {
        quote: "Aurify ubah keuangan bisnis saya jadi lebih mewah & terkontrol! Cash flow yang tadinya berantakan, sekarang bisa saya pantau setiap hari. Sangat membantu!",
        name: "Budi Santoso",
        role: "Owner Toko Elektronik",
        location: "Surabaya",
        rating: 5,
        initials: "BS",
    },
    {
        quote: "Sebagai pemilik warung makan, saya nggak paham akuntansi. Aurify bikin semuanya simpel — tinggal input, langsung keluar laporan. Pajak jadi nggak ribet lagi!",
        name: "Siti Rahayu",
        role: "Owner Warung Makan Sederhana",
        location: "Surabaya",
        rating: 5,
        initials: "SR",
    },
    {
        quote: "Template budgeting-nya luar biasa! Saya langsung bisa lihat di mana kebocoran uang bisnis saya. Dalam sebulan, pengeluaran berkurang 30%. Recommended!",
        name: "Ahmad Fauzi",
        role: "Owner Konveksi",
        location: "Sidoarjo",
        rating: 5,
        initials: "AF",
    },
    {
        quote: "Fitur invoice otomatis Aurify bikin saya terlihat lebih profesional di mata klien. Sekarang pembayaran masuk lebih cepat karena invoice-nya rapi dan tepat waktu.",
        name: "Dewi Lestari",
        role: "Freelance Designer",
        location: "Surabaya",
        rating: 5,
        initials: "DL",
    },
];

function StarRating({ rating }) {
    return (
        <div className="flex gap-1">
            {Array.from({ length: 5 }, (_, i) => (
                <svg
                    key={i}
                    className={`w-4 h-4 ${i < rating ? "text-gold-500" : "text-gray-700"}`}
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            ))}
        </div>
    );
}

function TestimonialCard({ testimonial, index }) {
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
            className={`bg-dark-200/60 backdrop-blur-sm border border-gold-400/10 rounded-2xl p-6 sm:p-8 hover:border-gold-400/20 transition-all duration-500 hover:glow-gold ${
                visible
                    ? "opacity-100 translate-y-0"
                    : "opacity-0 translate-y-8"
            }`}
            style={{
                transition:
                    "opacity 0.6s ease-out, transform 0.6s ease-out, border-color 0.5s, box-shadow 0.5s",
            }}
        >
            {/* Stars */}
            <StarRating rating={testimonial.rating} />

            {/* Quote */}
            <p className="text-gray-300 font-body text-sm leading-relaxed mt-4 mb-6 italic">
                "{testimonial.quote}"
            </p>

            {/* Author */}
            <div className="flex items-center gap-4 pt-4 border-t border-gold-400/10">
                {/* Avatar */}
                <div className="w-12 h-12 rounded-full bg-dark-300 border border-gold-400/20 flex items-center justify-center overflow-hidden">
                    <span className="text-gold-400 font-heading text-lg font-bold">
                        {testimonial.initials}
                    </span>
                </div>
                <div>
                    <p className="text-white font-body font-semibold text-sm">
                        {testimonial.name}
                    </p>
                    <p className="text-gray-500 font-body text-xs">
                        {testimonial.role}, {testimonial.location}
                    </p>
                </div>
            </div>
        </div>
    );
}

export default function Testimonials() {
    return (
        <section id="testimoni" className="relative py-20 md:py-28">
            <div className="absolute top-0 left-0 right-0 gold-line" />
            <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gold-400/3 rounded-full blur-[150px]" />

            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Header */}
                <div className="text-center max-w-3xl mx-auto mb-16">
                    <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gold-400/20 bg-gold-400/5 mb-6">
                        <span className="text-gold-400 text-xs font-body tracking-wider uppercase">
                            Testimoni Nyata
                        </span>
                    </div>
                    <h2 className="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                        <span className="text-white">Dipercaya oleh </span>
                        <span className="text-gold-gradient">UMKM</span>
                        <span className="text-white"> di Seluruh </span>
                        <span className="text-gold-shine">Indonesia</span>
                    </h2>
                    <p className="text-gray-400 font-body text-base sm:text-lg leading-relaxed">
                        Lihat bagaimana Aurify membantu pemilik bisnis mengelola
                        keuangan mereka dengan lebih baik.
                    </p>
                </div>

                {/* Grid */}
                <div className="grid sm:grid-cols-2 gap-6">
                    {testimonials.map((testimonial, index) => (
                        <TestimonialCard
                            key={index}
                            testimonial={testimonial}
                            index={index}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}
