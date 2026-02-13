import { useState } from "react";

const faqs = [
    {
        question: "Bagaimana Aurify bantu pajak UMKM?",
        answer: "Aurify secara otomatis menghitung dan membuat laporan pajak berdasarkan data transaksi yang Anda input. Laporan disesuaikan dengan regulasi pajak Indonesia (PPh Final UMKM 0.5%), sehingga Anda tinggal cetak dan laporkan. Tidak perlu akuntan mahal!",
    },
    {
        question: "Apakah data keuangan saya aman?",
        answer: "Keamanan data adalah prioritas utama kami. Aurify menggunakan enkripsi bank-grade (AES-256), server di Indonesia, backup otomatis harian, dan sertifikasi ISO 27001. Data Anda tidak akan pernah di-share ke pihak ketiga.",
    },
    {
        question: "Apakah bisa integrasi dengan bank saya?",
        answer: "Ya! Aurify Pro mendukung integrasi langsung dengan BCA, Mandiri, BRI, BNI, dan bank digital seperti Jago & Jenius. Transaksi otomatis tersinkron sehingga Anda tidak perlu input manual.",
    },
    {
        question: "Apa bedanya versi Gratis dan Pro?",
        answer: "Versi Gratis cocok untuk memulai — tracking pengeluaran dasar dan 3 kategori budget. Versi Pro membuka semua fitur premium: unlimited kategori, integrasi bank, AI prediksi cash flow, invoice otomatis, laporan pajak, dan hingga 5 user. Mulai dari Rp99.000/bulan.",
    },
    {
        question: "Apakah cocok untuk bisnis kecil yang belum paham akuntansi?",
        answer: "Tentu saja! Aurify dirancang khusus agar mudah digunakan oleh siapa saja — tidak perlu latar belakang akuntansi. Interface-nya intuitif, ada panduan langkah demi langkah, dan tim support kami siap membantu via WhatsApp.",
    },
];

function FAQItem({ faq, isOpen, toggle }) {
    return (
        <div className="border border-gold-400/10 rounded-xl overflow-hidden hover:border-gold-400/20 transition-colors duration-300">
            <button
                onClick={toggle}
                className="w-full flex items-center justify-between px-6 py-4 text-left group"
            >
                <span className="text-white font-body text-sm sm:text-base font-semibold pr-4 group-hover:text-gold-400 transition-colors duration-300">
                    {faq.question}
                </span>
                <span
                    className={`text-gold-400 transition-transform duration-300 shrink-0 ${
                        isOpen ? "rotate-45" : ""
                    }`}
                >
                    <svg
                        className="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        strokeWidth={2}
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                </span>
            </button>
            <div
                className={`transition-all duration-500 ease-in-out ${
                    isOpen ? "max-h-96 opacity-100" : "max-h-0 opacity-0"
                } overflow-hidden`}
            >
                <p className="px-6 pb-5 text-gray-400 font-body text-sm leading-relaxed">
                    {faq.answer}
                </p>
            </div>
        </div>
    );
}

export default function CtaFaq() {
    const [openIndex, setOpenIndex] = useState(0);

    const scrollToForm = () => {
        const el = document.getElementById("form");
        if (el) el.scrollIntoView({ behavior: "smooth" });
    };

    return (
        <section id="cta" className="relative py-20 md:py-28">
            <div className="absolute top-0 left-0 right-0 gold-line" />

            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* CTA Banner */}
                <div className="text-center mb-20">
                    <div className="relative bg-dark-200/80 backdrop-blur-sm border border-gold-400/20 rounded-3xl p-8 sm:p-12 md:p-16 glow-gold-strong overflow-hidden">
                        {/* Background decoration */}
                        <div className="absolute top-0 left-0 w-full h-full">
                            <div className="absolute -top-20 -right-20 w-60 h-60 bg-gold-400/5 rounded-full blur-3xl" />
                            <div className="absolute -bottom-20 -left-20 w-60 h-60 bg-gold-800/5 rounded-full blur-3xl" />
                        </div>

                        <div className="relative z-10">
                            <h2 className="font-heading text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold mb-6">
                                <span className="text-white">
                                    Siap Ubah Keuangan Anda
                                </span>
                                <br />
                                <span className="text-gold-gradient">
                                    Menjadi Emas?
                                </span>
                            </h2>
                            <p className="text-gray-400 font-body text-base sm:text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
                                Bergabung dengan 1.200+ UMKM Indonesia yang
                                sudah mengubah keuangan mereka menjadi lebih
                                stabil, terkelola, dan menguntungkan bersama
                                Aurify.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <button
                                    onClick={scrollToForm}
                                    className="bg-gold-btn text-dark font-body font-bold text-base px-10 py-4 rounded-xl hover:shadow-xl hover:shadow-gold-400/20 transition-all duration-300 hover:scale-105 animate-pulse-gold"
                                >
                                    🚀 Daftar Gratis Sekarang!
                                </button>
                                <button
                                    onClick={scrollToForm}
                                    className="border border-gold-400/30 text-gold-400 font-body font-semibold text-base px-10 py-4 rounded-xl hover:bg-gold-400/5 hover:border-gold-400/50 transition-all duration-300"
                                >
                                    Dapatkan Template Gratis
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* FAQ */}
                <div className="max-w-3xl mx-auto">
                    <div className="text-center mb-12">
                        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gold-400/20 bg-gold-400/5 mb-6">
                            <span className="text-gold-400 text-xs font-body tracking-wider uppercase">
                                FAQ
                            </span>
                        </div>
                        <h2 className="font-heading text-3xl sm:text-4xl font-bold mb-4">
                            <span className="text-white">Pertanyaan yang </span>
                            <span className="text-gold-gradient">
                                Sering Ditanyakan
                            </span>
                        </h2>
                    </div>

                    <div className="space-y-3">
                        {faqs.map((faq, index) => (
                            <FAQItem
                                key={index}
                                faq={faq}
                                isOpen={openIndex === index}
                                toggle={() =>
                                    setOpenIndex(
                                        openIndex === index ? -1 : index,
                                    )
                                }
                            />
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
