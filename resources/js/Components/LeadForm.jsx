import { useState } from "react";
import { useForm } from "@inertiajs/react";

export default function LeadForm() {
    const [submitted, setSubmitted] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        nama: "",
        whatsapp: "",
        email: "",
        lembaga: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post("/leads", {
            onSuccess: () => {
                setSubmitted(true);
                reset();
            },
        });
    };

    if (submitted) {
        return (
            <section id="form" className="relative py-20 md:py-28">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 text-center">
                    <div className="bg-dark-200/80 backdrop-blur-sm border border-gold-400/20 rounded-2xl p-8 md:p-12 glow-gold animate-fade-in-up">
                        <div className="w-20 h-20 rounded-full bg-gold-btn mx-auto mb-6 flex items-center justify-center">
                            <svg
                                className="w-10 h-10 text-dark"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth={2.5}
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                        <h3 className="font-heading text-3xl md:text-4xl text-gold-gradient font-bold mb-4">
                            Terima Kasih! 🎉
                        </h3>
                        <p className="text-gray-400 font-body text-base mb-6">
                            Template budgeting premium akan dikirim ke WhatsApp
                            dan Email Anda dalam beberapa menit. Cek inbox Anda!
                        </p>
                        <button
                            onClick={() => setSubmitted(false)}
                            className="text-gold-400 font-body text-sm hover:text-gold-500 transition-colors"
                        >
                            ← Kirim untuk orang lain
                        </button>
                    </div>
                </div>
            </section>
        );
    }

    return (
        <section id="form" className="relative py-20 md:py-28">
            {/* Background accent */}
            <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gold-400/5 rounded-full blur-[140px]" />

            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    {/* Left – Copywriting */}
                    <div className="animate-fade-in-up">
                        {/* <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gold-400/20 bg-gold-400/5 mb-6">
                            <span className="text-gold-400 text-xs font-body tracking-wider uppercase">
                                Lead Magnet Eksklusif
                            </span>
                        </div> */}
                        <h2 className="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                            <span className="text-gold-gradient">
                                Akses Eksklusif:
                            </span>{" "}
                            <span className="text-white">
                                Dapatkan Template Budgeting Emas Gratis
                                untuk{" "}
                            </span>
                            <span className="text-gold-shine">UMKM Anda!</span>
                        </h2>
                        <p className="text-gray-400 font-body text-base leading-relaxed mb-8">
                            Isi detail di bawah untuk kirim template via
                            WA/Email — bantu atasi cash flow & pajak rumit dalam
                            hitungan menit. Template ini sudah digunakan oleh
                            1.200+ UMKM di Indonesia.
                        </p>
                        <div className="space-y-4">
                            {[
                                "Template Excel budgeting siap pakai",
                                "Panduan mengelola cash flow UMKM",
                                "Tips pajak sederhana untuk bisnis kecil",
                            ].map((item, i) => (
                                <div
                                    key={i}
                                    className="flex items-center gap-3"
                                >
                                    <div className="w-6 h-6 rounded-full bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                                        <svg
                                            className="w-3.5 h-3.5 text-gold-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth={2.5}
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                    </div>
                                    <span className="text-gray-300 font-body text-sm">
                                        {item}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Right – Form */}
                    <div className="animate-fade-in-up">
                        <form
                            onSubmit={handleSubmit}
                            className="bg-dark-200/80 backdrop-blur-sm border border-gold-400/20 rounded-2xl p-6 sm:p-8 glow-gold"
                        >
                            <h3 className="font-heading text-2xl text-gold-gradient font-bold mb-2 text-center">
                                Dapatkan Template Gratis
                            </h3>
                            <p className="text-gray-500 text-sm font-body text-center mb-6">
                                Isi form berikut — 100% gratis, tanpa spam.
                            </p>

                            <div className="space-y-4">
                                {/* Nama */}
                                <div>
                                    <label className="block text-gold-400/80 text-xs font-body font-semibold mb-1.5 uppercase tracking-wider">
                                        Nama Lengkap Anda{" "}
                                        <span className="text-red-400">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.nama}
                                        onChange={(e) =>
                                            setData("nama", e.target.value)
                                        }
                                        placeholder="Masukkan nama lengkap"
                                        className="w-full bg-dark-300/50 border border-gold-400/15 rounded-xl px-4 py-3 text-white font-body text-sm placeholder:text-gray-600 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/20 transition-all duration-300"
                                    />
                                    {errors.nama && (
                                        <p className="text-red-400 text-xs font-body mt-1">
                                            {errors.nama}
                                        </p>
                                    )}
                                </div>

                                {/* WhatsApp */}
                                <div>
                                    <label className="block text-gold-400/80 text-xs font-body font-semibold mb-1.5 uppercase tracking-wider">
                                        Nomor WhatsApp{" "}
                                        <span className="text-red-400">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        value={data.whatsapp}
                                        onChange={(e) =>
                                            setData("whatsapp", e.target.value)
                                        }
                                        placeholder="08xxxxxxxxxx"
                                        className="w-full bg-dark-300/50 border border-gold-400/15 rounded-xl px-4 py-3 text-white font-body text-sm placeholder:text-gray-600 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/20 transition-all duration-300"
                                    />
                                    {errors.whatsapp && (
                                        <p className="text-red-400 text-xs font-body mt-1">
                                            {errors.whatsapp}
                                        </p>
                                    )}
                                </div>

                                {/* Email */}
                                <div>
                                    <label className="block text-gold-400/80 text-xs font-body font-semibold mb-1.5 uppercase tracking-wider">
                                        Alamat Email{" "}
                                        <span className="text-red-400">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        value={data.email}
                                        onChange={(e) =>
                                            setData("email", e.target.value)
                                        }
                                        placeholder="email@bisnis-anda.com"
                                        className="w-full bg-dark-300/50 border border-gold-400/15 rounded-xl px-4 py-3 text-white font-body text-sm placeholder:text-gray-600 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/20 transition-all duration-300"
                                    />
                                    {errors.email && (
                                        <p className="text-red-400 text-xs font-body mt-1">
                                            {errors.email}
                                        </p>
                                    )}
                                </div>

                                {/* Lembaga */}
                                <div>
                                    <label className="block text-gold-400/80 text-xs font-body font-semibold mb-1.5 uppercase tracking-wider">
                                        Nama Bisnis/UMKM{" "}
                                        <span className="text-gray-600">
                                            (opsional)
                                        </span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.lembaga}
                                        onChange={(e) =>
                                            setData("lembaga", e.target.value)
                                        }
                                        placeholder="Nama usaha Anda untuk personalisasi"
                                        className="w-full bg-dark-300/50 border border-gold-400/15 rounded-xl px-4 py-3 text-white font-body text-sm placeholder:text-gray-600 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/20 transition-all duration-300"
                                    />
                                    {errors.lembaga && (
                                        <p className="text-red-400 text-xs font-body mt-1">
                                            {errors.lembaga}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full mt-6 bg-gold-btn text-dark font-body font-bold text-base px-6 py-4 rounded-xl hover:shadow-xl hover:shadow-gold-400/20 transition-all duration-300 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? (
                                    <span className="flex items-center justify-center gap-2">
                                        <svg
                                            className="animate-spin w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                className="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                strokeWidth="4"
                                            />
                                            <path
                                                className="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                            />
                                        </svg>
                                        Mengirim...
                                    </span>
                                ) : (
                                    "Kirim Template Sekarang!"
                                )}
                            </button>

                            <p className="text-gray-600 text-xs font-body text-center mt-4">
                                Data Anda aman & tidak akan di-share ke pihak
                                ketiga.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    );
}
