export default function Hero() {
    const scrollTo = (id) => {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: "smooth" });
    };

    return (
        <section
            id="hero"
            className="relative min-h-screen flex items-center pt-20 overflow-hidden"
        >
            {/* Background accents */}
            <div className="absolute top-0 right-0 w-[600px] h-[600px] bg-gold-400/5 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/3" />
            <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gold-800/10 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/3" />

            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
                <div className="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    {/* Left – Text */}
                    <div className="animate-fade-in-up">
                        {/* Badge */}
                        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-gold-400/20 bg-gold-400/5 mb-8">
                            <span className="w-2 h-2 rounded-full bg-gold-500 animate-pulse" />
                            <span className="text-gold-400 text-xs font-body tracking-wider uppercase">
                                Premium Financial Management
                            </span>
                        </div>

                        <h1 className="font-heading text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-[1.1] mb-6">
                            <span className="text-gold-gradient">Aurify:</span>{" "}
                            <span className="text-white">
                                Manajemen Finansial Premium yang Mengubah{" "}
                            </span>
                            <span className="text-gold-shine">Cash Flow</span>{" "}
                            <span className="text-white">Anda Menjadi </span>
                            <span className="text-gold-gradient">
                                Emas Stabil
                            </span>
                        </h1>

                        <p className="text-gray-400 font-body text-base sm:text-lg leading-relaxed mb-8 max-w-xl">
                            Atasi cash flow buruk, pajak rumit, dan budgeting
                            keluarga/UMKM dengan tools sederhana — tracking
                            pengeluaran real-time, invoice otomatis, laporan
                            pajak mudah. Cocok untuk bisnis kecil di Indonesia!
                        </p>

                        <div className="flex flex-col sm:flex-row gap-4">
                            <button
                                onClick={() => scrollTo("form")}
                                className="bg-gold-btn text-dark font-body font-bold text-base px-8 py-4 rounded-xl hover:shadow-xl hover:shadow-gold-400/20 transition-all duration-300 hover:scale-105 animate-pulse-gold"
                            >
                                🎁 Dapatkan Template Budgeting Gratis!
                            </button>
                            <button
                                onClick={() => scrollTo("fitur")}
                                className="border border-gold-400/30 text-gold-400 font-body font-semibold text-base px-8 py-4 rounded-xl hover:bg-gold-400/5 hover:border-gold-400/50 transition-all duration-300"
                            >
                                Lihat Fitur →
                            </button>
                        </div>

                        {/* Trust badges */}
                        <div className="flex items-center gap-6 mt-10 pt-8 border-t border-gold-400/10">
                            <div className="text-center">
                                <p className="text-gold-gradient font-heading text-2xl font-bold">
                                    1.200+
                                </p>
                                <p className="text-gray-500 text-xs font-body">
                                    UMKM Pengguna
                                </p>
                            </div>
                            <div className="w-px h-10 bg-gold-400/10" />
                            <div className="text-center">
                                <p className="text-gold-gradient font-heading text-2xl font-bold">
                                    98%
                                </p>
                                <p className="text-gray-500 text-xs font-body">
                                    Kepuasan
                                </p>
                            </div>
                            <div className="w-px h-10 bg-gold-400/10" />
                            <div className="text-center">
                                <p className="text-gold-gradient font-heading text-2xl font-bold">
                                    Gratis
                                </p>
                                <p className="text-gray-500 text-xs font-body">
                                    Mulai Hari Ini
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Right – Dashboard Mockup */}
                    <div className="relative animate-float hidden lg:block">
                        <div className="relative rounded-2xl border border-gold-400/20 bg-dark-200/80 backdrop-blur-sm p-1 glow-gold-strong">
                            {/* Fake browser chrome */}
                            <div className="flex items-center gap-2 px-4 py-3 border-b border-gold-400/10">
                                <div className="w-3 h-3 rounded-full bg-red-500/60" />
                                <div className="w-3 h-3 rounded-full bg-yellow-500/60" />
                                <div className="w-3 h-3 rounded-full bg-green-500/60" />
                                <div className="ml-4 flex-1 h-6 rounded-md bg-dark-300 flex items-center px-3">
                                    <span className="text-gray-600 text-xs font-body">
                                        app.aurify.id/dashboard
                                    </span>
                                </div>
                            </div>
                            {/* Dashboard content mock */}
                            <div className="p-6 space-y-4">
                                {/* Header */}
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-gold-400 font-heading text-lg font-semibold">
                                            Dashboard Keuangan
                                        </p>
                                        <p className="text-gray-500 text-xs font-body">
                                            Februari 2026
                                        </p>
                                    </div>
                                    <div className="px-3 py-1 rounded-lg bg-gold-400/10 border border-gold-400/20">
                                        <span className="text-gold-400 text-xs font-body font-semibold">
                                            PRO
                                        </span>
                                    </div>
                                </div>
                                {/* Stats row */}
                                <div className="grid grid-cols-3 gap-3">
                                    {[
                                        {
                                            label: "Pendapatan",
                                            value: "Rp 45.2M",
                                            change: "+12%",
                                        },
                                        {
                                            label: "Pengeluaran",
                                            value: "Rp 28.1M",
                                            change: "-8%",
                                        },
                                        {
                                            label: "Profit",
                                            value: "Rp 17.1M",
                                            change: "+23%",
                                        },
                                    ].map((stat) => (
                                        <div
                                            key={stat.label}
                                            className="bg-dark-300/50 rounded-lg p-3 border border-gold-400/5"
                                        >
                                            <p className="text-gray-500 text-[10px] font-body">
                                                {stat.label}
                                            </p>
                                            <p className="text-white font-body font-bold text-sm">
                                                {stat.value}
                                            </p>
                                            <p className="text-green-400 text-[10px] font-body">
                                                {stat.change}
                                            </p>
                                        </div>
                                    ))}
                                </div>
                                {/* Chart placeholder */}
                                <div className="bg-dark-300/50 rounded-lg p-4 border border-gold-400/5">
                                    <div className="flex items-end gap-1 h-20">
                                        {[
                                            40, 55, 35, 70, 50, 80, 65, 90, 75,
                                            95, 85, 60,
                                        ].map((h, i) => (
                                            <div
                                                key={i}
                                                className="flex-1 rounded-sm"
                                                style={{
                                                    height: `${h}%`,
                                                    background: `linear-gradient(180deg, #FFD700 0%, #8B4513 100%)`,
                                                    opacity:
                                                        0.6 + (i / 12) * 0.4,
                                                }}
                                            />
                                        ))}
                                    </div>
                                    <div className="flex justify-between mt-2">
                                        <span className="text-gray-600 text-[9px] font-body">
                                            Jan
                                        </span>
                                        <span className="text-gray-600 text-[9px] font-body">
                                            Des
                                        </span>
                                    </div>
                                </div>
                                {/* Recent transactions */}
                                <div className="space-y-2">
                                    {[
                                        {
                                            name: "Invoice #1024",
                                            amount: "+Rp 5.200.000",
                                            color: "text-green-400",
                                        },
                                        {
                                            name: "Biaya Operasional",
                                            amount: "-Rp 1.800.000",
                                            color: "text-red-400",
                                        },
                                        {
                                            name: "Invoice #1025",
                                            amount: "+Rp 3.400.000",
                                            color: "text-green-400",
                                        },
                                    ].map((tx, i) => (
                                        <div
                                            key={i}
                                            className="flex items-center justify-between py-2 px-3 rounded-lg bg-dark-300/30"
                                        >
                                            <span className="text-gray-400 text-xs font-body">
                                                {tx.name}
                                            </span>
                                            <span
                                                className={`text-xs font-body font-semibold ${tx.color}`}
                                            >
                                                {tx.amount}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                        {/* Glow behind */}
                        <div className="absolute inset-0 -z-10 bg-gold-400/5 blur-3xl rounded-full scale-110" />
                    </div>
                </div>
            </div>
        </section>
    );
}
